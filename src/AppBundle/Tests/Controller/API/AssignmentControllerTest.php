<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class AssignmentControllerTest extends BaseController
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
    public function getDataForListAction()
    {
        return [
            [
                '/api/assignment/1/list',
                true,
                Response::HTTP_OK,
                [
                    [
                        'id' => 3,
                        'work_package' => 1,
                        'work_package_name' => 'work-package1',
                        'percent_complete' => 0,
                        'milestone' => 2,
                        'confirmed' => false,
                        'started_at' => '2017-01-01 00:00:00',
                        'finished_at' => '2017-01-04 00:00:00',
                        'timephases' => [
                            [
                                'id' => 3,
                                'type' => 1,
                                'unit' => 2,
                                'value' => 'value',
                                'started_at' => '2017-01-01 12:00:00',
                                'finished_at' => '2017-01-01 15:00:00',
                            ],
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
                '/api/assignment/1',
                true,
                Response::HTTP_OK,
                [
                    'id' => 1,
                    'work_package' => 2,
                    'work_package_name' => 'work-package2',
                    'percent_complete' => 0,
                    'milestone' => 1,
                    'confirmed' => false,
                    'started_at' => '2016-12-12 00:00:00',
                    'finished_at' => '2017-01-01 00:00:00',
                    'timephases' => [
                        [
                            'id' => 1,
                            'type' => 1,
                            'unit' => 1,
                            'value' => 'value1',
                            'started_at' => '2016-12-12 11:30:00',
                            'finished_at' => '2016-12-12 13:00:00',
                        ],
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

        $this->client->request('POST', '/api/assignment/create', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'workPackage' => 1,
                    'milestone' => 1,
                    'percentWorkComplete' => 0,
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 4,
                    'work_package' => 1,
                    'work_package_name' => 'work-package1',
                    'percent_complete' => 0,
                    'milestone' => 1,
                    'confirmed' => false,
                    'started_at' => null,
                    'finished_at' => null,
                    'timephases' => [],
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

        $this->client->request('PATCH', '/api/assignment/4/edit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'milestone' => 2,
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 4,
                    'work_package' => 1,
                    'work_package_name' => 'work-package1',
                    'percent_complete' => 0,
                    'milestone' => 2,
                    'confirmed' => false,
                    'started_at' => null,
                    'finished_at' => null,
                    'timephases' => [],
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

        $this->client->request('GET', '/api/assignment/4/delete', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
