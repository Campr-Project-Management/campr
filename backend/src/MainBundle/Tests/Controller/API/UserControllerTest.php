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
            $responseContent['ownedMeetings'][0]['updatedAt'] = $userContent['ownedMeetings'][0]['updatedAt'];
            $responseContent['ownedMeetings'][0]['createdAt'] = $userContent['ownedMeetings'][0]['createdAt'];
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
                            'notes' => [],
                            'distributionLists' => [],
                            'createdAt' => null,
                            'updatedAt' => null,
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
