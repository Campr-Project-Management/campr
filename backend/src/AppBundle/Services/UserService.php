<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserService
 * Service used to handle user related actions.
 */
class UserService
{
    private $httpClient;

    public function __construct(RequestStack $requestStack, $mainDomain)
    {
        $scheme = $requestStack->getMasterRequest()
            ? $requestStack->getMasterRequest()->getScheme()
            : 'http'
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

    /**
     * Returns a random token for user activation/reset.
     *
     * @return mixed
     */
    public function generateActivationResetToken()
    {
        return substr(md5(microtime()), rand(0, 26), 6);
    }

    /**
     * Synchronize user from main site with user from secondary website.
     *
     * @param User $user
     *
     * @return User
     */
    public function syncUser(User $user)
    {
        $request = $this->httpClient->get(
            'user',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$user->getApiToken(),
                ],
            ]
        );

        if ($request->getStatusCode() !== 200) {
            throw new NotFoundHttpException();
        }

        $body = $request->getBody();
        $data = '';
        while (!$body->eof()) {
            $data .= $body->read(1024);
        }
        $data = json_decode($data, true);

        $user->setUsername($data['username']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhone($data['phone']);
        $user->setRoles($data['roles']);
        $user->setWidgetSettings($data['widgetSettings']);
        $user->setIsEnabled($data['isEnabled']);
        $user->setIsSuspended($data['isSuspended']);
        $user->setFacebook($data['facebook']);
        $user->setTwitter($data['twitter']);
        $user->setInstagram($data['instagram']);
        $user->setGplus($data['gplus']);
        $user->setLinkedIn($data['linkedIn']);
        $user->setMedium($data['medium']);
        $user->setUpdatedAt(new \DateTime($data['updatedAt']));
        $user->setAvatar($data['avatar']);

        return $user;
    }
}
