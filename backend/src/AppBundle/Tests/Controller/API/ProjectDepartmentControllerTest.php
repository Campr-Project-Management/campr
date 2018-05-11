<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\ProjectDepartment;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ProjectDepartmentControllerTest extends BaseController
{
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

        $department = $this->em->find(ProjectDepartment::class, 1);
        $this->assertNotNull($department, 'Project department with ID 1 is missing');

        $department = clone $department;
        $department->setName('Foo Department');
        $this->em->persist($department);
        $this->em->flush();

        $this->client->request(
            'PATCH',
            sprintf('/api/project-departments/%d', $department->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        try {
            $actual = json_decode($response->getContent(), true);
            $this->assertArrayHasKey('updatedAt', $actual, 'Invalid response format');

            $responseContent['updatedAt'] = $actual['updatedAt'];
            $responseContent['id'] = $actual['id'];
            foreach ($actual['projectUsers'] as $index => $projectUser) {
                $responseContent['projectUsers'][$index]['updatedAt'] = $projectUser['updatedAt'];
            }

            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $this->em->remove($department);
            $this->em->flush();
        }
    }

    /**
     * @return array
     */
    public function getDataForEditAction()
    {
        return [
            [
                [
                    'name' => 'project-department1',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'projectWorkCostType' => 1,
                    'projectWorkCostTypeName' => 'project-work-cost-type1',
                    'managers' => [],
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
                            'showInRasci' => true,
                            'showInOrg' => true,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => '',
                            'userAvatar' => 'https://www.gravatar.com/avatar/96083be540ce27b34e5b5424ea9270ad?d=identicon',
                            'userCompanyName' => null,
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
                            'showInRasci' => true,
                            'showInOrg' => true,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => 'https://www.gravatar.com/avatar/07b23578addd736da1cf36ae5efb358e?d=identicon',
                            'userCompanyName' => null,
                        ],
                    ],
                    'membersCount' => 2,
                    'project' => null,
                    'projectName' => null,
                    'id' => 1,
                    'name' => 'project-department1',
                    'abbreviation' => 'pd1',
                    'sequence' => 1,
                    'rate' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
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
            '/api/project-departments/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
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
                    'abbreviation' => '',
                    'sequence' => '',
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['This value should not be blank.'],
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
        $projectDepartment = (new ProjectDepartment())
            ->setName('project-department3')
            ->setAbbreviation('pd3')
            ->setSequence(1)
        ;
        $this->em->persist($projectDepartment);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/project-departments/%d', $projectDepartment->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();

        try {
            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
        } finally {
            $this->em->remove($projectDepartment);
            $this->em->flush();
        }
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
        $actual = json_decode($response->getContent(), true);

        $responseContent['updatedAt'] = $actual['updatedAt'];
        foreach ($actual['projectUsers'] as $index => $projectUser) {
            $responseContent['projectUsers'][$index]['updatedAt'] = $projectUser['updatedAt'];
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
    }

    /**
     * @return array
     */
    public function getDataForGetAction()
    {
        return [
            [
                '/api/project-departments/2',
                true,
                Response::HTTP_OK,
                [
                    'projectWorkCostType' => 2,
                    'projectWorkCostTypeName' => 'project-work-cost-type2',
                    'managers' => [],
                    'projectUsers' => [
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
                            'showInRasci' => true,
                            'showInOrg' => true,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => '',
                            'userAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
                            'userCompanyName' => null,
                        ],
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
                            'showInRasci' => true,
                            'showInOrg' => true,
                            'company' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'userAvatar' => 'https://www.gravatar.com/avatar/232f46da009f9ab6ab311f012c1e4b26?d=identicon',
                            'userCompanyName' => null,
                        ],
                    ],
                    'membersCount' => 2,
                    'project' => null,
                    'projectName' => null,
                    'id' => 2,
                    'name' => 'project-department2',
                    'abbreviation' => 'pd2',
                    'sequence' => 2,
                    'rate' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }
}
