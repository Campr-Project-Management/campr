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
                '/api/team/team-marvel',
                false,
                Response::HTTP_NOT_FOUND,
                [
                    'message' => 'Resource not found!',
                ],
            ],
            [
                '/api/team/1',
                true,
                Response::HTTP_OK,
                [
                    'id' => 1,
                    'name' => 'team_1',
                    'slug' => 'team-1',
                    'enabled' => false,
                    'description' => null,
                    'created_at' => '2017-01-01 00:00:00',
                ],
            ],
            [
                '/api/team/team-2',
                true,
                Response::HTTP_OK,
                [
                    'id' => 2,
                    'name' => 'team_2',
                    'slug' => 'team-2',
                    'enabled' => false,
                    'description' => null,
                    'created_at' => '2017-01-01 00:00:00',
                ],
            ],
        ];
    }
}
