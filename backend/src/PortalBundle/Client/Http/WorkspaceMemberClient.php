<?php

namespace PortalBundle\Client\Http;

use AppBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class WorkspaceMemberClient
{
    const DELETE_ENDPOINT = '/api/workspace/{slug}/members';

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
    public function delete(string $slug, string $email, User $user): array
    {
        $url = strtr(self::DELETE_ENDPOINT, ['{slug}' => $slug]);
        $logContext = [
            'slug' => $slug,
            'username' => $user->getUsername(),
            'url' => $url,
            'email' => $email,
        ];

        $this->logger->info('Delete workspace member', $logContext);

        try {
            $response = $this->client->delete(
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
                'Workspace member successfully deleted',
                array_merge($logContext, ['response' => json_encode($data)])
            );

            return $data;
        } catch (ClientException $e) {
            $this->logger->error($e->getMessage(), $logContext);
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 400 && $statusCode <= 499) {
                $contents = $response->getBody()->getContents();
                $response = json_decode($contents, true);

                throw new BadResponseException($response['message'], $e->getRequest(), $e->getResponse());
            }

            throw $e;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $logContext);

            throw $e;
        }
    }
}
