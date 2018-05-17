<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ColorStatusControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForListAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testListAction(
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'GET',
            '/api/color-statuses',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );

        $response = $this->client->getResponse();
        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
    }

    /**
     * @return array
     */
    public function getDataForListAction()
    {
        return [
            [
                true,
                Response::HTTP_OK,
                [
                    [
                        'id' => 1,
                        'name' => 'color_status.not_started',
                        'description' => null,
                        'color' => '#c87369',
                        'sequence' => 0,
                        'code' => 'red',
                    ],
                    [
                        'id' => 2,
                        'name' => 'color_status.in_progress',
                        'description' => null,
                        'color' => '#ccba54',
                        'sequence' => 1,
                        'code' => 'yellow',
                    ],
                    [
                        'id' => 3,
                        'name' => 'color_status.finished',
                        'description' => null,
                        'color' => '#5fc3a5',
                        'sequence' => 2,
                        'code' => 'green',
                    ],
                    [
                        'id' => 4,
                        'name' => 'color-status1',
                        'description' => null,
                        'color' => 'green',
                        'sequence' => 1,
                        'code' => 'code1',
                    ],
                    [
                        'id' => 5,
                        'name' => 'color-status2',
                        'description' => null,
                        'color' => 'green',
                        'sequence' => 2,
                        'code' => 'code2',
                    ],
                ],
            ],
        ];
    }
}
