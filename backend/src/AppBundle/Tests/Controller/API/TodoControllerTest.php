<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\Todo;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class TodoControllerTest extends BaseController
{
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
        $responseArray = json_decode($response->getContent(), true);
        $responseContent['responsibilityAvatar'] = $responseArray['responsibilityAvatar'];

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
                '/api/todos/1',
                true,
                Response::HTTP_OK,
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
                    'dueDate' => '2017-05-01 00:00:00',
                    'responsibilityAvatar' => '',
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

        $project = $this->em->getRepository(Project::class)->find(1);
        $todo = new Todo();
        $todo->setProject($project);
        $todo->setTitle('do this');
        $todo->setDescription('descript');
        $this->em->persist($todo);
        $this->em->flush();

        $this->client->request('PATCH', '/api/todos/3', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'dueDate' => '11-01-2017',
                ],
                true,
                Response::HTTP_ACCEPTED,
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
                    'id' => 3,
                    'title' => 'do this',
                    'description' => 'descript',
                    'showInStatusReport' => false,
                    'dueDate' => '2017-01-11 00:00:00',
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

        $this->client->request('DELETE', '/api/todos/3', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
