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

        $this->client->request('GET', '/api/workpackages', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
                    'totalItems' => 2,
                    'items' => [
                        [
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'parent' => null,
                            'parentName' => null,
                            'colorStatus' => 2,
                            'colorStatusName' => 'color-status2',
                            'colorStatusColor' => 'green',
                            'project' => 1,
                            'projectName' => 'project1',
                            'calendar' => null,
                            'calendarName' => null,
                            'workPackageStatus' => null,
                            'workPackageStatusName' => null,
                            'workPackageCategory' => null,
                            'workPackageCategoryName' => null,
                            'id' => 3,
                            'puid' => '1234',
                            'name' => 'work-package3',
                            'progress' => 0,
                            'scheduledStartAt' => '2017-01-01',
                            'scheduledFinishAt' => '2017-01-05',
                            'forecastStartAt' => null,
                            'forecastFinishAt' => null,
                            'actualStartAt' => null,
                            'actualFinishAt' => null,
                            'content' => 'content',
                            'results' => null,
                            'isKeyMilestone' => false,
                            'assignments' => [],
                            'labels' => [],
                        ],
                        [
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'parent' => null,
                            'parentName' => null,
                            'colorStatus' => 2,
                            'colorStatusName' => 'color-status2',
                            'colorStatusColor' => 'green',
                            'project' => 1,
                            'projectName' => 'project1',
                            'calendar' => null,
                            'calendarName' => null,
                            'workPackageStatus' => null,
                            'workPackageStatusName' => null,
                            'workPackageCategory' => null,
                            'workPackageCategoryName' => null,
                            'id' => 4,
                            'puid' => '123456',
                            'name' => 'work-package4',
                            'progress' => 0,
                            'scheduledStartAt' => '2017-01-01',
                            'scheduledFinishAt' => '2017-01-05',
                            'forecastStartAt' => null,
                            'forecastFinishAt' => null,
                            'actualStartAt' => null,
                            'actualFinishAt' => null,
                            'content' => 'content4',
                            'results' => null,
                            'isKeyMilestone' => false,
                            'assignments' => [],
                            'labels' => [],
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
                '/api/workpackages/3',
                true,
                Response::HTTP_OK,
                [
                    'responsibility' => 4,
                    'responsibilityFullName' => 'FirstName4 LastName4',
                    'parent' => null,
                    'parentName' => null,
                    'colorStatus' => 2,
                    'colorStatusName' => 'color-status2',
                    'colorStatusColor' => 'green',
                    'project' => 1,
                    'projectName' => 'project1',
                    'calendar' => null,
                    'calendarName' => null,
                    'workPackageStatus' => null,
                    'workPackageStatusName' => null,
                    'workPackageCategory' => null,
                    'workPackageCategoryName' => null,
                    'id' => 3,
                    'puid' => '1234',
                    'name' => 'work-package3',
                    'progress' => 0,
                    'scheduledStartAt' => '2017-01-01',
                    'scheduledFinishAt' => '2017-01-05',
                    'forecastStartAt' => null,
                    'forecastFinishAt' => null,
                    'actualStartAt' => null,
                    'actualFinishAt' => null,
                    'content' => 'content',
                    'results' => null,
                    'isKeyMilestone' => false,
                    'assignments' => [],
                    'labels' => [],
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

        $this->client->request('POST', '/api/workpackages', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                Response::HTTP_CREATED,
                [
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'parent' => null,
                    'parentName' => null,
                    'colorStatus' => null,
                    'colorStatusName' => null,
                    'colorStatusColor' => null,
                    'project' => null,
                    'projectName' => null,
                    'calendar' => null,
                    'calendarName' => null,
                    'workPackageStatus' => null,
                    'workPackageStatusName' => null,
                    'workPackageCategory' => null,
                    'workPackageCategoryName' => null,
                    'id' => 5,
                    'puid' => '555',
                    'name' => 'task',
                    'progress' => 0,
                    'scheduledStartAt' => null,
                    'scheduledFinishAt' => null,
                    'forecastStartAt' => null,
                    'forecastFinishAt' => null,
                    'actualStartAt' => null,
                    'actualFinishAt' => null,
                    'content' => null,
                    'results' => null,
                    'isKeyMilestone' => false,
                    'assignments' => [],
                    'labels' => [],
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

        $this->client->request('PATCH', '/api/workpackages/5', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'colorStatus' => 2,
                    'project' => 1,
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'parent' => null,
                    'parentName' => null,
                    'colorStatus' => 2,
                    'colorStatusName' => 'color-status2',
                    'colorStatusColor' => 'green',
                    'project' => 1,
                    'projectName' => 'project1',
                    'calendar' => null,
                    'calendarName' => null,
                    'workPackageStatus' => null,
                    'workPackageStatusName' => null,
                    'workPackageCategory' => null,
                    'workPackageCategoryName' => null,
                    'id' => 5,
                    'puid' => '555',
                    'name' => 'task123',
                    'progress' => 0,
                    'scheduledStartAt' => null,
                    'scheduledFinishAt' => null,
                    'forecastStartAt' => null,
                    'forecastFinishAt' => null,
                    'actualStartAt' => null,
                    'actualFinishAt' => null,
                    'content' => null,
                    'results' => null,
                    'isKeyMilestone' => false,
                    'assignments' => [],
                    'labels' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForDeleteAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     */
    public function testDeleteAction(
        $isResponseSuccessful,
        $responseStatusCode
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('DELETE', '/api/workpackages/5', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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

    /**
     * @dataProvider getDataForAssignmentsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testAssignmentsAction(
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
    public function getDataForAssignmentsAction()
    {
        return [
            [
                '/api/workpackages/1/assignments',
                true,
                Response::HTTP_OK,
                [
                    [
                        'workPackage' => 1,
                        'workPackageName' => 'work-package1',
                        'percentWorkComplete' => 0,
                        'workPackageProjectWorkCostType' => null,
                        'workPackageProjectWorkCostTypeName' => null,
                        'id' => 3,
                        'timephases' => [
                            [
                                'assignment' => 3,
                                'id' => 3,
                                'type' => 1,
                                'unit' => 2,
                                'value' => 'value',
                                'startedAt' => '2017-01-01 12:00:00',
                                'finishedAt' => '2017-01-01 15:00:00',
                            ],
                        ],
                        'milestone' => 2,
                        'confirmed' => false,
                        'startedAt' => '2017-01-01 00:00:00',
                        'finishedAt' => '2017-01-04 00:00:00',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForAssignmentsCreateAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testAssignmentsCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/workpackages/1/assignments', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForAssignmentsCreateAction()
    {
        return [
            [
                [
                    'milestone' => 1,
                    'percentWorkComplete' => 0,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'workPackage' => 1,
                    'workPackageName' => 'work-package1',
                    'percentWorkComplete' => 0,
                    'workPackageProjectWorkCostType' => null,
                    'workPackageProjectWorkCostTypeName' => null,
                    'id' => 5,
                    'timephases' => [],
                    'milestone' => 1,
                    'confirmed' => false,
                    'startedAt' => null,
                    'finishedAt' => null,
                ],
            ],
        ];
    }
}
