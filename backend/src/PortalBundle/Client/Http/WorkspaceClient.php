<?php

namespace PortalBundle\Client\Http;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Component\Repository\RepositoryInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class WorkspaceClient
{
    const SHOW_ENDPOINT = '/api/workspaces/{slug}';

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
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * WorkspaceClient constructor.
     *
     * @param Client              $client
     * @param string              $host
     * @param LoggerInterface     $logger
     * @param string              $webhookSecret
     * @param RepositoryInterface $userRepository
     */
    public function __construct(
        Client $client,
        string $host,
        LoggerInterface $logger,
        string $webhookSecret,
        RepositoryInterface $userRepository
    ) {
        $this->host = $host;
        $this->client = $client;
        $this->logger = $logger;
        $this->webhookSecret = $webhookSecret;
        $this->userRepository = $userRepository;
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
     * @throws \Exception
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

    /**
     * @param Team $team
     * @param User $user
     *
     * @return Team
     */
    public function get(Team $team, User $user): Team
    {
        $url = strtr(self::SHOW_ENDPOINT, ['{slug}' => $team->getSlug()]);
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$user->getApiToken(),
                'Host' => $this->host,
            ],
        ];

        $response = $this->client->get($url, $options);

        $data = $response->getBody()->getContents();

        $this->logger->info(
            'Workspace::get',
            [
                'url' => $url,
                'options' => $options,
                'response' => $data,
            ]
        );

        $data = json_decode($data, true);

        $team->setUuid($data['uuid']);
        $team->setName($data['name']);
        $team->setSlug($data['slug']);
        $team->setDescription($data['slug']);
        $team->setEnabled($data['enabled']);
        $team->setLogoUrl($data['logoUrl']);
        $team->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));
        $team->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']));

        $user = $this->getUserByUsername($data['userUsername']);
        $team->setUser($user);

        return $team;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    private function getUserByUsername(string $username): User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['username' => $username]);

        return $user;
    }
}
