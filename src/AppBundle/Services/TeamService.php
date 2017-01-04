<?php

namespace AppBundle\Services;

use GuzzleHttp\Client as HttpClient;
use Predis\Client as RedisClient;
use Symfony\Component\HttpFoundation\RequestStack;

class TeamService
{
    private $httpClient;

    private $redisClient;

    private $mainDomain;

    private $requestDomain;

    public function __construct(
        RequestStack $requestStack,
        RedisClient $redis,
        $mainDomain
    ) {
        $this->redisClient = $redis;
        $this->mainDomain = $mainDomain;

        $scheme = $requestStack->getMasterRequest()
            ? $requestStack->getMasterRequest()->getScheme()
            : 'http'
        ;

        $this->requestDomain = $requestStack->getMasterRequest()
            ? $requestStack->getMasterRequest()
            : $mainDomain
        ;

        $this->httpClient = new HttpClient([
            'base_uri' => sprintf('%s://%s/api/', $scheme, $mainDomain),
            'timeout' => 5,
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);
    }

    public function isEnabled($slug)
    {
        if ($this->mainDomain === $this->requestDomain) {
            return false;
        }

        if ($this->redisClient->exists($this->requestDomain)) {
            return (bool) $this->redisClient->get($this->requestDomain);
        }

        $req = $this->httpClient->get('team/'.$slug);
        if ($req->getStatusCode() !== 200) {
            $this->redisClient->setex($this->requestDomain, 60, false);
        }

        $body = $req->getBody();
        $data = '';
        while (!$body->eof()) {
            $data .= $body->read(1024);
        }

        $data = json_decode($data, true);
        $enabled = isset($data['enabled']) && $data['enabled'];
        $this->redisClient->setex($this->requestDomain, 60, $enabled);

        return $enabled;
    }
}
