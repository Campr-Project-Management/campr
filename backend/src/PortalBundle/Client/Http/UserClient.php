<?php

namespace PortalBundle\Client\Http;

use AppBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class UserClient
{
    const UPDATE_ENDPOINT = '/api/users/{uuid}';

    const SHOW_ENDPOINT = '/api/users/{uuid}';

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
     * UserClient constructor.
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
     * @param User  $user
     * @param array $data
     *
     * @return array
     */
    public function update(User $user, array $data): array
    {
        $url = strtr(self::UPDATE_ENDPOINT, ['{uuid}' => $user->getUuid()]);
        $response = $this->client->patch(
            $url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$user->getApiToken(),
                    'Host' => $this->host,
                ],
                RequestOptions::JSON => $data,
            ]
        );

        $body = $response->getBody();
        $data = $body->getContents();

        return json_decode($data, true);
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function get(User $user): User
    {
        $url = strtr(self::SHOW_ENDPOINT, ['{uuid}' => $user->getUuid()]);
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$user->getApiToken(),
                'Host' => $this->host,
            ],
        ];

        $response = $this->client->get($url, $options);

        $data = $response->getBody()->getContents();

        $this->logger->info(
            'User::get',
            [
                'url' => $url,
                'options' => $options,
                'response' => $data,
            ]
        );

        $data = json_decode($data, true);

        $user->setUsername($data['username']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhone($data['phone']);
        $user->setEnabled($data['enabled']);
        $user->setSuspended($data['suspended']);
        $user->setFacebook($data['facebook']);
        $user->setTwitter($data['twitter']);
        $user->setInstagram($data['instagram']);
        $user->setGplus($data['gplus']);
        $user->setLinkedIn($data['linkedIn']);
        $user->setMedium($data['medium']);
        $user->setUpdatedAt(new \DateTime($data['updatedAt']));
        $user->setAvatar($data['avatar']);
        $user->setAvatarUrl($data['avatarUrl']);
        $user->setLocale($data['locale']);
        $user->setTheme($data['theme']);

        return $user;
    }
}
