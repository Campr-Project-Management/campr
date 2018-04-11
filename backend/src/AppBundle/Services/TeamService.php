<?php

namespace AppBundle\Services;

use Predis\Client as RedisClient;
use Symfony\Component\HttpFoundation\RequestStack;

class TeamService extends BaseClientService
{
    private $redisClient;

    private $requestDomain;

    public function __construct(RequestStack $requestStack, $mainDomain, RedisClient $redis)
    {
        parent::__construct($requestStack, $mainDomain);

        $this->redisClient = $redis;

        $this->requestDomain = $requestStack->getMasterRequest()
            ? $requestStack->getMasterRequest()
            : $mainDomain
        ;
    }

    public function isEnabled($slug)
    {
        if ($this->mainDomain === $this->requestDomain) {
            return false;
        }

        if ($this->redisClient->exists($this->requestDomain)) {
            return (bool) $this->redisClient->get($this->requestDomain);
        }

        $req = $this->httpClient->get('teams/'.$slug.'/enabled');
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
