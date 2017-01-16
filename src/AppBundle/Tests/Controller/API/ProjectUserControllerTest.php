<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectUser;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class ProjectUserControllerTest extends BaseController
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
    public function getDataForListAction()
    {
        return [
            [
                '/api/project-user/list',
                true,
                Response::HTTP_OK,
                [
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
            ],
        ];
    }

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
            '/api/project-user/create',
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
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'user' => 6,
                    'project' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'user' => 6,
                    'userFullName' => 'FirstName6 LastName6',
                    'project' => 1,
                    'projectName' => 'project1',
                    'projectCategory' => null,
                    'projectCategoryName' => null,
                    'projectRole' => null,
                    'projectRoleName' => null,
                    'projectDepartment' => null,
                    'projectDepartmentName' => null,
                    'projectTeam' => null,
                    'projectTeamName' => null,
                    'id' => 5,
                    'showInResources' => false,
                    'showInRaci' => false,
                    'showInOrg' => false,
                    'createdAt' => '',
                    'updatedAt' => null,
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
            '/api/project-user/create',
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
                    'The name field should not be blank. Choose one user',
                    'The project field should not be blank. Choose one project',
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
            '/api/project-user/1/edit',
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
        $responseContent['updatedAt'] = $projectUser['updatedAt'];

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
                    'user' => 3,
                ],
                true,
                Response::HTTP_OK,
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
                    'updatedAt' => '',
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
            '/api/project-user/1/edit',
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
                    'user' => '',
                    'project' => '',
                ],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank. Choose one user',
                    'The project field should not be blank. Choose one project',
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
        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => 'user6@trisoft.ro',
            ])
        ;
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'name' => 'project1',
            ])
        ;

        $projectUser = (new ProjectUser())
            ->setUser($user)
            ->setProject($project)
        ;
        $this->em->persist($projectUser);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/project-user/%d/delete', $projectUser->getId()),
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
                '/api/project-user/2',
                true,
                Response::HTTP_OK,
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
            ],
        ];
    }
}
