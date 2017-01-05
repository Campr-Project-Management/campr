<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MeetingControllerTest extends BaseController
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
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/meeting/1/list', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
                    [
                        'id' => 1,
                        'name' => 'meeting1',
                        'project' => 1,
                        'project_name' => 'project1',
                        'location' => 'location1',
                        'date' => '2017-01-01',
                        'start' => '07:00:00',
                        'end' => '12:00:00',
                        'objectives' => 'objectives',
                        'meeting_participants' => [
                            [
                                'id' => 1,
                                'user' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'remark' => null,
                                'present' => false,
                            ],
                            [
                                'id' => 2,
                                'user' => [
                                    'id' => 5,
                                    'name' => 'FirstName5 LastName5',
                                ],
                                'remark' => null,
                                'present' => false,
                            ],
                        ],
                        'meeting_agendas' => [
                            [
                                'id' => 1,
                                'topic' => 'topic1',
                                'responsibility' => [
                                    'id' => 3,
                                    'name' => 'FirstName3 LastName3',
                                ],
                                'start' => '07:30:00',
                                'end' => '08:00:00',
                            ],
                            [
                                'id' => 2,
                                'topic' => 'topic2',
                                'responsibility' => [
                                    'id' => 3,
                                    'name' => 'FirstName3 LastName3',
                                ],
                                'start' => '11:30:00',
                                'end' => '12:00:00',
                            ],
                        ],
                        'medias' => [
                            [
                                'id' => 1,
                                'path' => 'file1',
                            ],
                        ],
                        'decisions' => [
                            [
                                'id' => 1,
                                'title' => 'decision1',
                                'description' => 'description1',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
                            ],
                            [
                                'id' => 2,
                                'title' => 'decision2',
                                'description' => 'description2',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
                            ],
                        ],
                        'todos' => [
                            [
                                'id' => 1,
                                'title' => 'todo1',
                                'description' => 'description for todo1',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
                            ],
                            [
                                'id' => 2,
                                'title' => 'todo2',
                                'description' => 'description for todo2',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
                            ],
                        ],
                        'notes' => [
                            [
                                'id' => 1,
                                'title' => 'note1',
                                'description' => 'description1',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
                            ],
                            [
                                'id' => 2,
                                'title' => 'note2',
                                'description' => 'description2',
                                'show_in_report' => false,
                                'responsibility' => [
                                    'id' => 4,
                                    'name' => 'FirstName4 LastName4',
                                ],
                                'date' => '2017-01-01 00:00:00',
                                'due_date' => '2017-05-01 00:00:00',
                                'status' => 'status1',
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
                '/api/meeting/1',
                true,
                Response::HTTP_OK,
                [
                    'id' => 1,
                    'name' => 'meeting1',
                    'project' => 1,
                    'project_name' => 'project1',
                    'location' => 'location1',
                    'date' => '2017-01-01',
                    'start' => '07:00:00',
                    'end' => '12:00:00',
                    'objectives' => 'objectives',
                    'meeting_participants' => [
                        [
                            'id' => 1,
                            'user' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'remark' => null,
                            'present' => false,
                        ],
                        [
                            'id' => 2,
                            'user' => [
                                'id' => 5,
                                'name' => 'FirstName5 LastName5',
                            ],
                            'remark' => null,
                            'present' => false,
                        ],
                    ],
                    'meeting_agendas' => [
                        [
                            'id' => 1,
                            'topic' => 'topic1',
                            'responsibility' => [
                                'id' => 3,
                                'name' => 'FirstName3 LastName3',
                            ],
                            'start' => '07:30:00',
                            'end' => '08:00:00',
                        ],
                        [
                            'id' => 2,
                            'topic' => 'topic2',
                            'responsibility' => [
                                'id' => 3,
                                'name' => 'FirstName3 LastName3',
                            ],
                            'start' => '11:30:00',
                            'end' => '12:00:00',
                        ],
                    ],
                    'medias' => [
                        [
                            'id' => 1,
                            'path' => 'file1',
                        ],
                    ],
                    'decisions' => [
                        [
                            'id' => 1,
                            'title' => 'decision1',
                            'description' => 'description1',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
                        ],
                        [
                            'id' => 2,
                            'title' => 'decision2',
                            'description' => 'description2',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
                        ],
                    ],
                    'todos' => [
                        [
                            'id' => 1,
                            'title' => 'todo1',
                            'description' => 'description for todo1',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
                        ],
                        [
                            'id' => 2,
                            'title' => 'todo2',
                            'description' => 'description for todo2',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
                        ],
                    ],
                    'notes' => [
                        [
                            'id' => 1,
                            'title' => 'note1',
                            'description' => 'description1',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
                        ],
                        [
                            'id' => 2,
                            'title' => 'note2',
                            'description' => 'description2',
                            'show_in_report' => false,
                            'responsibility' => [
                                'id' => 4,
                                'name' => 'FirstName4 LastName4',
                            ],
                            'date' => '2017-01-01 00:00:00',
                            'due_date' => '2017-05-01 00:00:00',
                            'status' => 'status1',
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

        $this->client->request('POST', '/api/meeting/create', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'name' => 'meet',
                    'location' => 'loc1',
                    'objectives' => 'objectives',
                    'project' => 1,
                    'date' => '01/01/2017',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 2,
                    'name' => 'meet',
                    'project' => 1,
                    'project_name' => 'project1',
                    'location' => 'loc1',
                    'date' => '2017-01-01',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                    'objectives' => 'objectives',
                    'meeting_participants' => [],
                    'meeting_agendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'notes' => [],
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

        $this->client->request('PATCH', '/api/meeting/2/edit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
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
                    'name' => 'meeting-renamed',
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 2,
                    'name' => 'meeting-renamed',
                    'project' => 1,
                    'project_name' => 'project1',
                    'location' => 'loc1',
                    'date' => '2017-01-01',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                    'objectives' => 'objectives',
                    'meeting_participants' => [],
                    'meeting_agendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'notes' => [],
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

        $this->client->request('GET', '/api/meeting/2/delete', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
