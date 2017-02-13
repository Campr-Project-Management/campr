<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class MeetingControllerTest extends BaseController
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
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/meeting/1/list', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);
        $responseContent[0]['createdAt'] = $content[0]['createdAt'];
        $responseContent[0]['updatedAt'] = $content[0]['updatedAt'];

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
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => null,
                        'createdByFullName' => null,
                        'id' => 1,
                        'name' => 'meeting1',
                        'location' => 'location1',
                        'date' => '2017-01-01 00:00:00',
                        'start' => '07:00:00',
                        'end' => '12:00:00',
                        'objectives' => 'objectives',
                        'meetingParticipants' => [
                            [
                                'meeting' => 1,
                                'meetingName' => 'meeting1',
                                'user' => 4,
                                'userFullName' => 'FirstName4 LastName4',
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
                                'start' => '07:30:00',
                                'end' => '08:00:00',
                                'duration' => '00:30:00',
                            ],
                            [
                                'meeting' => 1,
                                'meetingName' => 'meeting1',
                                'responsibility' => 3,
                                'responsibilityFullName' => 'FirstName3 LastName3',
                                'id' => 2,
                                'topic' => 'topic2',
                                'start' => '11:30:00',
                                'end' => '12:00:00',
                                'duration' => '00:30:00',
                            ],
                        ],
                        'medias' => [
                            [
                                'fileSystem' => 1,
                                'fileSystemName' => 'fs',
                                'user' => 1,
                                'userFullName' => 'FirstName1 LastName1',
                                'id' => 1,
                                'path' => 'file1',
                                'mimeType' => 'inode/x-empty',
                                'fileSize' => 0,
                                'createdAt' => '2017-01-01 00:00:00',
                            ],
                        ],
                        'decisions' => [
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
                               'title' => 'decision1',
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
                                'title' => 'decision2',
                                'description' => 'description2',
                                'showInStatusReport' => false,
                                'date' => '2017-01-01 00:00:00',
                                'dueDate' => '2017-05-01 00:00:00',
                            ],
                        ],
                        'todos' => [
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
                                'title' => 'todo1',
                                'description' => 'description for todo1',
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
                                'title' => 'todo2',
                                'description' => 'description for todo2',
                                'showInStatusReport' => false,
                                'date' => '2017-01-01 00:00:00',
                                'dueDate' => '2017-05-01 00:00:00',
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
                        'distributionLists' => [],
                        'createdAt' => '',
                        'updatedAt' => '',
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

        $content = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $content['createdAt'];
        $responseContent['updatedAt'] = $content['updatedAt'];

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
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => null,
                    'createdByFullName' => null,
                    'id' => 1,
                    'name' => 'meeting1',
                    'location' => 'location1',
                    'date' => '2017-01-01 00:00:00',
                    'start' => '07:00:00',
                    'end' => '12:00:00',
                    'objectives' => 'objectives',
                    'meetingParticipants' => [
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'user' => 4,
                            'userFullName' => 'FirstName4 LastName4',
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
                            'start' => '07:30:00',
                            'end' => '08:00:00',
                            'duration' => '00:30:00',
                        ],
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'responsibility' => 3,
                            'responsibilityFullName' => 'FirstName3 LastName3',
                            'id' => 2,
                            'topic' => 'topic2',
                            'start' => '11:30:00',
                            'end' => '12:00:00',
                            'duration' => '00:30:00',
                        ],
                    ],
                    'medias' => [
                        [
                            'fileSystem' => 1,
                            'fileSystemName' => 'fs',
                            'user' => 1,
                            'userFullName' => 'FirstName1 LastName1',
                            'id' => 1,
                            'path' => 'file1',
                            'mimeType' => 'inode/x-empty',
                            'fileSize' => 0,
                            'createdAt' => '2017-01-01 00:00:00',
                        ],
                    ],
                    'decisions' => [
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
                            'title' => 'decision1',
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
                            'title' => 'decision2',
                            'description' => 'description2',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
                        ],
                    ],
                    'todos' => [
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
                            'title' => 'todo1',
                            'description' => 'description for todo1',
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
                            'title' => 'todo2',
                            'description' => 'description for todo2',
                            'showInStatusReport' => false,
                            'date' => '2017-01-01 00:00:00',
                            'dueDate' => '2017-05-01 00:00:00',
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
                    'distributionLists' => [],
                    'createdAt' => '',
                    'updatedAt' => '',
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

        $content = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $content['createdAt'];
        $responseContent['updatedAt'] = $content['updatedAt'];

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
                    'date' => '07-01-2017',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 2,
                    'name' => 'meet',
                    'location' => 'loc1',
                    'date' => '2017-01-07 00:00:00',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                    'objectives' => 'objectives',
                    'meetingParticipants' => [],
                    'meetingAgendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'notes' => [],
                    'distributionLists' => [],
                    'createdAt' => '',
                    'updatedAt' => '',
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

        $content = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $content['createdAt'];
        $responseContent['updatedAt'] = $content['updatedAt'];

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
                    'date' => '09-03-2017',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 2,
                    'name' => 'meeting-renamed',
                    'location' => 'loc1',
                    'date' => '2017-03-09 00:00:00',
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                    'objectives' => 'objectives',
                    'meetingParticipants' => [],
                    'meetingAgendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'notes' => [],
                    'distributionLists' => [],
                    'createdAt' => '',
                    'updatedAt' => '',
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

        $this->client->request('DELETE', '/api/meeting/2/delete', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
