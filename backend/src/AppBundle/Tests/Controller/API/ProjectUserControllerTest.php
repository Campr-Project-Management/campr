<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class ProjectUserControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForEditAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param array $expected
     */
    public function testEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        array $expected
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            '/api/project-users/1',
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
        $expected['updatedAt'] = $actual['updatedAt'];
        $expected['userAvatarUrl'] = $actual['userAvatarUrl'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($expected, $actual);
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
                Response::HTTP_ACCEPTED,
                [
                    'user' => 3,
                    'userFullName' => 'FirstName3 LastName3',
                    'userUsername' => 'user3',
                    'userFacebook' => null,
                    'userTwitter' => null,
                    'userLinkedIn' => null,
                    'userGplus' => null,
                    'userEmail' => 'user3@trisoft.ro',
                    'userCompanyName' => null,
                    'userPhone' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'projectCategory' => 1,
                    'projectCategoryName' => 'project-category1',
                    'projectRoles' => [2],
                    'projectDepartments' => [1],
                    'projectDepartmentNames' => ['project-department1'],
                    'projectTeam' => 1,
                    'projectTeamName' => 'project-team1',
                    'projectRoleNames' => [ProjectRole::ROLE_MANAGER],
                    'subteams' => [],
                    'subteamNames' => [],
                    'id' => 1,
                    'showInRasci' => true,
                    'company' => null,
                    'rate' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => '',
                    'userAvatarUrl' => '',
                    'userDeleted' => false,
                    'isProjectManager' => true,
                    'isProjectSponsor' => false,
                    'isRASCI' => true,
                    'departmentMembers' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForFieldsNotBlankOnEditAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
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
            '/api/project-users/1',
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
                    'user' => '',
                    'project' => '',
                ],
                true,
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
            ->findOneBy(
                [
                    'email' => 'user6@trisoft.ro',
                ]
            )
        ;
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy(
                [
                    'name' => 'project1',
                ]
            )
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
            sprintf('/api/project-users/%d', $projectUser->getId()),
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
     * @param       $url
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param array $expected
     */
    public function testGetAction(
        $url,
        $isResponseSuccessful,
        $responseStatusCode,
        array $expected
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
        $expected['updatedAt'] = $actual['updatedAt'];
        $expected['userAvatarUrl'] = $actual['userAvatarUrl'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function getDataForGetAction()
    {
        return [
            [
                '/api/project-users/2',
                true,
                Response::HTTP_OK,
                [
                    'user' => 4,
                    'userFullName' => 'FirstName4 LastName4',
                    'userUsername' => 'user4',
                    'userFacebook' => null,
                    'userTwitter' => null,
                    'userLinkedIn' => null,
                    'userGplus' => null,
                    'userEmail' => 'user4@trisoft.ro',
                    'userCompanyName' => null,
                    'userPhone' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'projectCategory' => 2,
                    'projectCategoryName' => 'project-category2',
                    'projectRoles' => [1],
                    'projectDepartments' => [2],
                    'projectDepartmentNames' => ['project-department2'],
                    'projectTeam' => 2,
                    'projectTeamName' => 'project-team2',
                    'projectRoleNames' => [ProjectRole::ROLE_SPONSOR],
                    'subteams' => [],
                    'subteamNames' => [],
                    'id' => 2,
                    'showInRasci' => true,
                    'company' => null,
                    'rate' => null,
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                    'userAvatarUrl' => '',
                    'userDeleted' => false,
                    'isProjectManager' => false,
                    'isProjectSponsor' => true,
                    'isRASCI' => true,
                    'departmentMembers' => [],
                ],
            ],
        ];
    }
}
