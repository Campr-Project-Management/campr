<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class NoteControllerTest extends BaseController
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
                '/api/note/1/list',
                true,
                Response::HTTP_OK,
                [
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
                '/api/note/1',
                true,
                Response::HTTP_OK,
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

        $this->client->request('POST', '/api/note/create', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'title' => 'note project 1',
                    'project' => 1,
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
                    'id' => 3,
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

        $this->client->request('PATCH', '/api/note/3/edit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'date' => '2017-01-02',
                ],
                true,
                Response::HTTP_OK,
                [
                    'status' => null,
                    'statusName' => null,
                    'meeting' => null,
                    'meetingName' => null,
                    'project' => 1,
                    'projectName' => 'project1',
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'id' => 3,
                    'title' => 'note project 1',
                    'description' => 'descript',
                    'showInStatusReport' => false,
                    'date' => '2017-01-02 00:00:00',
                    'dueDate' => null,
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

        $this->client->request('GET', '/api/note/3/delete', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
