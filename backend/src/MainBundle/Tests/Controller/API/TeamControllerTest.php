<?php

namespace MainBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class TeamControllerTest extends BaseController
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
                '/api/teams/teammarvel',
                false,
                Response::HTTP_NOT_FOUND,
                [
                    'message' => 'Resource not found!',
                ],
            ],
            [
                '/api/teams/1',
                true,
                Response::HTTP_OK,
                [
                    'user' => 3,
                    'userFullName' => 'FirstName3 LastName3',
                    'id' => 1,
                    'name' => 'team_1',
                    'slug' => 'team1',
                    'description' => null,
                    'enabled' => true,
                    'createdAt' => '2017-01-01 00:00:00',
                    'teamMembers' => [
                        [
                            'user' => 3,
                            'userFullName' => 'FirstName3 LastName3',
                            'team' => 1,
                            'teamName' => 'team_1',
                            'id' => 1,
                            'roles' => ['ROLE_SUPER_ADMIN'],
                        ],
                        [
                            'user' => 3,
                            'userFullName' => 'FirstName3 LastName3',
                            'team' => 1,
                            'teamName' => 'team_1',
                            'id' => 2,
                            'roles' => ['ROLE_SUPER_ADMIN'],
                        ],
                        [
                            'user' => 3,
                            'userFullName' => 'FirstName3 LastName3',
                            'team' => 1,
                            'teamName' => 'team_1',
                            'id' => 3,
                            'roles' => ['ROLE_SUPER_ADMIN'],
                        ],
                    ],
                    'teamSlugs' => [],
                    'teamInvites' => [],
                    'available' => false,
                    'logo' => null,
                ],
            ],
            [
                '/api/teams/team2',
                true,
                Response::HTTP_OK,
                [
                    'user' => 3,
                    'userFullName' => 'FirstName3 LastName3',
                    'id' => 2,
                    'name' => 'team_2',
                    'slug' => 'team2',
                    'description' => null,
                    'enabled' => true,
                    'createdAt' => '2017-01-01 00:00:00',
                    'teamMembers' => [],
                    'teamSlugs' => [],
                    'teamInvites' => [],
                    'available' => false,
                    'logo' => null,
                ],
            ],
        ];
    }
}
