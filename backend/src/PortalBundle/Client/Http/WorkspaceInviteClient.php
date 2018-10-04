<?php

namespace PortalBundle\Client\Http;

use AppBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class WorkspaceInviteClient
{
    const CREATE_ENDPOINT = '/api/workspace/{slug}/invites';

    /**
     * @var string
     */
    private $host;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * WorkspaceInviteClient constructor.
     *
     * @param Client          $client
     * @param string          $host
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, string $host, LoggerInterface $logger)
    {
        $this->host = $host;
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param string $slug
     * @param string $email
     * @param User   $user
     *
     * @throws \Exception
     *
     * @return array
     */
    public function create(string $slug, string $email, User $user): array
    {
        $url = strtr(self::CREATE_ENDPOINT, ['{slug}' => $slug]);
        $logContext = [
            'slug' => $slug,
            'username' => $user->getUsername(),
            'url' => $url,
        ];

        $this->logger->info('Creating workspace invite', $logContext);

        try {
            $response = $this->client->post(
                $url,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.$user->getApiToken(),
                        'Host' => $this->host,
                    ],
                    RequestOptions::JSON => [
                        'email' => $email,
                    ],
                ]
            );

            $body = $response->getBody();
            $data = $body->getContents();

            $data = json_decode($data, true);

            $this->logger->info(
                'Workspace invite successfully created',
                array_merge($logContext, ['response' => json_encode($data)])
            );

            return $data;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $logContext);

            throw $e;
        }
    }
}
