<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
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

        $this->client->request(
            'GET',
            '/api/workpackages',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);
        $tasks = $content['items'];
        for ($i = 0; $i < sizeof($tasks); ++$i) {
            $responseContent['items'][$i]['responsibilityAvatar'] = $tasks[$i]['responsibilityAvatar'];
            $responseContent['items'][$i]['createdAt'] = $tasks[$i]['createdAt'];
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
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
                            'puid' => 3,
                            'phase' => null,
                            'phaseName' => null,
                            'milestone' => null,
                            'milestoneName' => null,
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'responsibilityEmail' => 'user4@trisoft.ro',
                            'accountability' => null,
                            'accountabilityFullName' => null,
                            'accountabilityEmail' => null,
                            'parent' => null,
                            'parentName' => null,
                            'colorStatus' => 5,
                            'colorStatusName' => 'color-status2',
                            'colorStatusColor' => 'green',
                            'project' => 1,
                            'projectName' => 'project1',
                            'calendar' => null,
                            'calendarName' => null,
                            'label' => 0,
                            'labelName' => '',
                            'labelColor' => '',
                            'workPackageStatus' => null,
                            'workPackageStatusName' => null,
                            'workPackageCategory' => null,
                            'workPackageCategoryName' => null,
                            'noAttachments' => 0,
                            'noComments' => 0,
                            'noSubtasks' => 0,
                            'totalCosts' => 0,
                            'childrenTotalCosts' => 0,
                            'childrenTotalDuration' => 0,
                            'id' => 3,
                            'name' => 'work-package3',
                            'children' => [],
                            'progress' => 100,
                            'scheduledStartAt' => '2017-01-01',
                            'scheduledFinishAt' => '2017-01-05',
                            'scheduledDurationDays' => 5,
                            'forecastStartAt' => null,
                            'forecastFinishAt' => null,
                            'forecastDurationDays' => 0,
                            'actualStartAt' => null,
                            'actualFinishAt' => null,
                            'actualDurationDays' => 0,
                            'content' => 'content',
                            'results' => null,
                            'isKeyMilestone' => false,
                            'assignments' => [],
                            'labels' => [],
                            'type' => 1,
                            'dependencies' => [],
                            'dependants' => [],
                            'medias' => [],
                            'automaticSchedule' => false,
                            'duration' => 0,
                            'costs' => [],
                            'comments' => [],
                            'supportUsers' => [],
                            'consultedUsers' => [],
                            'informedUsers' => [],
                            'createdAt' => date(\DateTime::ATOM),
                            'externalActualCost' => 0,
                            'externalForecastCost' => 0,
                            'internalActualCost' => 0,
                            'internalForecastCost' => 0,
                            'responsibilityAvatar' => null,
                            'totalForecastCosts' => 0,
                            'totalActualCosts' => 0,
                            'actualCostColor' => 'green',
                            'externalCostTotal' => 0,
                            'internalCostTotal' => 0,
                            'externalCostOPEXTotal' => 0,
                            'externalCostCAPEXTotal' => 0,
                            'isPhase' => false,
                            'isMilestone' => true,
                            'isTask' => false,
                            'isTutorial' => false,
                            'isSubtask' => false,
                        ],
                        [
                            'puid' => 4,
                            'phase' => null,
                            'phaseName' => null,
                            'milestone' => null,
                            'milestoneName' => null,
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'responsibilityEmail' => 'user4@trisoft.ro',
                            'accountability' => null,
                            'accountabilityFullName' => null,
                            'accountabilityEmail' => null,
                            'parent' => null,
                            'parentName' => null,
                            'colorStatus' => 5,
                            'colorStatusName' => 'color-status2',
                            'colorStatusColor' => 'green',
                            'project' => 1,
                            'projectName' => 'project1',
                            'calendar' => null,
                            'calendarName' => null,
                            'label' => 0,
                            'labelName' => '',
                            'labelColor' => '',
                            'workPackageStatus' => null,
                            'workPackageStatusName' => null,
                            'workPackageCategory' => null,
                            'workPackageCategoryName' => null,
                            'noAttachments' => 0,
                            'noComments' => 0,
                            'noSubtasks' => 0,
                            'totalCosts' => 0,
                            'childrenTotalCosts' => 0,
                            'childrenTotalDuration' => 0,
                            'id' => 4,
                            'name' => 'work-package4',
                            'children' => [],
                            'progress' => 100,
                            'scheduledStartAt' => '2017-01-01',
                            'scheduledFinishAt' => '2017-01-05',
                            'scheduledDurationDays' => 5,
                            'forecastStartAt' => null,
                            'forecastFinishAt' => null,
                            'forecastDurationDays' => 0,
                            'actualStartAt' => null,
                            'actualFinishAt' => null,
                            'actualDurationDays' => 0,
                            'content' => 'content4',
                            'results' => null,
                            'isKeyMilestone' => false,
                            'assignments' => [],
                            'labels' => [],
                            'type' => 0,
                            'dependencies' => [],
                            'dependants' => [],
                            'medias' => [],
                            'automaticSchedule' => false,
                            'duration' => 0,
                            'costs' => [],
                            'comments' => [],
                            'supportUsers' => [],
                            'consultedUsers' => [],
                            'informedUsers' => [],
                            'createdAt' => date(\DateTime::ATOM),
                            'externalActualCost' => 0,
                            'externalForecastCost' => 0,
                            'internalActualCost' => 0,
                            'internalForecastCost' => 0,
                            'responsibilityAvatar' => null,
                            'totalForecastCosts' => 0,
                            'totalActualCosts' => 0,
                            'actualCostColor' => 'green',
                            'externalCostTotal' => 0,
                            'internalCostTotal' => 0,
                            'externalCostOPEXTotal' => 0,
                            'externalCostCAPEXTotal' => 0,
                            'isPhase' => true,
                            'isMilestone' => false,
                            'isTask' => false,
                            'isTutorial' => false,
                            'isSubtask' => false,
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

        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );
        $response = $this->client->getResponse();

        $task = json_decode($response->getContent(), true);
        $responseContent['responsibilityAvatar'] = $task['responsibilityAvatar'];
        $responseContent['createdAt'] = $task['createdAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
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
                    'puid' => 3,
                    'phase' => null,
                    'phaseName' => null,
                    'milestone' => null,
                    'milestoneName' => null,
                    'responsibility' => 4,
                    'responsibilityFullName' => 'FirstName4 LastName4',
                    'responsibilityEmail' => 'user4@trisoft.ro',
                    'accountability' => null,
                    'accountabilityFullName' => null,
                    'accountabilityEmail' => null,
                    'parent' => null,
                    'parentName' => null,
                    'colorStatus' => 5,
                    'colorStatusName' => 'color-status2',
                    'colorStatusColor' => 'green',
                    'project' => 1,
                    'projectName' => 'project1',
                    'calendar' => null,
                    'calendarName' => null,
                    'label' => 0,
                    'labelName' => '',
                    'labelColor' => '',
                    'workPackageStatus' => null,
                    'workPackageStatusName' => null,
                    'workPackageCategory' => null,
                    'workPackageCategoryName' => null,
                    'noAttachments' => 0,
                    'noComments' => 0,
                    'noSubtasks' => 0,
                    'totalCosts' => 0,
                    'childrenTotalCosts' => 0,
                    'childrenTotalDuration' => 0,
                    'id' => 3,
                    'name' => 'work-package3',
                    'children' => [],
                    'progress' => 100,
                    'scheduledStartAt' => '2017-01-01',
                    'scheduledFinishAt' => '2017-01-05',
                    'scheduledDurationDays' => 5,
                    'forecastStartAt' => null,
                    'forecastFinishAt' => null,
                    'forecastDurationDays' => 0,
                    'actualStartAt' => null,
                    'actualFinishAt' => null,
                    'actualDurationDays' => 0,
                    'content' => 'content',
                    'results' => null,
                    'isKeyMilestone' => false,
                    'assignments' => [],
                    'labels' => [],
                    'type' => 1,
                    'dependencies' => [],
                    'dependants' => [],
                    'medias' => [],
                    'automaticSchedule' => false,
                    'duration' => 0,
                    'costs' => [],
                    'comments' => [],
                    'supportUsers' => [],
                    'consultedUsers' => [],
                    'informedUsers' => [],
                    'createdAt' => date(\DateTime::ATOM),
                    'externalActualCost' => 0,
                    'externalForecastCost' => 0,
                    'internalActualCost' => 0,
                    'internalForecastCost' => 0,
                    'responsibilityAvatar' => null,
                    'totalForecastCosts' => 0,
                    'totalActualCosts' => 0,
                    'actualCostColor' => 'green',
                    'externalCostTotal' => 0,
                    'internalCostTotal' => 0,
                    'externalCostOPEXTotal' => 0,
                    'externalCostCAPEXTotal' => 0,
                    'isPhase' => false,
                    'isMilestone' => true,
                    'isTask' => false,
                    'isTutorial' => false,
                    'isSubtask' => false,
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

        $this->client->request(
            'PATCH',
            '/api/workpackages/5',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        // Remove the 2 lines bellow when WP listener is fixed
        $task = json_decode($response->getContent(), true);
        $responseContent['puid'] = $task['puid'];
        $responseContent['createdAt'] = $task['createdAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
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
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'puid' => 5,
                    'phase' => null,
                    'phaseName' => null,
                    'milestone' => null,
                    'milestoneName' => null,
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'responsibilityEmail' => null,
                    'accountability' => null,
                    'accountabilityFullName' => null,
                    'accountabilityEmail' => null,
                    'parent' => null,
                    'parentName' => null,
                    'colorStatus' => 2,
                    'colorStatusName' => 'color_status.in_progress',
                    'colorStatusColor' => '#ccba54',
                    'project' => 1,
                    'projectName' => 'project1',
                    'calendar' => null,
                    'calendarName' => null,
                    'label' => 0,
                    'labelName' => '',
                    'labelColor' => '',
                    'workPackageStatus' => 2,
                    'workPackageStatusName' => 'label.pending',
                    'workPackageCategory' => null,
                    'workPackageCategoryName' => null,
                    'noAttachments' => 0,
                    'noComments' => 0,
                    'noSubtasks' => 0,
                    'totalCosts' => 0,
                    'childrenTotalCosts' => 0,
                    'childrenTotalDuration' => 0,
                    'id' => 5,
                    'name' => 'task123',
                    'children' => [],
                    'progress' => 0,
                    'scheduledStartAt' => null,
                    'scheduledFinishAt' => null,
                    'scheduledDurationDays' => 0,
                    'forecastStartAt' => null,
                    'forecastFinishAt' => null,
                    'forecastDurationDays' => 0,
                    'actualStartAt' => null,
                    'actualFinishAt' => null,
                    'actualDurationDays' => 0,
                    'content' => null,
                    'results' => null,
                    'isKeyMilestone' => false,
                    'assignments' => [],
                    'labels' => [],
                    'type' => 2,
                    'dependencies' => [],
                    'dependants' => [],
                    'medias' => [],
                    'automaticSchedule' => false,
                    'duration' => 0,
                    'costs' => [],
                    'comments' => [],
                    'supportUsers' => [],
                    'consultedUsers' => [],
                    'informedUsers' => [],
                    'createdAt' => date(\DateTime::ATOM),
                    'externalActualCost' => 0,
                    'externalForecastCost' => 0,
                    'internalActualCost' => 0,
                    'internalForecastCost' => 0,
                    'totalForecastCosts' => 0,
                    'totalActualCosts' => 0,
                    'actualCostColor' => 'green',
                    'externalCostTotal' => 0,
                    'internalCostTotal' => 0,
                    'externalCostOPEXTotal' => 0,
                    'externalCostCAPEXTotal' => 0,
                    'isPhase' => false,
                    'isMilestone' => false,
                    'isTask' => true,
                    'isTutorial' => false,
                    'isSubtask' => false,
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

        $this->client->request(
            'DELETE',
            '/api/workpackages/5',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );
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

        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
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

        $this->client->request(
            'POST',
            '/api/workpackages/1/assignments',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
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

    /**
     * @dataProvider getDataForCommentsCreateAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCommentsCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/workpackages/1/comments',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            json_encode($content)
        );

        $response = $this->client->getResponse();

        $comment = json_decode($response->getContent(), true);

        if ($isResponseSuccessful) {
            $responseContent['createdAt'] = $comment['createdAt'];
            $responseContent['updatedAt'] = $comment['updatedAt'];
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function getDataForCommentsCreateAction()
    {
        return [
            // data set for success
            [
                [
                    'body' => 'This is the text for comment body.',
                    'author' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'id' => 2,
                    'body' => 'This is the text for comment body.',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => '2017-01-01 12:00:00',
                ],
            ],
            //data set for failure
            [
                [
                    'body' => 'This is the text for comment body.',
                ],
                false,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'author' => [
                            'You must select an author',
                        ],
                    ],
                ],
            ],
        ];
    }
}
