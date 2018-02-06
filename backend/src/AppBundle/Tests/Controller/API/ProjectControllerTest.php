<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Contract;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Company;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForCreateAction()
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
        $this->markTestSkipped('must be revisited.');
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $project = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $project['createdAt'];
        $responseContent['updatedAt'] = $project['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $project = $this
            ->em
            ->getRepository(Project::class)
            ->find($project['id'])
        ;
        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->findOneBy([
                'user' => $user,
                'project' => $project,
            ])
        ;
        $this->em->remove($projectUser);
        $this->em->remove($project);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'name' => 'project3',
                    'number' => 'project-number-3',
                    'configuration' => '{}',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'colorStatus' => null,
                    'colorStatusColor' => null,
                    'colorStatusName' => null,
                    'company' => null,
                    'companyName' => null,
                    'projectComplexity' => null,
                    'projectComplexityName' => null,
                    'projectCategory' => null,
                    'projectCategoryName' => null,
                    'projectScope' => null,
                    'projectScopeName' => null,
                    'status' => 1,
                    'statusName' => 'project-status1',
                    'portfolio' => null,
                    'portfolioName' => null,
                    'userFavorites' => [],
                    'progress' => 0,
                    'programme' => null,
                    'programmeName' => null,
                    'configuration' => [],
                    'id' => 3,
                    'name' => 'project3',
                    'number' => 'project-number-3',
                    'projectUsers' => [],
                    'projectTeams' => [],
                    'notes' => [],
                    'todos' => [],
                    'distributionLists' => [],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'createdAt' => '',
                    'updatedAt' => null,
                    'contracts' => [],
                    'projectObjectives' => [],
                    'projectLimitations' => [],
                    'projectDeliverables' => [],
                    'logo' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNumberIsUniqueOnEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNumberIsUniqueOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            '/api/projects/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForNumberIsUniqueOnEditAction()
    {
        return [
            [
                [
                    'number' => 'project-number-2',
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'number' => ['That number is taken'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForFieldsNotBlankOnEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testFieldsNotBlankOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            '/api/projects/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForFieldsNotBlankOnEditAction()
    {
        return [
            [
                [
                    'name' => '',
                    'number' => '',
                    'company' => null,
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['The name field should not be blank'],
                        'number' => ['The number field should not be blank'],
                        'company' => ['You must select a company'],
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
     */
    public function testDeleteAction(
        $isResponseSuccessful,
        $responseStatusCode
    ) {
        $company = $this
            ->em
            ->getRepository(Company::class)
            ->find(1)
        ;

        $project = (new Project())
            ->setName('project3')
            ->setNumber('project-number-3')
            ->setCompany($company)
        ;
        $this->em->persist($project);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/projects/%d', $project->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
    }

    /**d
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
     * @dataProvider getDataForGetAction()
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();
        $project = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $project['updatedAt'];
        $responseContent['projectUsers'][0]['updatedAt'] = $project['projectUsers'][0]['updatedAt'];
        $responseContent['projectUsers'][0]['userAvatar'] = $project['projectUsers'][0]['userAvatar'];

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
                '/api/projects/2',
                true,
                Response::HTTP_OK,
                [
                    'company' => 2,
                    'companyName' => 'company2',
                    'projectManager' => null,
                    'projectManagerName' => null,
                    'projectManagers' => [],
                    'projectSponsor' => null,
                    'projectSponsorName' => null,
                    'projectSponsors' => [],
                    'projectComplexity' => 2,
                    'projectComplexityName' => 'project-complexity2',
                    'projectCategory' => 2,
                    'projectCategoryName' => 'project-category2',
                    'projectScope' => 2,
                    'projectScopeName' => 'project-scope2',
                    'status' => 2,
                    'statusName' => 'project-status2',
                    'portfolio' => 2,
                    'portfolioName' => 'portfolio2',
                    'label' => null,
                    'labelName' => null,
                    'userFavorites' => [],
                    'progress' => 0,
                    'programme' => null,
                    'programmeName' => null,
                    'projectModules' => [],
                    'isNew' => false,
                    'colorStatus' => null,
                    'colorStatusName' => null,
                    'colorStatusColor' => null,
                    'overallStatus' => 2,
                    'scheduledStartAt' => '2017-01-01',
                    'scheduledFinishAt' => null,
                    'scheduledDuration' => 1,
                    'forecastStartAt' => '2017-01-01',
                    'forecastFinishAt' => null,
                    'forecastDuration' => 1,
                    'actualStartAt' => '2017-01-01',
                    'actualFinishAt' => null,
                    'actualDuration' => 1,
                    'id' => 2,
                    'name' => 'project2',
                    'number' => 'project-number-2',
                    'shortNote' => null,
                    'projectUsers' => [
                        [
                            'user' => 6,
                            'userFullName' => 'FirstName6 LastName6',
                            'userUsername' => 'user6',
                            'userFacebook' => null,
                            'userTwitter' => null,
                            'userLinkedIn' => null,
                            'userGplus' => null,
                            'userEmail' => 'user6@trisoft.ro',
                            'userPhone' => null,
                            'project' => 2,
                            'projectName' => 'project2',
                            'projectCategory' => 2,
                            'projectCategoryName' => 'project-category2',
                            'projectRoles' => [8],
                            'projectDepartments' => [2],
                            'projectDepartmentNames' => ['project-department2'],
                            'projectTeam' => 2,
                            'projectTeamName' => 'project-team2',
                            'projectRoleNames' => ['team-participant'],
                            'subteams' => [],
                            'subteamNames' => [],
                            'id' => 4,
                            'showInResources' => true,
                            'showInRasci' => null,
                            'showInOrg' => null,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => '',
                        ],
                    ],
                    'projectTeams' => [],
                    'notes' => [],
                    'todos' => [],
                    'distributionLists' => [],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'costs' => [],
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                    'contracts' => [],
                    'projectObjectives' => [],
                    'projectLimitations' => [],
                    'projectDeliverables' => [],
                    'configuration' => [],
                    'units' => [],
                    'resources' => [],
                    'subteams' => [],
                    'risks' => [],
                    'opportunities' => [],
                    'decisions' => [],
                    'opportunityStatuses' => [],
                    'opportunityStrategies' => [],
                    'riskStatuses' => [],
                    'riskStrategies' => [],
                    'projectDepartments' => [],
                    'statusReports' => [],
                    'statusReportConfigs' => [],
                    'projectCloseDowns' => [],
                    'projectRoles' => [],
                    'logo' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCalendarsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCalendarsAction(
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
    public function getDataForCalendarsAction()
    {
        return [
            [
                '/api/projects/1/calendars',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'parent' => null,
                        'parentName' => null,
                        'id' => 1,
                        'name' => 'calendar1',
                        'isBased' => true,
                        'isBaseline' => true,
                        'days' => [
                            [
                                'calendar' => 1,
                                'calendarName' => 'calendar1',
                                'id' => 1,
                                'type' => 1,
                                'working' => 5,
                                'workingTimes' => [
                                    [
                                        'day' => 1,
                                        'id' => 1,
                                        'fromTime' => '14:30:00',
                                        'toTime' => '18:30:00',
                                    ],
                                    [
                                        'day' => 1,
                                        'id' => 2,
                                        'fromTime' => '14:30:00',
                                        'toTime' => '18:30:00',
                                    ],
                                ],
                            ],
                            [
                                'calendar' => 1,
                                'calendarName' => 'calendar1',
                                'id' => 2,
                                'type' => 2,
                                'working' => 10,
                                'workingTimes' => [],
                            ],
                        ],
                        'workPackages' => [],
                        'workPackageProjectWorkCostTypes' => [],
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'parent' => null,
                        'parentName' => null,
                        'id' => 2,
                        'name' => 'calendar2',
                        'isBased' => true,
                        'isBaseline' => true,
                        'days' => [],
                        'workPackages' => [],
                        'workPackageProjectWorkCostTypes' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateCalendarAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateCalendarAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/projects/1/calendars', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateCalendarAction()
    {
        return [
            [
                [
                    'name' => 'Calendar 2017',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'parent' => null,
                    'parentName' => null,
                    'id' => null,
                    'name' => 'Calendar 2017',
                    'isBased' => false,
                    'isBaseline' => false,
                    'days' => [],
                    'workPackages' => [],
                    'workPackageProjectWorkCostTypes' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForContractsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testContractsAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent[0]['updatedAt'] = $responseArray[0]['updatedAt'];
        $responseContent[1]['updatedAt'] = $responseArray[1]['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForContractsAction()
    {
        return [
            [
                '/api/projects/1/contracts',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'id' => 1,
                        'name' => 'contract1',
                        'description' => 'contract-description1',
                        'projectStartEvent' => null,
                        'projectObjectives' => [],
                        'projectLimitations' => [],
                        'projectDeliverables' => [],
                        'proposedStartDate' => '2017-01-01',
                        'proposedEndDate' => '2017-05-01',
                        'forecastStartDate' => null,
                        'forecastEndDate' => null,
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'frozen' => false,
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'id' => 2,
                        'name' => 'contract2',
                        'description' => 'contract-description2',
                        'projectStartEvent' => null,
                        'projectObjectives' => [],
                        'projectLimitations' => [],
                        'projectDeliverables' => [],
                        'proposedStartDate' => '2017-05-01',
                        'proposedEndDate' => '2017-08-01',
                        'forecastStartDate' => null,
                        'forecastEndDate' => null,
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'frozen' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateContractAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateContractAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/1/contracts',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $contract = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $contract['createdAt'];
        $responseContent['updatedAt'] = $contract['updatedAt'];
        $responseContent['id'] = $contract['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $contract = $this
            ->em
            ->getRepository(Contract::class)
            ->find($contract['id'])
        ;
        $this->em->remove($contract);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateContractAction()
    {
        return [
            [
                [
                    'name' => 'contract-test',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 4,
                    'name' => 'contract-test',
                    'description' => null,
                    'projectStartEvent' => null,
                    'projectObjectives' => [],
                    'projectLimitations' => [],
                    'projectDeliverables' => [],
                    'proposedStartDate' => null,
                    'proposedEndDate' => null,
                    'forecastStartDate' => null,
                    'forecastEndDate' => null,
                    'createdAt' => null,
                    'updatedAt' => null,
                    'frozen' => false,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForDistributionListsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testDistributionListsAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );

        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseArray[0]['updatedAt'] = null;
        $responseArray[1]['updatedAt'] = null;

        $responseContent[0]['users'][0]['apiToken'] = $responseArray[0]['users'][0]['apiToken'];
        $responseContent[1]['users'][0]['apiToken'] = $responseArray[1]['users'][0]['apiToken'];
        $responseContent[0]['users'][0]['updatedAt'] = $responseArray[0]['users'][0]['updatedAt'];
        $responseContent[1]['users'][0]['updatedAt'] = $responseArray[1]['users'][0]['updatedAt'];
        $email = md5(strtolower(trim($responseArray[0]['users'][0]['email'])));
        $responseContent[0]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $email = md5(strtolower(trim($responseArray[1]['users'][0]['email'])));
        $responseContent[1]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $responseContent[0]['createdByAvatar'] = $responseArray[0]['createdByAvatar'];
        $responseContent[1]['createdByAvatar'] = $responseArray[1]['createdByAvatar'];

        $responseArray = json_encode($responseArray);

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $responseArray);
    }

    /**
     * @return array
     */
    public function getDataForDistributionListsAction()
    {
        return [
            [
                '/api/projects/1/distribution-lists',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'createdByDepartmentNames' => [],
                        'id' => 1,
                        'name' => 'distribution-list-1',
                        'sequence' => 1,
                        'users' => [
                            [
                                'roles' => ['ROLE_USER'],
                                'isAdmin' => false,
                                'gravatar' => '',
                                'id' => 7,
                                'username' => 'user10',
                                'email' => 'user10@trisoft.ro',
                                'phone' => null,
                                'firstName' => 'FirstName10',
                                'lastName' => 'LastName10',
                                'isEnabled' => true,
                                'isSuspended' => false,
                                'createdAt' => '2017-01-01 00:00:00',
                                'updatedAt' => null,
                                'activatedAt' => null,
                                'teams' => [],
                                'apiToken' => null,
                                'widgetSettings' => [],
                                'facebook' => null,
                                'twitter' => null,
                                'instagram' => null,
                                'gplus' => null,
                                'linkedIn' => null,
                                'medium' => null,
                                'ownedDistributionLists' => [],
                                'contracts' => [],
                                'ownedMeetings' => [],
                                'subteamMembers' => [],
                                'projectUsers' => [],
                                'statusReports' => [],
                                'signUpDetails' => [],
                                'locale' => 'en',
                                'avatar' => null,
                            ],
                        ],
                        'meetings' => [],
                        'createdAt' => '2017-01-01 07:00:00',
                        'updatedAt' => null,
                        'createdByAvatar' => '',
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'createdByDepartmentNames' => [],
                        'id' => 2,
                        'name' => 'distribution-list-2',
                        'sequence' => 1,
                        'users' => [
                            [
                                'roles' => ['ROLE_USER'],
                                'isAdmin' => false,
                                'gravatar' => '',
                                'id' => 7,
                                'username' => 'user10',
                                'email' => 'user10@trisoft.ro',
                                'phone' => null,
                                'firstName' => 'FirstName10',
                                'lastName' => 'LastName10',
                                'isEnabled' => true,
                                'isSuspended' => false,
                                'createdAt' => '2017-01-01 00:00:00',
                                'updatedAt' => null,
                                'activatedAt' => null,
                                'teams' => [],
                                'apiToken' => null,
                                'widgetSettings' => [],
                                'facebook' => null,
                                'twitter' => null,
                                'instagram' => null,
                                'gplus' => null,
                                'linkedIn' => null,
                                'medium' => null,
                                'ownedDistributionLists' => [],
                                'contracts' => [],
                                'ownedMeetings' => [],
                                'subteamMembers' => [],
                                'projectUsers' => [],
                                'statusReports' => [],
                                'signUpDetails' => [],
                                'locale' => 'en',
                                'avatar' => null,
                            ],
                        ],
                        'meetings' => [],
                        'createdAt' => '2017-01-01 07:00:00',
                        'updatedAt' => null,
                        'createdByAvatar' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateDistributionListAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateDistributionListAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/1/distribution-lists',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $distributionList = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $distributionList['createdAt'];
        $responseContent['updatedAt'] = $distributionList['updatedAt'];
        $responseContent['id'] = $distributionList['id'];
        $responseContent['createdByAvatar'] = $distributionList['createdByAvatar'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $distributionList = $this
            ->em
            ->getRepository(DistributionList::class)
            ->find($distributionList['id'])
        ;
        $this->em->remove($distributionList);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateDistributionListAction()
    {
        return [
            [
                [
                    'name' => 'distribution-list-3',
                    'sequence' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'createdByDepartmentNames' => [],
                    'id' => null,
                    'name' => 'distribution-list-3',
                    'sequence' => 1,
                    'users' => [],
                    'meetings' => [],
                    'createdAt' => null,
                    'updatedAt' => null,
                    'createdByAvatar' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForLabelsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testLabelsAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForLabelsAction()
    {
        return [
            [
                '/api/projects/1/labels',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'openWorkPackagesNumber' => 0,
                        'id' => 1,
                        'title' => 'label-title1',
                        'description' => null,
                        'color' => 'color1',
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'openWorkPackagesNumber' => 0,
                        'id' => 2,
                        'title' => 'label-title2',
                        'description' => null,
                        'color' => 'color2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateLabelAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateLabelAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/2/labels',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateLabelAction()
    {
        return [
            [
                [
                    'title' => 'label-title',
                    'color' => '123',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 2,
                    'projectName' => 'project2',
                    'openWorkPackagesNumber' => 0,
                    'id' => null,
                    'title' => 'label-title',
                    'description' => null,
                    'color' => '123',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForMeetingsAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testMeetingsAction(
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/projects/1/meetings', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['items'][0]['createdAt'] = $responseArray['items'][0]['createdAt'];
        $responseContent['items'][0]['updatedAt'] = $responseArray['items'][0]['updatedAt'];
        foreach ($responseArray['items'][0]['meetingParticipants'] as $key => $participant) {
            $responseContent['items'][0]['meetingParticipants'][$key]['userAvatar'] = $participant['userAvatar'];
        }
        foreach ($responseArray['items'][0]['meetingAgendas'] as $key => $agenda) {
            $responseContent['items'][0]['meetingAgendas'][$key]['responsibilityAvatar'] = $agenda['responsibilityAvatar'];
        }
        foreach ($responseArray['items'][0]['decisions'] as $key => $decision) {
            $responseContent['items'][0]['decisions'][$key]['responsibilityAvatar'] = $decision['responsibilityAvatar'];
        }
        foreach ($responseArray['items'][0]['todos'] as $key => $todo) {
            $responseContent['items'][0]['todos'][$key]['responsibilityAvatar'] = $todo['responsibilityAvatar'];
        }
        foreach ($responseArray['items'][0]['notes'] as $key => $note) {
            $responseContent['items'][0]['notes'][$key]['responsibilityAvatar'] = $note['responsibilityAvatar'];
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForMeetingsAction()
    {
        return [
            [
                true,
                Response::HTTP_OK,
                [
                    'items' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => null,
                            'createdByFullName' => null,
                            'meetingCategory' => null,
                            'meetingCategoryName' => null,
                            'id' => 1,
                            'name' => 'meeting1',
                            'location' => 'location1',
                            'date' => '2017-01-01 00:00:00',
                            'start' => '07:00',
                            'end' => '12:00',
                            'meetingObjectives' => [],
                            'meetingParticipants' => [
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'user' => 4,
                                    'userFullName' => 'FirstName4 LastName4',
                                    'userDepartmentNames' => ['project-department2'],
                                    'id' => 1,
                                    'remark' => null,
                                    'isPresent' => false,
                                    'isExcused' => false,
                                    'inDistributionList' => false,
                                ],
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'user' => 5,
                                    'userFullName' => 'FirstName5 LastName5',
                                    'userDepartmentNames' => ['project-department1'],
                                    'id' => 2,
                                    'remark' => null,
                                    'isPresent' => false,
                                    'isExcused' => false,
                                    'inDistributionList' => false,
                                ],
                            ],
                            'meetingAgendas' => [
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'responsibility' => 3,
                                    'responsibilityFullName' => 'FirstName3 LastName3',
                                    'id' => 1,
                                    'topic' => 'topic1',
                                    'start' => '07:30',
                                    'end' => '08:00',
                                    'responsibilityAvatar' => '',
                                ],
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'responsibility' => 3,
                                    'responsibilityFullName' => 'FirstName3 LastName3',
                                    'id' => 2,
                                    'topic' => 'topic2',
                                    'start' => '11:30',
                                    'end' => '12:00',
                                    'responsibilityAvatar' => '',
                                ],
                            ],
                            'medias' => [
                                [
                                    'fileSystem' => 1,
                                    'fileSystemName' => 'fs',
                                    'user' => 1,
                                    'userFullName' => 'FirstName1 LastName1',
                                    'fileName' => 'file1',
                                    'id' => 1,
                                    'path' => 'file1',
                                    'mimeType' => 'inode/x-empty',
                                    'fileSize' => 0,
                                    'createdAt' => '2017-01-01 00:00:00',
                                ],
                            ],
                            'decisions' => [
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'decisionCategory' => null,
                                    'decisionCategoryName' => null,
                                    'id' => 1,
                                    'title' => 'decision1',
                                    'description' => 'description1',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                                [
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'decisionCategory' => null,
                                    'decisionCategoryName' => null,
                                    'id' => 2,
                                    'title' => 'decision2',
                                    'description' => 'description2',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                            ],
                            'todos' => [
                                [
                                    'status' => null,
                                    'statusName' => null,
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'todoCategory' => null,
                                    'todoCategoryName' => null,
                                    'id' => 1,
                                    'title' => 'todo1',
                                    'description' => 'description for todo1',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                                [
                                    'status' => null,
                                    'statusName' => null,
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'todoCategory' => null,
                                    'todoCategoryName' => null,
                                    'id' => 2,
                                    'title' => 'todo2',
                                    'description' => 'description for todo2',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                            ],
                            'notes' => [
                                [
                                    'status' => null,
                                    'statusName' => null,
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'id' => 1,
                                    'title' => 'note1',
                                    'description' => 'description1',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                                [
                                    'status' => null,
                                    'statusName' => null,
                                    'meeting' => 1,
                                    'meetingName' => 'meeting1',
                                    'project' => 1,
                                    'projectName' => 'project1',
                                    'responsibility' => 4,
                                    'responsibilityFullName' => 'FirstName4 LastName4',
                                    'id' => 2,
                                    'title' => 'note2',
                                    'description' => 'description2',
                                    'showInStatusReport' => false,
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => '',
                                ],
                            ],
                            'distributionLists' => [],
                            'createdAt' => null,
                            'updatedAt' => null,
                        ],
                    ],
                    'totalItems' => 1,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateMeetingAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateMeetingAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/projects/1/meetings', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];
        $responseContent['createdAt'] = $responseArray['createdAt'];
        $responseContent['updatedAt'] = $responseArray['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateMeetingAction()
    {
        return [
            [
                [
                    'name' => 'meet',
                    'location' => 'loc1',
                    'date' => '07-01-2017',
                    'start' => '16:00',
                    'end' => '17:00',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'meetingCategory' => null,
                    'meetingCategoryName' => null,
                    'id' => null,
                    'name' => 'meet',
                    'location' => 'loc1',
                    'date' => '2017-01-07 00:00:00',
                    'start' => '16:00',
                    'end' => '17:00',
                    'meetingObjectives' => [],
                    'meetingParticipants' => [],
                    'meetingAgendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'notes' => [],
                    'distributionLists' => [],
                    'createdAt' => null,
                    'updatedAt' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNotesAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNotesAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', $url, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent[0]['responsibilityAvatar'] = $responseArray[0]['responsibilityAvatar'];
        $responseContent[1]['responsibilityAvatar'] = $responseArray[1]['responsibilityAvatar'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForNotesAction()
    {
        return [
            [
                '/api/projects/1/notes',
                true,
                Response::HTTP_OK,
                [
                    [

                        'status' => null,
                        'statusName' => null,
                        'meeting' => 1,
                        'meetingName' => 'meeting1',
                        'project' => 1,
                        'projectName' => 'project1',
                        'responsibility' => 4,
                        'responsibilityFullName' => 'FirstName4 LastName4',
                        'id' => 1,
                        'title' => 'note1',
                        'description' => 'description1',
                        'showInStatusReport' => false,
                        'date' => '2017-01-01 00:00:00',
                        'dueDate' => '2017-05-01 00:00:00',
                        'responsibilityAvatar' => '',
                    ],
                    [
                        'status' => null,
                        'statusName' => null,
                        'meeting' => 1,
                        'meetingName' => 'meeting1',
                        'project' => 1,
                        'projectName' => 'project1',
                        'responsibility' => 4,
                        'responsibilityFullName' => 'FirstName4 LastName4',
                        'id' => 2,
                        'title' => 'note2',
                        'description' => 'description2',
                        'showInStatusReport' => false,
                        'date' => '2017-01-01 00:00:00',
                        'dueDate' => '2017-05-01 00:00:00',
                        'responsibilityAvatar' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateNoteAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateNoteAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/projects/1/notes', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateNoteAction()
    {
        return [
            [
                [
                    'title' => 'note project 1',
                    'description' => 'descript',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'status' => null,
                    'statusName' => null,
                    'meeting' => null,
                    'meetingName' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'id' => null,
                    'title' => 'note project 1',
                    'description' => 'descript',
                    'showInStatusReport' => false,
                    'date' => null,
                    'dueDate' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNumberIsUniqueOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNumberIsUniqueOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $company = $this
            ->em
            ->getRepository(Company::class)
            ->find(1)
        ;

        $project = (new Project())
            ->setName('project3')
            ->setNumber('project-number-3')
            ->setCompany($company)
        ;
        $this->em->persist($project);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $this->em->remove($project);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForNumberIsUniqueOnCreateAction()
    {
        return [
            [
                [
                    'name' => 'project3',
                    'number' => 'project-number-3',
                    'configuration' => '',
                    'company' => [
                        'id' => 1,
                        'name' => 'company1',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                    ],
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'number' => ['That number is taken'],
                        'company' => ['This value is not valid.'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForFieldsNotBlankOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testFieldsNotBlankOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForFieldsNotBlankOnCreateAction()
    {
        return [
            [
                [],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['The name field should not be blank'],
                        'number' => ['The number field should not be blank'],
                        'company' => ['You must select a company'],
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

        $this->client->request(
            'PATCH',
            '/api/projects/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $project = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $project['updatedAt'];
        $responseContent['scheduledStartAt'] = $project['scheduledStartAt'];
        $responseContent['scheduledFinishAt'] = $project['scheduledFinishAt'];
        $responseContent['projectTeams'][0]['updatedAt'] = $project['projectTeams'][0]['updatedAt'];
        $responseContent['projectTeams'][1]['updatedAt'] = $project['projectTeams'][1]['updatedAt'];
        $responseContent['distributionLists'][0]['updatedAt'] = $project['distributionLists'][0]['updatedAt'];
        $responseContent['distributionLists'][1]['updatedAt'] = $project['distributionLists'][1]['updatedAt'];
        $responseContent['distributionLists'][0]['users'][0]['apiToken'] = $project['distributionLists'][0]['users'][0]['apiToken'];
        $responseContent['distributionLists'][1]['users'][0]['apiToken'] = $project['distributionLists'][1]['users'][0]['apiToken'];
        $responseContent['distributionLists'][0]['users'][0]['updatedAt'] = $project['distributionLists'][0]['users'][0]['updatedAt'];
        $responseContent['distributionLists'][1]['users'][0]['updatedAt'] = $project['distributionLists'][1]['users'][0]['updatedAt'];
        $responseContent['distributionLists'][0]['createdByAvatar'] = $project['distributionLists'][0]['createdByAvatar'];
        $responseContent['distributionLists'][1]['createdByAvatar'] = $project['distributionLists'][1]['createdByAvatar'];
        $responseContent['contracts'][0]['updatedAt'] = $project['contracts'][0]['updatedAt'];
        $responseContent['contracts'][1]['updatedAt'] = $project['contracts'][1]['updatedAt'];
        $email = md5(strtolower(trim($project['distributionLists'][0]['users'][0]['email'])));
        $responseContent['distributionLists'][0]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $email = md5(strtolower(trim($project['distributionLists'][1]['users'][0]['email'])));
        $responseContent['distributionLists'][1]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $responseContent['units'][0]['createdAt'] = $project['units'][0]['createdAt'];
        $responseContent['units'][1]['createdAt'] = $project['units'][1]['createdAt'];
        $responseContent['units'][0]['updatedAt'] = $project['units'][0]['updatedAt'];
        $responseContent['units'][1]['updatedAt'] = $project['units'][1]['updatedAt'];

        $responseContent['notes'][0]['responsibilityAvatar'] = $project['notes'][0]['responsibilityAvatar'];
        $responseContent['notes'][1]['responsibilityAvatar'] = $project['notes'][1]['responsibilityAvatar'];
        $responseContent['todos'][0]['responsibilityAvatar'] = $project['todos'][0]['responsibilityAvatar'];
        $responseContent['todos'][1]['responsibilityAvatar'] = $project['todos'][1]['responsibilityAvatar'];
        $responseContent['decisions'][0]['responsibilityAvatar'] = $project['decisions'][0]['responsibilityAvatar'];
        $responseContent['decisions'][1]['responsibilityAvatar'] = $project['decisions'][1]['responsibilityAvatar'];

        for ($i = 1; $i <= 3; ++$i) {
            $projectUser = $this->em->getRepository(ProjectUser::class)->find($i);
            $email = md5(strtolower(trim($projectUser->getUser()->getEmail())));
            $responseContent['projectUsers'][$i - 1]['updatedAt'] = $projectUser->getUpdatedAt()->format('Y-m-d H:i:s');
            $responseContent['projectUsers'][$i - 1]['userAvatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        }

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
                    'name' => 'project1',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'company' => 1,
                    'companyName' => 'company1',
                    'projectManager' => null,
                    'projectManagerName' => null,
                    'projectManagers' => [],
                    'projectSponsor' => null,
                    'projectSponsorName' => null,
                    'projectSponsors' => [],
                    'projectComplexity' => 1,
                    'projectComplexityName' => 'project-complexity1',
                    'projectCategory' => 1,
                    'projectCategoryName' => 'project-category1',
                    'projectScope' => 1,
                    'projectScopeName' => 'project-scope1',
                    'status' => 1,
                    'statusName' => 'project-status1',
                    'portfolio' => 1,
                    'portfolioName' => 'portfolio1',
                    'label' => null,
                    'labelName' => null,
                    'userFavorites' => [],
                    'progress' => 100,
                    'programme' => null,
                    'programmeName' => null,
                    'projectModules' => ['project-module1', 'project-module2', 'project-module3'],
                    'isNew' => false,
                    'colorStatus' => 5,
                    'colorStatusName' => 'color-status2',
                    'colorStatusColor' => 'green',
                    'overallStatus' => 2,
                    'scheduledStartAt' => date('Y-m-d', time()),
                    'scheduledFinishAt' => date('Y-m-d', time() + (4 * 3600 * 24)),
                    'scheduledDuration' => 4,
                    'forecastStartAt' => '2017-01-01',
                    'forecastFinishAt' => null,
                    'forecastDuration' => 1,
                    'actualStartAt' => '2017-01-01',
                    'actualFinishAt' => null,
                    'actualDuration' => 1,
                    'id' => 1,
                    'name' => 'project1',
                    'number' => 'project-number-1',
                    'shortNote' => null,
                    'projectUsers' => [
                        [
                            'user' => 3,
                            'userFullName' => 'FirstName3 LastName3',
                            'userUsername' => 'user3',
                            'userFacebook' => null,
                            'userTwitter' => null,
                            'userLinkedIn' => null,
                            'userGplus' => null,
                            'userEmail' => 'user3@trisoft.ro',
                            'userPhone' => null,
                            'project' => 1,
                            'projectName' => 'project1',
                            'projectCategory' => 1,
                            'projectCategoryName' => 'project-category1',
                            'projectRoles' => [5],
                            'projectDepartments' => [1],
                            'projectDepartmentNames' => ['project-department1'],
                            'projectTeam' => 1,
                            'projectTeamName' => 'project-team1',
                            'projectRoleNames' => ['manager'],
                            'subteams' => [],
                            'subteamNames' => [],
                            'id' => 1,
                            'showInResources' => true,
                            'showInRasci' => null,
                            'showInOrg' => null,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => '',
                        ],
                        [
                            'user' => 4,
                            'userFullName' => 'FirstName4 LastName4',
                            'userUsername' => 'user4',
                            'userFacebook' => null,
                            'userTwitter' => null,
                            'userLinkedIn' => null,
                            'userGplus' => null,
                            'userEmail' => 'user4@trisoft.ro',
                            'userPhone' => null,
                            'project' => 1,
                            'projectName' => 'project1',
                            'projectCategory' => 2,
                            'projectCategoryName' => 'project-category2',
                            'projectRoles' => [6],
                            'projectDepartments' => [2],
                            'projectDepartmentNames' => ['project-department2'],
                            'projectTeam' => 2,
                            'projectTeamName' => 'project-team2',
                            'projectRoleNames' => ['sponsor'],
                            'subteams' => [],
                            'subteamNames' => [],
                            'id' => 2,
                            'showInResources' => true,
                            'showInRasci' => null,
                            'showInOrg' => null,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => '',
                        ],
                        [
                            'user' => 5,
                            'userFullName' => 'FirstName5 LastName5',
                            'userUsername' => 'user5',
                            'userFacebook' => null,
                            'userTwitter' => null,
                            'userLinkedIn' => null,
                            'userGplus' => null,
                            'userEmail' => 'user5@trisoft.ro',
                            'userPhone' => null,
                            'project' => 1,
                            'projectName' => 'project1',
                            'projectCategory' => 1,
                            'projectCategoryName' => 'project-category1',
                            'projectRoles' => [7],
                            'projectDepartments' => [1],
                            'projectDepartmentNames' => ['project-department1'],
                            'projectTeam' => 1,
                            'projectTeamName' => 'project-team1',
                            'projectRoleNames' => ['team-member'],
                            'subteams' => [],
                            'subteamNames' => [],
                            'id' => 3,
                            'showInResources' => true,
                            'showInRasci' => null,
                            'showInOrg' => null,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => '',
                        ],
                    ],
                    'projectTeams' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'parent' => null,
                            'parentName' => null,
                            'id' => 1,
                            'name' => 'project-team1',
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'children' => [],
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'parent' => null,
                            'parentName' => null,
                            'id' => 2,
                            'name' => 'project-team2',
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'children' => [],
                        ],
                    ],
                    'notes' => [
                        [
                            'status' => null,
                            'statusName' => null,
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'id' => 1,
                            'title' => 'note1',
                            'description' => 'description1',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                        [
                            'status' => null,
                            'statusName' => null,
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'id' => 2,
                            'title' => 'note2',
                            'description' => 'description2',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                        [
                            'status' => null,
                            'statusName' => null,
                            'meeting' => null,
                            'meetingName' => null,
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => null,
                            'responsibilityFullName' => null,
                            'id' => 4,
                            'title' => 'note project 1',
                            'description' => 'descript',
                            'showInStatusReport' => false,
                            'date' => null,
                            'dueDate' => null,
                        ],
                    ],
                    'todos' => [
                        [

                            'status' => null,
                            'statusName' => null,
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'todoCategory' => null,
                            'todoCategoryName' => null,
                            'id' => 1,
                            'title' => 'todo1',
                            'description' => 'description for todo1',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                        [
                            'status' => null,
                            'statusName' => null,
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'todoCategory' => null,
                            'todoCategoryName' => null,
                            'id' => 2,
                            'title' => 'todo2',
                            'description' => 'description for todo2',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                    ],
                    'distributionLists' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'createdByDepartmentNames' => [],
                            'id' => 1,
                            'name' => 'distribution-list-1',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
                                    'isAdmin' => false,
                                    'gravatar' => '',
                                    'id' => 7,
                                    'username' => 'user10',
                                    'email' => 'user10@trisoft.ro',
                                    'phone' => null,
                                    'firstName' => 'FirstName10',
                                    'lastName' => 'LastName10',
                                    'isEnabled' => true,
                                    'isSuspended' => false,
                                    'createdAt' => '2017-01-01 00:00:00',
                                    'updatedAt' => null,
                                    'activatedAt' => null,
                                    'teams' => [],
                                    'apiToken' => null,
                                    'widgetSettings' => [],
                                    'facebook' => null,
                                    'twitter' => null,
                                    'instagram' => null,
                                    'gplus' => null,
                                    'linkedIn' => null,
                                    'medium' => null,
                                    'ownedDistributionLists' => [],
                                    'contracts' => [],
                                    'ownedMeetings' => [],
                                    'subteamMembers' => [],
                                    'projectUsers' => [],
                                    'statusReports' => [],
                                    'signUpDetails' => [],
                                    'locale' => 'en',
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                            'createdByAvatar' => [],
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'createdByDepartmentNames' => [],
                            'id' => 2,
                            'name' => 'distribution-list-2',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
                                    'isAdmin' => false,
                                    'gravatar' => '',
                                    'id' => 7,
                                    'username' => 'user10',
                                    'email' => 'user10@trisoft.ro',
                                    'phone' => null,
                                    'firstName' => 'FirstName10',
                                    'lastName' => 'LastName10',
                                    'isEnabled' => true,
                                    'isSuspended' => false,
                                    'createdAt' => '2017-01-01 00:00:00',
                                    'updatedAt' => null,
                                    'activatedAt' => null,
                                    'teams' => [],
                                    'apiToken' => null,
                                    'widgetSettings' => [],
                                    'facebook' => null,
                                    'twitter' => null,
                                    'instagram' => null,
                                    'gplus' => null,
                                    'linkedIn' => null,
                                    'medium' => null,
                                    'ownedDistributionLists' => [],
                                    'contracts' => [],
                                    'ownedMeetings' => [],
                                    'subteamMembers' => [],
                                    'projectUsers' => [],
                                    'statusReports' => [],
                                    'signUpDetails' => [],
                                    'locale' => 'en',
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                            'createdByAvatar' => [],
                        ],
                    ],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'costs' => [],
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                    'contracts' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 1,
                            'name' => 'contract1',
                            'description' => 'contract-description1',
                            'projectStartEvent' => null,
                            'projectObjectives' => [],
                            'projectLimitations' => [],
                            'projectDeliverables' => [],
                            'proposedStartDate' => '2017-01-01',
                            'proposedEndDate' => '2017-05-01',
                            'forecastStartDate' => null,
                            'forecastEndDate' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'frozen' => false,
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 2,
                            'name' => 'contract2',
                            'description' => 'contract-description2',
                            'projectStartEvent' => null,
                            'projectObjectives' => [],
                            'projectLimitations' => [],
                            'projectDeliverables' => [],
                            'proposedStartDate' => '2017-05-01',
                            'proposedEndDate' => '2017-08-01',
                            'forecastStartDate' => null,
                            'forecastEndDate' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'frozen' => false,
                        ],
                    ],
                    'projectObjectives' => [],
                    'projectLimitations' => [],
                    'projectDeliverables' => [],
                    'configuration' => [],
                    'units' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'id' => 4,
                            'name' => 'unit1',
                            'sequence' => 1,
                            'createdAt' => '',
                            'updatedAt' => '',
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'id' => 5,
                            'name' => 'unit2',
                            'sequence' => 2,
                            'createdAt' => '',
                            'updatedAt' => '',
                        ],
                    ],
                    'resources' => [],
                    'subteams' => [],
                    'risks' => [],
                    'opportunities' => [],
                    'decisions' => [
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'decisionCategory' => null,
                            'decisionCategoryName' => null,
                            'id' => 1,
                            'title' => 'decision1',
                            'description' => 'description1',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'project' => 1,
                            'projectName' => 'project1',
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'decisionCategory' => null,
                            'decisionCategoryName' => null,
                            'id' => 2,
                            'title' => 'decision2',
                            'description' => 'description2',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                    ],
                    'opportunityStatuses' => [],
                    'opportunityStrategies' => [],
                    'riskStatuses' => [],
                    'riskStrategies' => [],
                    'projectDepartments' => [],
                    'statusReports' => [],
                    'statusReportConfigs' => [],
                    'projectCloseDowns' => [],
                    'projectRoles' => [],
                    'logo' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForProjectTeamsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testProjectTeamsAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();
        for ($i = 1; $i <= 2; ++$i) {
            $pm = $this->em->getRepository(ProjectTeam::class)->find($i);
            $responseContent[$i - 1]['updatedAt'] = $pm->getUpdatedAt()->format('Y-m-d H:i:s');
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForProjectTeamsAction()
    {
        return [
            [
                '/api/projects/1/project-teams',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'parent' => null,
                        'parentName' => null,
                        'id' => 1,
                        'name' => 'project-team1',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'children' => [],
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'parent' => null,
                        'parentName' => null,
                        'id' => 2,
                        'name' => 'project-team2',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'children' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateProjectTeamAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateProjectTeamAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/1/project-teams',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $projectTeam = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $projectTeam['createdAt'];
        $responseContent['updatedAt'] = $projectTeam['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $projectTeam = $this
            ->em
            ->getRepository(ProjectTeam::class)
            ->find($projectTeam['id'])
        ;
        $this->em->remove($projectTeam);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateProjectTeamAction()
    {
        return [
            [
                [
                    'name' => 'project-team3',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'parent' => null,
                    'parentName' => null,
                    'id' => 3,
                    'name' => 'project-team3',
                    'createdAt' => '',
                    'updatedAt' => null,
                    'children' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForProjectUsersAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function ØrersAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        for ($i = 0; $i < 3; ++$i) {
            $responseContent[$i]['updatedAt'] = $responseArray[$i]['updatedAt'];
            $responseContent[$i]['userAvatar'] = $responseArray[$i]['userAvatar'];
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForProjectUsersAction()
    {
        return [
            [
                '/api/projects/1/project-users',
                true,
                Response::HTTP_OK,
                [
                    [
                        'user' => 3,
                        'userFullName' => 'FirstName3 LastName3',
                        'userFacebook' => null,
                        'userTwitter' => null,
                        'userLinkedIn' => null,
                        'userGplus' => null,
                        'userEmail' => 'user3@trisoft.ro',
                        'userPhone' => null,
                        'project' => 1,
                        'projectName' => 'project1',
                        'projectCategory' => 1,
                        'projectCategoryName' => 'project-category1',
                        'projectRole' => 1,
                        'projectRoleName' => 'manager',
                        'projectDepartment' => 1,
                        'projectDepartmentName' => 'project-department1',
                        'projectTeam' => 1,
                        'projectTeamName' => 'project-team1',
                        'id' => 1,
                        'showInResources' => true,
                        'showInRasci' => null,
                        'showInOrg' => null,
                        'company' => null,
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'userAvatar' => '',
                    ],
                    [
                        'user' => 4,
                        'userFullName' => 'FirstName4 LastName4',
                        'userFacebook' => null,
                        'userTwitter' => null,
                        'userLinkedIn' => null,
                        'userGplus' => null,
                        'userEmail' => 'user4@trisoft.ro',
                        'userPhone' => null,
                        'project' => 1,
                        'projectName' => 'project1',
                        'projectCategory' => 2,
                        'projectCategoryName' => 'project-category2',
                        'projectRole' => 2,
                        'projectRoleName' => 'sponsor',
                        'projectDepartment' => 2,
                        'projectDepartmentName' => 'project-department2',
                        'projectTeam' => 2,
                        'projectTeamName' => 'project-team2',
                        'id' => 2,
                        'showInResources' => true,
                        'showInRasci' => null,
                        'showInOrg' => null,
                        'company' => null,
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'userAvatar' => '',
                    ],
                    [
                        'user' => 5,
                        'userFullName' => 'FirstName5 LastName5',
                        'userFacebook' => null,
                        'userTwitter' => null,
                        'userLinkedIn' => null,
                        'userGplus' => null,
                        'userEmail' => 'user5@trisoft.ro',
                        'userPhone' => null,
                        'project' => 1,
                        'projectName' => 'project1',
                        'projectCategory' => 1,
                        'projectCategoryName' => 'project-category1',
                        'projectRole' => 3,
                        'projectRoleName' => 'team-member',
                        'projectDepartment' => 1,
                        'projectDepartmentName' => 'project-department1',
                        'projectTeam' => 1,
                        'projectTeamName' => 'project-team1',
                        'id' => 3,
                        'showInResources' => true,
                        'showInRasci' => null,
                        'showInOrg' => null,
                        'company' => null,
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                        'userAvatar' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateProjectUserAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateProjectUserAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/1/project-users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $projectUser = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $projectUser['createdAt'];
        $responseContent['updatedAt'] = $projectUser['updatedAt'];
        $responseContent['userAvatar'] = $projectUser['userAvatar'];
        $responseContent['id'] = $projectUser['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $projectUser = $this
            ->em
            ->getRepository(ProjectUser::class)
            ->find($projectUser['id'])
        ;
        $this->em->remove($projectUser);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateProjectUserAction()
    {
        return [
            [
                [
                    'user' => 6,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'user' => 6,
                    'userFullName' => 'FirstName6 LastName6',
                    'userUsername' => 'user6',
                    'userFacebook' => null,
                    'userTwitter' => null,
                    'userLinkedIn' => null,
                    'userGplus' => null,
                    'userEmail' => 'user6@trisoft.ro',
                    'userPhone' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'projectCategory' => null,
                    'projectCategoryName' => null,
                    'projectRoles' => [],
                    'projectDepartments' => [],
                    'projectDepartmentNames' => [],
                    'projectTeam' => null,
                    'projectTeamName' => null,
                    'projectRoleNames' => [],
                    'subteams' => [],
                    'subteamNames' => [],
                    'id' => null,
                    'showInResources' => false,
                    'showInRasci' => false,
                    'showInOrg' => false,
                    'company' => null,
                    'createdAt' => '',
                    'updatedAt' => null,
                    'userAvatar' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForFieldsNotBlankOnCreateProjectUserAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testFieldsNotBlankOnCreateProjectUserAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/projects/1/project-users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForFieldsNotBlankOnCreateProjectUserAction()
    {
        return [
            [
                [],
                false,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'user' => ['The name field should not be blank. Choose one user'],
                    ],

                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForTodosAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testTodosAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', $url, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent[0]['responsibilityAvatar'] = $responseArray[0]['responsibilityAvatar'];
        $responseContent[1]['responsibilityAvatar'] = $responseArray[1]['responsibilityAvatar'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForTodosAction()
    {
        return [
            [
                '/api/projects/1/todos',
                true,
                Response::HTTP_OK,
                [
                    [

                        'status' => null,
                        'statusName' => null,
                        'meeting' => 1,
                        'meetingName' => 'meeting1',
                        'project' => 1,
                        'projectName' => 'project1',
                        'responsibility' => 4,
                        'responsibilityFullName' => 'FirstName4 LastName4',
                        'todoCategory' => null,
                        'todoCategoryName' => null,
                        'id' => 1,
                        'title' => 'todo1',
                        'description' => 'description for todo1',
                        'showInStatusReport' => false,
                        'date' => '2017-01-01 00:00:00',
                        'dueDate' => '2017-05-01 00:00:00',
                        'responsibilityAvatar' => '',
                    ],
                    [
                        'status' => null,
                        'statusName' => null,
                        'meeting' => 1,
                        'meetingName' => 'meeting1',
                        'project' => 1,
                        'projectName' => 'project1',
                        'responsibility' => 4,
                        'responsibilityFullName' => 'FirstName4 LastName4',
                        'todoCategory' => null,
                        'todoCategoryName' => null,
                        'id' => 2,
                        'title' => 'todo2',
                        'description' => 'description for todo2',
                        'showInStatusReport' => false,
                        'date' => '2017-01-01 00:00:00',
                        'dueDate' => '2017-05-01 00:00:00',
                        'responsibilityAvatar' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateTodoAction()
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateTodoAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/projects/1/todos', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateTodoAction()
    {
        return [
            [
                [
                    'title' => 'do this',
                    'description' => 'descript',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'status' => null,
                    'statusName' => null,
                    'meeting' => null,
                    'meetingName' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'todoCategory' => null,
                    'todoCategoryName' => null,
                    'id' => null,
                    'title' => 'do this',
                    'description' => 'descript',
                    'showInStatusReport' => false,
                    'date' => null,
                    'dueDate' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForWppwctsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testWppwctsAction(
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForWppwctsAction()
    {
        return [
            [
                '/api/projects/1/wppwcts',
                true,
                Response::HTTP_OK,
                [
                    [
                        'workPackage' => 1,
                        'workPackageName' => 'work-package1',
                        'projectWorkCostType' => 1,
                        'projectWorkCostTypeName' => 'project-work-cost-type1',
                        'calendar' => null,
                        'calendarName' => null,
                        'id' => 1,
                        'name' => 'work-package-project-work-cost-type1',
                        'base' => null,
                        'change' => null,
                        'actual' => null,
                        'remaining' => null,
                        'forecast' => null,
                        'isGeneric' => false,
                        'isInactive' => false,
                        'isEnterprise' => false,
                        'isCostResource' => false,
                        'isBudget' => false,
                        'createdAt' => '2017-01-20',
                    ],
                    [
                        'workPackage' => 2,
                        'workPackageName' => 'work-package2',
                        'projectWorkCostType' => 2,
                        'projectWorkCostTypeName' => 'project-work-cost-type2',
                        'calendar' => null,
                        'calendarName' => null,
                        'id' => 2,
                        'name' => 'work-package-project-work-cost-type2',
                        'base' => null,
                        'change' => null,
                        'actual' => null,
                        'remaining' => null,
                        'forecast' => null,
                        'isGeneric' => false,
                        'isInactive' => false,
                        'isEnterprise' => false,
                        'isCostResource' => false,
                        'isBudget' => false,
                        'createdAt' => '2017-01-20',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForExportCalendarsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testExportCalendarsAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContentType,
        $responseContent
    ) {
        $user = $this->getUserByUsername('user4');
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
        $content = $response->getContent();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContentType, $response->headers->get('Content-Type'));
        if ($isResponseSuccessful) {
            $this->assertTrue(strpos($content, $responseContent) !== false);
        }
    }

    /**
     * @return array
     */
    public function getDataForExportCalendarsAction()
    {
        return [
            [
                '/api/projects/1/export-calendars',
                false,
                Response::HTTP_BAD_REQUEST,
                'application/json',
                null,
            ],
            [
                '/api/projects/1/export-calendars?type=csv',
                true,
                Response::HTTP_OK,
                'text/csv; charset=UTF-8',
                'Start Date","Start Time","End Date","End Time","All Day Event",Description,Location,Private',
            ],
            [
                '/api/projects/1/export-calendars?type=ics',
                true,
                Response::HTTP_OK,
                'text/calendar; charset=UTF-8',
                'BEGIN:VCALENDAR',
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateTaskAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testCreateTaskAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('POST', '/api/projects/1/tasks', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        // Remove the 2 lines bellow when WP listener is fixed
        $task = json_decode($response->getContent(), true);
        $responseContent['id'] = $task['id'];
        $responseContent['puid'] = $task['puid'];
        $responseContent['createdAt'] = $task['createdAt'];

        $this->assertEquals($isResponseSuccessful, $response->getStatusCode() === 201);
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateTaskAction()
    {
        return [
            [
                [
                    'name' => 'task',
                    'progress' => 0,
                    'type' => 2,
                    'duration' => 0,
                ],
                true,
                Response::HTTP_CREATED,
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
                    'colorStatus' => null,
                    'colorStatusName' => null,
                    'colorStatusColor' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'calendar' => null,
                    'calendarName' => null,
                    'label' => null,
                    'labelName' => null,
                    'labelColor' => null,
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
                    'name' => 'task',
                    'children' => [],
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
                    'externalActualCost' => null,
                    'externalForecastCost' => null,
                    'internalActualCost' => null,
                    'internalForecastCost' => null,
                ],
            ],
        ];
    }
}
