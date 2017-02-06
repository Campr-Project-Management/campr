<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Project;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class DistributionListControllerTest extends BaseController
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

        $this->client->request(
            'GET',
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token), ],
            ''
        );

        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseArray[0]['updatedAt'] = null;
        $responseArray[1]['updatedAt'] = null;

        $responseContent[0]['users'][0]['apiToken'] = $responseArray[0]['users'][0]['apiToken'];
        $responseContent[1]['users'][0]['apiToken'] = $responseArray[1]['users'][0]['apiToken'];
        $responseContent[0]['users'][0]['updatedAt'] = $responseArray[0]['users'][0]['updatedAt'];
        $responseContent[1]['users'][0]['updatedAt'] = $responseArray[1]['users'][0]['updatedAt'];

        $responseArray = json_encode($responseArray);

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $responseArray);
    }

    /**
     * @return array
     */
    public function getDataForListAction()
    {
        return [
            [
                '/api/distribution-list/1/list',
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'id' => 1,
                        'name' => 'distribution-list-1',
                        'sequence' => 1,
                        'users' => [
                            [
                                'roles' => ['ROLE_USER'],
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
                                'avatar' => null,
                            ],
                        ],
                        'meetings' => [],
                        'createdAt' => '2017-01-01 07:00:00',
                        'updatedAt' => null,
                    ],
                    [
                        'project' => 1,
                        'projectName' => 'project1',
                        'createdBy' => 1,
                        'createdByFullName' => 'FirstName1 LastName1',
                        'id' => 2,
                        'name' => 'distribution-list-2',
                        'sequence' => 1,
                        'users' => [
                            [
                                'roles' => ['ROLE_USER'],
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
                                'avatar' => null,
                            ],
                        ],
                        'meetings' => [],
                        'createdAt' => '2017-01-01 07:00:00',
                        'updatedAt' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateAction()
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

        $this->client->request(
            'POST',
            '/api/distribution-list/create',
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
        $responseContent['createdAt'] = $distributionList['createdAt'];
        $responseContent['updatedAt'] = $distributionList['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $distributionList = $this
            ->em
            ->getRepository(DistributionList::class)
            ->find($distributionList['id'])
        ;
        $this->em->remove($distributionList);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'name' => 'distribution-list-3',
                    'sequence' => 1,
                    'project' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 3,
                    'name' => 'distribution-list-3',
                    'sequence' => 1,
                    'users' => [],
                    'meetings' => [],
                    'createdAt' => null,
                    'updatedAt' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNameIsUniqueOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNameIsUniqueOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $project = $this
            ->em
            ->getRepository(Project::class)
            ->findOneBy([
                'name' => 'project1',
            ])
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
            'POST',
            '/api/distribution-list/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $this->em->remove($distributionList);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getDataForNameIsUniqueOnCreateAction()
    {
        return [
            [
                [
                    'name' => 'distribution-list-3',
                    'sequence' => 1,
                    'project' => 1,
                ],
                true,
                Response::HTTP_OK,
                [
                    'That name is taken',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForFieldsNotBlankOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testFieldsNotBlankOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/distribution-list/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForFieldsNotBlankOnCreateAction()
    {
        return [
            [
                [],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
                    'The sequence field should not be blank',
                    'You must select one project',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForSequenceIsNumberOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testSequenceIsNumberOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/distribution-list/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForSequenceIsNumberOnCreateAction()
    {
        return [
            [
                [
                    'name' => 'distribution-list-3',
                    'sequence' => 'project-category',
                    'project' => 1,
                ],
                true,
                Response::HTTP_OK,
                [
                    'The sequence field should contain numbers greater than or equal to 0',
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

        $this->client->request(
            'POST',
            '/api/distribution-list/1/edit',
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
                Response::HTTP_OK,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 1,
                    'name' => 'distribution-list-1',
                    'sequence' => 1,
                    'users' => [
                        [
                            'roles' => ['ROLE_USER'],
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
                            'avatar' => null,
                        ],
                    ],
                    'meetings' => [],
                    'createdAt' => '2017-01-01 07:00:00',
                    'updatedAt' => null,
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
            'POST',
            '/api/distribution-list/1/edit',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
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
                Response::HTTP_OK,
                [
                    'That name is taken',
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
            'POST',
            '/api/distribution-list/1/edit',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
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
                    'sequence' => '',

                ],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
                    'The sequence field should not be blank',
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
            'POST',
            '/api/distribution-list/1/edit',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
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
                Response::HTTP_OK,
                [
                    'The sequence field should contain numbers greater than or equal to 0',
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
            ->findOneBy([
                'name' => 'project1',
            ])
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
            sprintf('/api/distribution-list/%d/delete', $distributionList->getId()),
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
                '/api/distribution-list/1',
                true,
                Response::HTTP_OK,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'createdBy' => 1,
                    'createdByFullName' => 'FirstName1 LastName1',
                    'id' => 1,
                    'name' => 'distribution-list-1',
                    'sequence' => 1,
                    'users' => [
                        [
                            'roles' => ['ROLE_USER'],
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
                            'avatar' => null,
                        ],
                    ],
                    'meetings' => [],
                    'createdAt' => '2017-01-01 07:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }
}
