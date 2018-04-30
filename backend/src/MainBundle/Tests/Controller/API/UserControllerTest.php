<?php

namespace MainBundle\Tests\Controller\API;

use AppBundle\Entity\User;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForGetAction
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testGetAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', $url, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        if (isset($responseContent['apiToken'])) {
            $responseContent['apiToken'] = $token;
        }

        $userContent = json_decode($response->getContent(), true);

        if (!array_key_exists('message', $userContent)) {
            $email = md5(strtolower(trim($userContent['email'])));
            $responseContent['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        }

        if (isset($responseContent['updatedAt'])) {
            $responseContent['updatedAt'] = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        }
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForGetAction()
    {
        return [
            [
                '/api/users/999',
                false,
                Response::HTTP_NOT_FOUND,
                [
                    'message' => 'Resource not found!',
                ],
            ],
            [
                '/api/users/1',
                true,
                Response::HTTP_OK,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'isAdmin' => true,
                    'gravatar' => null,
                    'id' => 1,
                    'username' => 'superadmin',
                    'email' => 'superadmin@trisoft.ro',
                    'phone' => null,
                    'firstName' => 'FirstName1',
                    'lastName' => 'LastName1',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '2017-01-01 00:00:00',
                    'updatedAt' => '',
                    'activatedAt' => null,
                    'teams' => [],
                    'apiToken' => '',
                    'widgetSettings' => [],
                    'company' => null,
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'gplus' => null,
                    'linkedIn' => null,
                    'medium' => null,
                    'projectUsers' => [],
                    'signUpDetails' => [],
                    'locale' => 'en',
                    'avatar' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $token = $this->user->getApiToken();

        $this->client->request('PATCH', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());

        $user = $this->getUserByUsername('testuser');
        if (isset($responseContent['id'])) {
            $responseContent['id'] = $user->getId();
        }
        if (isset($responseContent['apiToken'])) {
            $responseContent['apiToken'] = $token;
        }
        if (isset($responseContent['createdAt'])) {
            $responseContent['createdAt'] = $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null;
        }
        if (isset($responseContent['updatedAt'])) {
            $responseContent['updatedAt'] = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        }
        if (isset($responseContent['gravatar'])) {
            $email = md5(strtolower(trim($user->getEmail())));
            $responseContent['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        }
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForEditAction()
    {
        return [
            [
                [
                    'plainPassword' => [
                        'first' => 'pass1',
                        'second' => 'pass11',
                    ],
                ],
                false,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'plainPassword' => [
                            'first' => ['The password fields do not match'],
                        ],
                    ],
                ],
            ],
            [
                [
                    'firstName' => 'User3',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'isAdmin' => true,
                    'gravatar' => '',
                    'id' => '',
                    'username' => 'testuser',
                    'email' => 'testuser@trisoft.ro',
                    'phone' => null,
                    'company' => null,
                    'firstName' => 'User3',
                    'lastName' => 'LastName',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '',
                    'updatedAt' => '',
                    'activatedAt' => null,
                    'teams' => [],
                    'apiToken' => '',
                    'widgetSettings' => [],
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'gplus' => null,
                    'linkedIn' => null,
                    'medium' => null,
                    'projectUsers' => [],
                    'signUpDetails' => [],
                    'locale' => 'en',
                    'avatar' => null,
                ],
            ],
        ];
    }
}
