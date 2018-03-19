<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Project;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class DistributionListControllerTest extends BaseController
{
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
            'PATCH',
            '/api/distribution-lists/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $distributionList = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $distributionList['updatedAt'];
        $responseContent['users'][0]['apiToken'] = $distributionList['users'][0]['apiToken'];
        $responseContent['users'][0]['updatedAt'] = $distributionList['users'][0]['updatedAt'];
        $email = md5(strtolower(trim($distributionList['users'][0]['email'])));
        $responseContent['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $responseContent['createdByAvatar'] = $distributionList['createdByAvatar'];

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
                    'name' => 'distribution-list-1',
                ],
                true,
                Response::HTTP_ACCEPTED,
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
                            'projectUsers' => [],
                            'signUpDetails' => [],
                            'locale' => 'en',
                            'avatar' => null,
                        ],
                    ],
                    'meetings' => [],
                    'createdAt' => '2017-01-01 07:00:00',
                    'updatedAt' => null,
                    'createdByAvatar' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNameIsUniqueOnEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNameIsUniqueOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            '/api/distribution-lists/1',
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
    public function getDataForNameIsUniqueOnEditAction()
    {
        return [
            [
                [
                    'name' => 'distribution-list-2',
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['That name is taken'],
                    ],
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
            'PATCH',
            '/api/distribution-lists/1',
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
                    'name' => '',
                    'sequence' => 1,

                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['The name field should not be blank'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForSequenceIsNumberOnEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testSequenceIsNumberOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            '/api/distribution-lists/1',
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
    public function getDataForSequenceIsNumberOnEditAction()
    {
        return [
            [
                [
                    'sequence' => 'distribution-list',
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'sequence' => ['The sequence field should contain numbers greater than or equal to 0'],
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
        $user = $this->getUserByUsername('superadmin');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneByName('project1')
        ;
        $distributionList = (new DistributionList())
            ->setName('distribution-list-3')
            ->setSequence(1)
            ->setProject($project)
            ->setCreatedBy($user)
        ;
        $this->em->persist($distributionList);
        $this->em->flush();

        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/distribution-lists/%d', $distributionList->getId()),
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

        $distributionList = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $distributionList['updatedAt'];
        $responseContent['users'][0]['apiToken'] = $distributionList['users'][0]['apiToken'];
        $responseContent['users'][0]['updatedAt'] = $distributionList['users'][0]['updatedAt'];
        $email = md5(strtolower(trim($distributionList['users'][0]['email'])));
        $responseContent['users'][0]['gravatar'] = sprintf('https://www.gravatar.com/avatar/%s?d=identicon', $email);
        $responseContent['createdByAvatar'] = $distributionList['createdByAvatar'];

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
                '/api/distribution-lists/1',
                true,
                Response::HTTP_OK,
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
                            'projectUsers' => [],
                            'signUpDetails' => [],
                            'locale' => 'en',
                            'avatar' => null,
                        ],
                    ],
                    'meetings' => [],
                    'createdAt' => '2017-01-01 07:00:00',
                    'updatedAt' => null,
                    'createdByAvatar' => [],
                ],
            ],
        ];
    }
}
