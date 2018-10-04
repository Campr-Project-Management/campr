<?php

namespace PortalBundle\Client\Http;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class WorkspaceClient
{
    const CHECK_ENABLED_ENDPOINT = '/api/workspaces/{slug}/check-enabled';

    const CREATED_WEBHOOK_ENDPOINT = '/webhook/workspaces/{uuid}/created';

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
     * @var string
     */
    private $webhookSecret;

    /**
     * WorkspaceClient constructor.
     *
     * @param Client          $client
     * @param string          $host
     * @param LoggerInterface $logger
     * @param string          $webhookSecret
     */
    public function __construct(Client $client, string $host, LoggerInterface $logger, string $webhookSecret)
    {
        $this->host = $host;
        $this->client = $client;
        $this->logger = $logger;
        $this->webhookSecret = $webhookSecret;
    }

    /**
     * @param string $slug
     * @param User   $user
     *
     * @return bool
     */
    public function isEnabled(string $slug, User $user): bool
    {
        $url = strtr(self::CHECK_ENABLED_ENDPOINT, ['{slug}' => $slug]);
        $logContext = [
            'slug' => $slug,
            'username' => $user->getUsername(),
            'url' => $url,
        ];

        $this->logger->info('Checking if workspace is enabled', $logContext);

        try {
            $response = $this->client->get(
                $url,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.$user->getApiToken(),
                        'Host' => $this->host,
                    ],
                ]
            );

            $body = $response->getBody();
            $data = $body->getContents();

            $data = json_decode($data, true);

            $this->logger->info(
                'Workspace enabled check finished',
                array_merge($logContext, ['response' => json_encode($data)])
            );

            return (bool) ($data['enabled'] ?? false);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $logContext);

            throw $e;
        }
    }

    /**
     * @param Team $team
     *
     * @return bool
     */
    public function createdWebhook(Team $team): bool
    {
        $uuid = $team->getUuid();
        Assert::notEmpty($uuid, 'Team UUID is empty');

        $url = strtr(self::CREATED_WEBHOOK_ENDPOINT, ['{uuid}' => $uuid]);
        $logContext = [
            'team' => $team->getSlug(),
            'url' => $url,
        ];

        $this->logger->info('Calling portal created webhook', $logContext);

        try {
            $this->client->post(
                $url,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->webhookSecret,
                        'Host' => $this->host,
                    ],
                ]
            );

            $this->logger->info('Portal created webhook finished', $logContext);

            return true;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $logContext);

            throw $e;
        }
    }
}
