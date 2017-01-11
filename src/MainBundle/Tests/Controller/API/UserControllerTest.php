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
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $this->login($this->user);

        $token = $this->user->getApiToken();
        $this->client->request('GET', $url, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();
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
                '/api/user/999',
                false,
                Response::HTTP_NOT_FOUND,
                [
                    'message' => 'Resource not found!',
                ],
            ],
            [
                '/api/user/1',
                true,
                Response::HTTP_OK,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'id' => 1,
                    'username' => 'superadmin',
                    'email' => 'superadmin@trisoft.ro',
                    'phone' => null,
                    'firstName' => 'FirstName1',
                    'lastName' => 'LastName1',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '2017-01-01 00:00:00',
                    'updatedAt' => null,
                    'activatedAt' => null,
                    'teams' => [],
                    'teamMembers' => [],
                    'teamInvites' => [],
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

        $this->client->request('PATCH', '/api/user/edit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());

        $user = $this->getUserByUsername('testuser');
        if (isset($responseContent['id'])) {
            $responseContent['id'] = $user->getId();
        }
        if (isset($responseContent['createdAt'])) {
            $responseContent['createdAt'] = $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null;
        }
        if (isset($responseContent['updatedAt'])) {
            $responseContent['updatedAt'] = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
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
                true,
                Response::HTTP_OK,
                [
                    'The password fields do not match',
                ],
            ],
            [
                [
                    'firstName' => 'User3',
                ],
                true,
                Response::HTTP_OK,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'id' => '',
                    'username' => 'testuser',
                    'email' => 'testuser@trisoft.ro',
                    'phone' => null,
                    'firstName' => 'User3',
                    'lastName' => 'LastName',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '',
                    'updatedAt' => '',
                    'activatedAt' => null,
                    'teams' => [],
                    'teamMembers' => [],
                    'teamInvites' => [],
                ],
            ],
        ];
    }
}
