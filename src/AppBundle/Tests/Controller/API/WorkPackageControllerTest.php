<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkPackageControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForListAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testListAction(
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('user4');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/workpackage/list', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
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
                        'id' => 3,
                        'name' => 'work-package3',
                        'project' => null,
                        'project_name' => null,
                        'responsibility' => 4,
                        'responsibility_name' => 'FirstName4 LastName4',
                        'schedules' => [
                            'base' => [
                                'start' => '2017-01-01 00:00:00',
                                'finish' => '2017-01-05 00:00:00',
                            ],
                            'forecast' => [
                                'start' => null,
                                'finish' => null,
                            ],
                        ],
                        'progress' => 0,
                        'content' => 'content',
                        'color_status' => [
                            'id' => 2,
                            'name' => 'color-status2',
                            'color' => 'green',
                        ],
                    ],
                    [
                        'id' => 4,
                        'name' => 'work-package4',
                        'project' => null,
                        'project_name' => null,
                        'responsibility' => 4,
                        'responsibility_name' => 'FirstName4 LastName4',
                        'schedules' => [
                            'base' => [
                                'start' => '2017-01-01 00:00:00',
                                'finish' => '2017-01-05 00:00:00',
                            ],
                            'forecast' => [
                                'start' => null,
                                'finish' => null,
                            ],
                        ],
                        'progress' => 0,
                        'content' => 'content4',
                        'color_status' => [
                            'id' => 2,
                            'name' => 'color-status2',
                            'color' => 'green',
                        ],
                    ],
                ],
            ],
        ];
    }

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
                '/api/workpackage/3',
                true,
                Response::HTTP_OK,
                [
                    'id' => 3,
                    'name' => 'work-package3',
                    'project' => null,
                    'project_name' => null,
                    'responsibility' => 4,
                    'responsibility_name' => 'FirstName4 LastName4',
                    'schedules' => [
                        'base' => [
                            'start' => '2017-01-01 00:00:00',
                            'finish' => '2017-01-05 00:00:00',
                        ],
                        'forecast' => [
                            'start' => null,
                            'finish' => null,
                        ],
                    ],
                    'progress' => 0,
                    'content' => 'content',
                    'color_status' => [
                        'id' => 2,
                        'name' => 'color-status2',
                        'color' => 'green',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/workpackage/create', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'puid' => '555',
                    'name' => 'task',
                    'progress' => 0,
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 5,
                    'name' => 'task',
                    'project' => null,
                    'project_name' => null,
                    'responsibility' => null,
                    'responsibility_name' => null,
                    'schedules' => [
                        'base' => [
                            'start' => null,
                            'finish' => null,
                        ],
                        'forecast' => [
                            'start' => null,
                            'finish' => null,
                        ],
                    ],
                    'progress' => '0',
                    'content' => null,
                    'color_status' => [
                        'id' => null,
                        'name' => null,
                        'color' => null,
                    ],
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
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('PATCH', '/api/workpackage/5/edit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
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
                    'name' => 'task123',
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 5,
                    'name' => 'task123',
                    'project' => null,
                    'project_name' => null,
                    'responsibility' => null,
                    'responsibility_name' => null,
                    'schedules' => [
                        'base' => [
                            'start' => null,
                            'finish' => null,
                        ],
                        'forecast' => [
                            'start' => null,
                            'finish' => null,
                        ],
                    ],
                    'progress' => 0,
                    'content' => null,
                    'color_status' => [
                        'id' => null,
                        'name' => null,
                        'color' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForDeleteAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testDeleteAction(
        $isResponseSuccessful,
        $responseStatusCode
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/workpackage/5/delete', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function getDataForDeleteAction()
    {
        return [
            [
                true,
                Response::HTTP_NO_CONTENT,
            ],
        ];
    }
}
