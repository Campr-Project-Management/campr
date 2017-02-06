<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
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
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/project/create',
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
                ],
                true,
                Response::HTTP_CREATED,
                [
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
                    'id' => 3,
                    'name' => 'project3',
                    'number' => 'project-number-3',
                    'projectUsers' => [],
                    'notes' => [],
                    'distributionLists' => [],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'createdAt' => '',
                    'updatedAt' => null,
                    'logo' => null,
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
        $project = (new Project())
            ->setName('project3')
            ->setNumber('project-number-3')
        ;
        $this->em->persist($project);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/project/create',
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
                ],
                true,
                Response::HTTP_OK,
                [
                    'That number is taken',
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
            '/api/project/create',
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
    public function getDataForFieldsNotBlankOnCreateAction()
    {
        return [
            [
                [],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
                    'The number field should not be blank',
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
            'POST',
            '/api/project/1/edit',
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
        $responseContent['updatedAt'] = $project['updatedAt'];
        $responseContent['distributionLists'][0]['updatedAt'] = $project['distributionLists'][0]['updatedAt'];
        $responseContent['distributionLists'][1]['updatedAt'] = $project['distributionLists'][1]['updatedAt'];
        $responseContent['distributionLists'][0]['users'][0]['apiToken'] = $project['distributionLists'][0]['users'][0]['apiToken'];
        $responseContent['distributionLists'][1]['users'][0]['apiToken'] = $project['distributionLists'][1]['users'][0]['apiToken'];
        $responseContent['distributionLists'][0]['users'][0]['updatedAt'] = $project['distributionLists'][0]['users'][0]['updatedAt'];
        $responseContent['distributionLists'][1]['users'][0]['updatedAt'] = $project['distributionLists'][1]['users'][0]['updatedAt'];

        for ($i = 1; $i <= 3; ++$i) {
            $projectUser = $this->em->getRepository(ProjectUser::class)->find($i);
            $responseContent['projectUsers'][$i - 1]['updatedAt'] = $projectUser->getUpdatedAt()->format('Y-m-d H:i:s');
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
                Response::HTTP_OK,
                [
                    'company' => null,
                    'companyName' => null,
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
                    'id' => 1,
                    'name' => 'project1',
                    'number' => 'project-number-1',
                    'projectUsers' => [
                        [
                            'user' => 3,
                            'userFullName' => 'FirstName3 LastName3',
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
                            'showInRaci' => null,
                            'showInOrg' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                        ],
                        [
                            'user' => 4,
                            'userFullName' => 'FirstName4 LastName4',
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
                            'showInRaci' => null,
                            'showInOrg' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                        ],
                        [
                            'user' => 5,
                            'userFullName' => 'FirstName5 LastName5',
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
                            'showInRaci' => null,
                            'showInOrg' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                        ],
                    ],
                    'notes' => [
                        [
                            'status' => 1,
                            'statusName' => 'status1',
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
                        ],
                        [
                            'status' => 1,
                            'statusName' => 'status1',
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
                        ],
                    ],
                    'distributionLists' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 1,
                            'name' => 'distribution-list-1',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
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
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 2,
                            'name' => 'distribution-list-2',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
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
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                        ],
                    ],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
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
            'POST',
            '/api/project/1/edit',
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
    public function getDataForNumberIsUniqueOnEditAction()
    {
        return [
            [
                [
                    'number' => 'project-number-2',
                ],
                true,
                Response::HTTP_OK,
                [
                    'That number is taken',
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
            'POST',
            '/api/project/1/edit',
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
    public function getDataForFieldsNotBlankOnEditAction()
    {
        return [
            [
                [
                    'name' => '',
                    'number' => '',
                ],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
                    'The number field should not be blank',
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
        $project = (new Project())
            ->setName('project3')
            ->setNumber('project-number-3')
        ;
        $this->em->persist($project);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/project/%d/delete', $project->getId()),
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
                '/api/project/2',
                true,
                Response::HTTP_OK,
                [
                    'company' => null,
                    'companyName' => null,
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
                    'id' => 2,
                    'name' => 'project2',
                    'number' => 'project-number-2',
                    'projectUsers' => [
                        [
                            'user' => 6,
                            'userFullName' => 'FirstName6 LastName6',
                            'project' => 2,
                            'projectName' => 'project2',
                            'projectCategory' => 2,
                            'projectCategoryName' => 'project-category2',
                            'projectRole' => 4,
                            'projectRoleName' => 'team-participant',
                            'projectDepartment' => 2,
                            'projectDepartmentName' => 'project-department2',
                            'projectTeam' => 2,
                            'projectTeamName' => 'project-team2',
                            'id' => 4,
                            'showInResources' => true,
                            'showInRaci' => null,
                            'showInOrg' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                        ],
                    ],
                    'notes' => [],
                    'distributionLists' => [],
                    'statusUpdatedAt' => null,
                    'approvedAt' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                    'logo' => null,
                ],
            ],
        ];
    }
}
