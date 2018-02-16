<?php

namespace MainBundle\Tests\Controller\API;

use AppBundle\Entity\User;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
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

        if (isset($responseContent['apiToken'])) {
            $responseContent['apiToken'] = $token;
        }

        $userContent = json_decode($response->getContent(), true);

        if (!array_key_exists('message', $userContent)) {
            $responseContent['ownedDistributionLists'][0]['updatedAt'] = $userContent['ownedDistributionLists'][0]['updatedAt'];
            $responseContent['ownedDistributionLists'][1]['updatedAt'] = $userContent['ownedDistributionLists'][1]['updatedAt'];
            $responseContent['ownedDistributionLists'][0]['createdByAvatar'] = $userContent['ownedDistributionLists'][0]['createdByAvatar'];
            $responseContent['ownedDistributionLists'][1]['createdByAvatar'] = $userContent['ownedDistributionLists'][1]['createdByAvatar'];
            $responseContent['ownedDistributionLists'][0]['users'][0]['apiToken'] = $userContent['ownedDistributionLists'][0]['users'][0]['apiToken'];
            $responseContent['ownedDistributionLists'][0]['users'][0]['updatedAt'] = $userContent['ownedDistributionLists'][0]['users'][0]['updatedAt'];
            $responseContent['ownedDistributionLists'][1]['users'][0]['apiToken'] = $userContent['ownedDistributionLists'][1]['users'][0]['apiToken'];
            $responseContent['ownedDistributionLists'][1]['users'][0]['updatedAt'] = $userContent['ownedDistributionLists'][1]['users'][0]['updatedAt'];
            $responseContent['contracts'][0]['updatedAt'] = $userContent['contracts'][0]['updatedAt'];
            $responseContent['contracts'][1]['updatedAt'] = $userContent['contracts'][1]['updatedAt'];
            $email = md5(strtolower(trim($userContent['email'])));
            $responseContent['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
            $email = md5(strtolower(trim($responseContent['ownedDistributionLists'][0]['users'][0]['email'])));
            $responseContent['ownedDistributionLists'][0]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
            $email = md5(strtolower(trim($responseContent['ownedDistributionLists'][1]['users'][0]['email'])));
            $responseContent['ownedDistributionLists'][1]['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);

            foreach ($responseContent['ownedMeetings'] as $omk => $ownedMeeting) {
                $responseContent['ownedMeetings'][$omk]['updatedAt'] = $userContent['ownedMeetings'][$omk]['updatedAt'];
                $responseContent['ownedMeetings'][$omk]['createdAt'] = $userContent['ownedMeetings'][$omk]['createdAt'];
                $responseContent['ownedMeetings'][$omk]['date'] = $userContent['ownedMeetings'][$omk]['date'];

                foreach ($userContent['ownedMeetings'][$omk]['infos'] as $key => $info) {
                    $responseContent['ownedMeetings'][$omk]['infos'][$key]['createdAt'] = $info['createdAt'];
                    $responseContent['ownedMeetings'][$omk]['infos'][$key]['updatedAt'] = $info['updatedAt'];
                }
            }
        }

        if (isset($responseContent['updatedAt'])) {
            $responseContent['updatedAt'] = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        }
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
                '/api/users/999',
                false,
                Response::HTTP_NOT_FOUND,
                [
                    'message' => 'Resource not found!',
                ],
            ],
            [
                '/api/users/1',
                true,
                Response::HTTP_OK,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'isAdmin' => true,
                    'gravatar' => null,
                    'id' => 1,
                    'username' => 'superadmin',
                    'email' => 'superadmin@trisoft.ro',
                    'phone' => null,
                    'firstName' => 'FirstName1',
                    'lastName' => 'LastName1',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '2017-01-01 00:00:00',
                    'updatedAt' => '',
                    'activatedAt' => null,
                    'teams' => [],
                    'apiToken' => '',
                    'widgetSettings' => [],
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'gplus' => null,
                    'linkedIn' => null,
                    'medium' => null,
                    'ownedDistributionLists' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'createdByDepartmentNames' => [],
                            'id' => 1,
                            'name' => 'distribution-list-1',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
                                    'isAdmin' => false,
                                    'gravatar' => '',
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
                                    'contracts' => [],
                                    'ownedMeetings' => [],
                                    'subteamMembers' => [],
                                    'projectUsers' => [],
                                    'statusReports' => [],
                                    'signUpDetails' => [],
                                    'locale' => 'en',
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                            'createdByAvatar' => '',
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'createdByDepartmentNames' => [],
                            'id' => 2,
                            'name' => 'distribution-list-2',
                            'sequence' => 1,
                            'users' => [
                                [
                                    'roles' => ['ROLE_USER'],
                                    'isAdmin' => false,
                                    'gravatar' => '',
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
                                    'contracts' => [],
                                    'ownedMeetings' => [],
                                    'subteamMembers' => [],
                                    'projectUsers' => [],
                                    'statusReports' => [],
                                    'signUpDetails' => [],
                                    'locale' => 'en',
                                    'avatar' => null,
                                ],
                            ],
                            'meetings' => [],
                            'createdAt' => '2017-01-01 07:00:00',
                            'updatedAt' => '2017-01-30 07:11:12',
                            'createdByAvatar' => '',
                        ],
                    ],
                    'contracts' => [
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 1,
                            'name' => 'contract1',
                            'description' => 'contract-description1',
                            'projectStartEvent' => null,
                            'projectObjectives' => [],
                            'projectLimitations' => [],
                            'projectDeliverables' => [],
                            'proposedStartDate' => '2017-01-01',
                            'proposedEndDate' => '2017-05-01',
                            'forecastStartDate' => null,
                            'forecastEndDate' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'frozen' => false,
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'id' => 2,
                            'name' => 'contract2',
                            'description' => 'contract-description2',
                            'projectStartEvent' => null,
                            'projectObjectives' => [],
                            'projectLimitations' => [],
                            'projectDeliverables' => [],
                            'proposedStartDate' => '2017-05-01',
                            'proposedEndDate' => '2017-08-01',
                            'forecastStartDate' => null,
                            'forecastEndDate' => null,
                            'createdAt' => '2017-01-01 12:00:00',
                            'updatedAt' => null,
                            'frozen' => false,
                        ],
                    ],
                    'ownedMeetings' => [
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
                                    'userDepartmentNames' => [],
                                    'id' => 1,
                                    'remark' => 'remark1',
                                    'isPresent' => false,
                                    'isExcused' => false,
                                    'inDistributionList' => false,
                                    'userAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
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
                                    'userAvatar' => 'https://www.gravatar.com/avatar/07b23578addd736da1cf36ae5efb358e?d=identicon',
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
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/96083be540ce27b34e5b5424ea9270ad?d=identicon',
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
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/96083be540ce27b34e5b5424ea9270ad?d=identicon',
                                ],
                            ],
                            'medias' => [
                                [
                                    'fileSystem' => 1,
                                    'fileSystemName' => 'fs1-edited',
                                    'user' => null,
                                    'userFullName' => null,
                                    'fileName' => 'file2.txt',
                                    'id' => 1,
                                    'path' => 'file2.txt',
                                    'mimeType' => 'text/plain',
                                    'fileSize' => 13,
                                    'createdAt' => '2017-01-01 00:00:00',
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
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
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
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
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
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
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
                                    'date' => '2017-01-01 00:00:00',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'responsibilityAvatar' => 'https://www.gravatar.com/avatar/8654c6441d88fdebf45f198f27b3decc?d=identicon',
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
                                    'infoStatus' => 6,
                                    'infoStatusName' => 'Info Status 1',
                                    'infoStatusColor' => '#000000',
                                    'infoCategory' => 11,
                                    'infoCategoryName' => 'Info Category 1',
                                    'id' => 1,
                                    'topic' => 'note1',
                                    'description' => 'description1',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'createdAt' => '2018-02-16 07:01:01',
                                    'updatedAt' => '2018-02-16 07:01:01',
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
                                    'infoStatus' => 7,
                                    'infoStatusName' => 'Info Status 2',
                                    'infoStatusColor' => '#000000',
                                    'infoCategory' => 12,
                                    'infoCategoryName' => 'Info Category 2',
                                    'id' => 2,
                                    'topic' => 'note2',
                                    'description' => 'description2',
                                    'dueDate' => '2017-05-01 00:00:00',
                                    'createdAt' => '2018-02-16 07:01:01',
                                    'updatedAt' => '2018-02-16 07:01:01',
                                ],
                            ],
                            'distributionLists' => [],
                            'createdAt' => '2018-02-16 07:01:01',
                            'updatedAt' => '2018-02-16 07:01:01',
                        ],
                        [
                            'project' => 1,
                            'projectName' => 'project1',
                            'createdBy' => 1,
                            'createdByFullName' => 'FirstName1 LastName1',
                            'meetingCategory' => null,
                            'meetingCategoryName' => null,
                            'id' => 3,
                            'name' => 'meet',
                            'location' => 'loc1',
                            'date' => '2017-01-07 00:00:00',
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
                            'createdAt' => '2018-02-16 10:07:52',
                            'updatedAt' => '2018-02-16 10:07:52',
                        ],
                    ],
                    'subteamMembers' => [],
                    'projectUsers' => [],
                    'statusReports' => [],
                    'signUpDetails' => [],
                    'locale' => 'en',
                    'avatar' => null,
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
        $this->user = $this->createUser('testuser', 'testuser@trisoft.ro', 'Password1', ['ROLE_SUPER_ADMIN']);
        $token = $this->user->getApiToken();

        $this->client->request('PATCH', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();
        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());

        $user = $this->getUserByUsername('testuser');
        if (isset($responseContent['id'])) {
            $responseContent['id'] = $user->getId();
        }
        if (isset($responseContent['apiToken'])) {
            $responseContent['apiToken'] = $token;
        }
        if (isset($responseContent['createdAt'])) {
            $responseContent['createdAt'] = $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null;
        }
        if (isset($responseContent['updatedAt'])) {
            $responseContent['updatedAt'] = $user->getUpdatedAt() ? $user->getUpdatedAt()->format('Y-m-d H:i:s') : null;
        }
        if (isset($responseContent['gravatar'])) {
            $email = md5(strtolower(trim($user->getEmail())));
            $responseContent['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        }
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
                    'plainPassword' => [
                        'first' => 'pass1',
                        'second' => 'pass11',
                    ],
                ],
                false,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'plainPassword' => [
                            'first' => ['The password fields do not match'],
                        ],
                    ],
                ],
            ],
            [
                [
                    'firstName' => 'User3',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'roles' => ['ROLE_SUPER_ADMIN'],
                    'isAdmin' => true,
                    'gravatar' => '',
                    'id' => '',
                    'username' => 'testuser',
                    'email' => 'testuser@trisoft.ro',
                    'phone' => null,
                    'firstName' => 'User3',
                    'lastName' => 'LastName',
                    'isEnabled' => true,
                    'isSuspended' => false,
                    'createdAt' => '',
                    'updatedAt' => '',
                    'activatedAt' => null,
                    'teams' => [],
                    'apiToken' => '',
                    'widgetSettings' => [],
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'gplus' => null,
                    'linkedIn' => null,
                    'medium' => null,
                    'ownedDistributionLists' => [],
                    'contracts' => [],
                    'ownedMeetings' => [],
                    'subteamMembers' => [],
                    'projectUsers' => [],
                    'statusReports' => [],
                    'signUpDetails' => [],
                    'locale' => 'en',
                    'avatar' => null,
                ],
            ],
        ];
    }
}
