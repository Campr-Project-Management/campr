<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class MeetingControllerTest extends BaseController
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

        $this->client->request(
            'GET',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)],
            ''
        );
        $response = $this->client->getResponse();

        $actual = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $actual['createdAt'];
        $responseContent['updatedAt'] = $actual['updatedAt'];
        foreach ($actual['meetingParticipants'] as $key => $participant) {
            $responseContent['meetingParticipants'][$key]['userAvatar'] = $participant['userAvatar'];
        }
        foreach ($actual['meetingAgendas'] as $key => $agenda) {
            $responseContent['meetingAgendas'][$key]['responsibilityAvatar'] = $agenda['responsibilityAvatar'];
        }

        foreach ($actual['decisions'] as $key => $decision) {
            $responseContent['decisions'][$key]['responsibilityAvatar'] = $decision['responsibilityAvatar'];
        }

        foreach ($actual['todos'] as $key => $todo) {
            $responseContent['todos'][$key]['responsibilityAvatar'] = $todo['responsibilityAvatar'];
        }

        foreach ($actual['medias'] as $key => $media) {
            $responseContent['medias'][$key]['name'] = $media['name'];
            $responseContent['medias'][$key]['originalName'] = $media['originalName'];
            $responseContent['medias'][$key]['path'] = $media['path'];
            $responseContent['medias'][$key]['fileName'] = $media['fileName'];
        }

        foreach ($actual['infos'] as $key => $info) {
            $responseContent['infos'][$key]['responsibilityAvatar'] = $info['responsibilityAvatar'];
            $responseContent['infos'][$key]['responsibilityAvatarUrl'] = $info['responsibilityAvatarUrl'];
            $responseContent['infos'][$key]['createdAt'] = $info['createdAt'];
            $responseContent['infos'][$key]['updatedAt'] = $info['updatedAt'];
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
                '/api/meetings/1',
                true,
                Response::HTTP_OK,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
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
                            'originalName' => '',
                            'name' => '',
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
                            'dueDate' => '2017-05-01 00:00:00',
                            'responsibilityAvatar' => '',
                        ],
                    ],
                    'infos' => [
                        [
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'responsibilityAvatar' => null,
                            'responsibilityGravatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
                            'project' => 1,
                            'projectName' => 'project1',
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'infoCategory' => 11,
                            'infoCategoryName' => 'Info Category 1',
                            'id' => 1,
                            'topic' => 'note1',
                            'description' => 'description1',
                            'expiresAt' => '2017-05-01',
                            'createdAt' => date('Y-m-d H:i:s'),
                            'updatedAt' => date('Y-m-d H:i:s'),
                            'isExpired' => true,
                        ],
                        [
                            'responsibility' => 4,
                            'responsibilityFullName' => 'FirstName4 LastName4',
                            'responsibilityAvatar' => null,
                            'responsibilityGravatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
                            'project' => 1,
                            'projectName' => 'project1',
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'infoCategory' => 12,
                            'infoCategoryName' => 'Info Category 2',
                            'id' => 2,
                            'topic' => 'note2',
                            'description' => 'description2',
                            'expiresAt' => '2017-05-01',
                            'createdAt' => date('Y-m-d H:i:s'),
                            'updatedAt' => date('Y-m-d H:i:s'),
                            'isExpired' => true,
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
        $this->markTestSkipped('meeting/distribution list needs to be re-done!');

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $project = $this->em->getRepository(Project::class)->find(1);
        $meeting = new Meeting();
        $meeting->setName('nname');
        $meeting->setLocation('loc1');
        $meeting->setProject($project);
        $meeting->setDate(new \DateTime('2017-03-09'));
        $meeting->setStart(new \DateTime('2017-03-09 16:00:00'));
        $meeting->setEnd(new \DateTime('2017-03-09 17:00:00'));
        $this->em->persist($meeting);
        $this->em->flush();

        $this->client->request('PATCH', '/api/meetings/2', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $responseArray['createdAt'];
        $responseContent['updatedAt'] = $responseArray['updatedAt'];

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
                    'createdBy' => null,
                    'createdByFullName' => null,
                    'meetingCategory' => null,
                    'meetingCategoryName' => null,
                    'id' => 2,
                    'name' => 'meeting-renamed',
                    'location' => 'loc1',
                    'date' => '2017-03-09 00:00:00',
                    'start' => '16:00',
                    'end' => '17:00',
                    'meetingObjectives' => [],
                    'meetingParticipants' => [],
                    'meetingAgendas' => [],
                    'medias' => [],
                    'decisions' => [],
                    'todos' => [],
                    'infos' => [],
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
        $this->markTestSkipped('because it depends on testCreateAction from above');
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            '/api/meetings/2',
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
}
