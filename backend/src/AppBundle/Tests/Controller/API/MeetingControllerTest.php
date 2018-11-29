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
            $responseContent['meetingParticipants'][$key]['userAvatarUrl'] = $participant['userAvatarUrl'];
        }
        foreach ($actual['meetingAgendas'] as $key => $agenda) {
            $responseContent['meetingAgendas'][$key]['responsibilityAvatarUrl'] = $agenda['responsibilityAvatarUrl'];
        }

        foreach ($actual['medias'] as $key => $media) {
            $responseContent['medias'][$key]['name'] = $media['name'];
            $responseContent['medias'][$key]['originalName'] = $media['originalName'];
            $responseContent['medias'][$key]['path'] = $media['path'];
            $responseContent['medias'][$key]['fileName'] = $media['fileName'];
            $responseContent['medias'][$key]['userAvatarUrl'] = $media['userAvatarUrl'];
        }

        $responseContent['openDecisions'] = $actual['openDecisions'];
        $responseContent['openInfos'] = $actual['openInfos'];
        $responseContent['openTodos'] = $actual['openTodos'];

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
                    'meetingReports' => [],
                    'meetingAgendas' => [
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'responsibility' => 3,
                            'responsibilityFullName' => 'FirstName3 LastName3',
                            'id' => 1,
                            'topic' => 'topic1',
                            'start' => '07:30',
                            'duration' => 0,
                            'responsibilityAvatarUrl' => '',
                        ],
                        [
                            'meeting' => 1,
                            'meetingName' => 'meeting1',
                            'responsibility' => 3,
                            'responsibilityFullName' => 'FirstName3 LastName3',
                            'id' => 2,
                            'topic' => 'topic2',
                            'start' => '11:30',
                            'duration' => 0,
                            'responsibilityAvatarUrl' => '',
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
                            'expiresAt' => null,
                            'name' => '',
                            'updatedAt' => null,
                            'userAvatarUrl' => '',
                        ],
                    ],
                    'distributionLists' => [],
                    'createdAt' => '',
                    'updatedAt' => '',
                    'openDecisions' => [],
                    'openTodos' => [],
                    'openInfos' => [],
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
                    'meetingReports' => [],
                    'meetingAgendas' => [],
                    'medias' => [],
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
