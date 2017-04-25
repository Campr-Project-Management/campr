<?php

namespace AppBundle\Services;

use GuzzleHttp\Client as HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BaseClientService
 * Service used as base service for services that use httpClient.
 */
abstract class BaseClientService
{
    protected $httpClient;

    protected $scheme;

    protected $mainDomain;

    /**
     * BaseClientService constructor.
     *
     * @param RequestStack $requestStack
     * @param $mainDomain
     */
    public function __construct(RequestStack $requestStack, $mainDomain)
    {
        $this->mainDomain = $mainDomain;

        $this->scheme = $requestStack->getMasterRequest()
            ? $requestStack->getMasterRequest()->getScheme()
            : 'http';

        $this->httpClient = new HttpClient([
            'base_uri' => sprintf('%s://%s/api/', $this->scheme, $mainDomain),
            'timeout' => 5000,
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ],
        ]);
    }
}
