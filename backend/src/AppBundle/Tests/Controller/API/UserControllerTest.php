<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForGetTeamsAction
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $expected
     */
    public function testGetTeamsAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        $expected
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'GET',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );

        $response = $this->client->getResponse();
        $actual = json_decode($response->getContent(), true);

        foreach ($actual as $i => $team) {
            $expected[$i]['userAvatarUrl'] = $team['userAvatarUrl'];
            foreach ($team['teamMembers'] as $j => $member) {
                $expected[$i]['teamMembers'][$j]['userAvatarUrl'] = $member['userAvatarUrl'];
            }
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function getDataForGetTeamsAction()
    {
        return [
            [
                '/api/users/3/teams',
                true,
                Response::HTTP_OK,
                [
                    [
                        'user' => 3,
                        'userFullName' => 'FirstName3 LastName3',
                        'id' => 1,
                        'name' => 'team_1',
                        'slug' => 'team-1',
                        'description' => null,
                        'enabled' => true,
                        'createdAt' => '2017-01-01 00:00:00',
                        'teamMembers' => [
                            [
                                'user' => 3,
                                'userFullName' => 'FirstName3 LastName3',
                                'userAvatarUrl' => '',
                                'team' => 1,
                                'teamName' => 'team_1',
                                'id' => 1,
                                'roles' => ['ROLE_SUPER_ADMIN'],
                            ],
                            [
                                'user' => 3,
                                'userFullName' => 'FirstName3 LastName3',
                                'userAvatarUrl' => '',
                                'team' => 1,
                                'teamName' => 'team_1',
                                'id' => 2,
                                'roles' => ['ROLE_SUPER_ADMIN'],
                            ],
                            [
                                'user' => 3,
                                'userFullName' => 'FirstName3 LastName3',
                                'userAvatarUrl' => '',
                                'team' => 1,
                                'teamName' => 'team_1',
                                'id' => 3,
                                'roles' => ['ROLE_SUPER_ADMIN'],
                            ],
                        ],
                        'teamInvites' => [],
                        'available' => false,
                        'logoUrl' => null,
                        'userAvatarUrl' => '',
                    ],
                    [
                        'user' => 3,
                        'userFullName' => 'FirstName3 LastName3',
                        'id' => 2,
                        'name' => 'team_2',
                        'slug' => 'team-2',
                        'description' => null,
                        'enabled' => true,
                        'createdAt' => '2017-01-01 00:00:00',
                        'teamMembers' => [],
                        'teamInvites' => [],
                        'available' => false,
                        'logoUrl' => null,
                        'userAvatarUrl' => '',
                    ],
                    [
                        'user' => 3,
                        'userFullName' => 'FirstName3 LastName3',
                        'id' => 3,
                        'name' => 'team_3',
                        'slug' => 'team-3',
                        'description' => null,
                        'enabled' => true,
                        'createdAt' => '2017-01-01 00:00:00',
                        'teamMembers' => [],
                        'teamInvites' => [],
                        'available' => false,
                        'logoUrl' => null,
                        'userAvatarUrl' => '',
                    ],
                ],
            ],
        ];
    }
}
