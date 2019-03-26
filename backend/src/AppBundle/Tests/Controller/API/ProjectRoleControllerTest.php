<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\ProjectRole;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class ProjectRoleControllerTest extends BaseController
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
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();

        /** @var ProjectRole $pr */
        foreach ($this->em->getRepository(ProjectRole::class)->findAll() as $pr) {
            foreach ($responseContent as $index => $r) {
                if ($r['id'] === $pr->getId()) {
                    $responseContent[$index]['updatedAt'] = $pr->getUpdatedAt() ? $pr->getUpdatedAt()->format('Y-m-d H:i:s') : null;
                }
            }
        }

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function getDataForListAction()
    {
        return [
            [
                '/api/project-roles',
                true,
                Response::HTTP_OK,
                [
                    [
                        'id' => 1,
                        'name' => ProjectRole::ROLE_SPONSOR,
                        'sequence' => 1,
                        'isLead' => false,
                        'children' => [],
                        'createdAt' => '2017-01-01 00:00:00',
                        'updatedAt' => null,
                    ],
                    [
                        'id' => 2,
                        'name' => ProjectRole::ROLE_MANAGER,
                        'sequence' => 2,
                        'isLead' => false,
                        'children' => [],
                        'createdAt' => '2017-01-01 00:00:00',
                        'updatedAt' => null,
                    ],
                    [
                        'id' => 3,
                        'name' => ProjectRole::ROLE_TEAM_MEMBER,
                        'sequence' => 3,
                        'isLead' => false,
                        'children' => [],
                        'createdAt' => '2017-01-01 00:00:00',
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
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
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
            '/api/project-roles',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        try {
            $actual = json_decode($response->getContent(), true);

            $responseContent['id'] = $actual['id'];
            $responseContent['createdAt'] = $actual['createdAt'];
            $responseContent['updatedAt'] = $actual['updatedAt'];

            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $projectRole = $this
                ->em
                ->getRepository(ProjectRole::class)
                ->find($actual['id']);
            $this->em->remove($projectRole);
            $this->em->flush();
        }
    }

    /**
     * @return array
     */
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'name' => 'foobar',
                    'sequence' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'id' => 9,
                    'name' => 'foobar',
                    'sequence' => 1,
                    'isLead' => false,
                    'children' => [],
                    'createdAt' => '',
                    'updatedAt' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNameIsUniqueOnCreateAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testNameIsUniqueOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $projectRole = new ProjectRole();
        $projectRole->setName('project-role');
        $projectRole->setSequence(1);
        $this->em->persist($projectRole);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/project-roles',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );

        try {
            $response = $this->client->getResponse();
            $actual = json_decode($response->getContent(), true);

            $this->assertEquals($isResponseSuccessful, $response->isClientError());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $this->em->remove($projectRole);
            $this->em->flush();
        }
    }

    /**
     * @return array
     */
    public function getDataForNameIsUniqueOnCreateAction()
    {
        return [
            [
                [
                    'name' => 'project-role',
                    'sequence' => 1,
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
     * @dataProvider getDataForFieldsNotBlankOnCreateAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
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
            '/api/project-roles',
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
    public function getDataForFieldsNotBlankOnCreateAction()
    {
        return [
            [
                [],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['The name field should not be blank'],
                        'sequence' => ['The sequence field should not be blank'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForSequenceIsNumberOnCreateAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
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
            '/api/project-roles',
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
    public function getDataForSequenceIsNumberOnCreateAction()
    {
        return [
            [
                [
                    'name' => 'project-role',
                    'sequence' => 'project-role',
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
     * @dataProvider getDataForEditAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $role = new ProjectRole();
        $role->setName(uniqid());
        $role->setSequence(1);
        $role->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $this->em->persist($role);
        $this->em->flush();

        try {
            $this->client->request(
                'PATCH',
                sprintf('/api/project-roles/%d', $role->getId()),
                [],
                [],
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
                ],
                json_encode($content)
            );
            $response = $this->client->getResponse();
            $projectRole = json_decode($response->getContent(), true);
            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());

            $responseContent['updatedAt'] = $projectRole['updatedAt'];
            $responseContent['id'] = $role->getId();

            $this->assertEquals($responseContent, $projectRole);
        } finally {
            $this->em->remove($role);
            $this->em->flush();
        }
    }

    /**
     * @return array
     */
    public function getDataForEditAction()
    {
        return [
            [
                [
                    'name' => 'new-role-edit-name',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'id' => null,
                    'name' => 'new-role-edit-name',
                    'sequence' => 1,
                    'isLead' => false,
                    'children' => [],
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForNameIsUniqueOnEditAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testNameIsUniqueOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        /** @var ProjectRole $role */
        $role = $this->em
            ->getRepository(ProjectRole::class)
            ->findOneBy(['name' => ProjectRole::ROLE_MANAGER]);

        $this->client->request(
            'PATCH',
            sprintf('/api/project-roles/%d', $role->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
    }

    /**
     * @return array
     */
    public function getDataForNameIsUniqueOnEditAction()
    {
        return [
            [
                [
                    'name' => ProjectRole::ROLE_SPONSOR,
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
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testFieldsNotBlankOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        /** @var ProjectRole $role */
        $role = $this->em
            ->getRepository(ProjectRole::class)
            ->findOneBy(['name' => ProjectRole::ROLE_MANAGER]);

        $this->client->request(
            'PATCH',
            sprintf('/api/project-roles/%d', $role->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($isResponseSuccessful, $response->isClientError());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
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
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'name' => ['The name field should not be blank'],
                        'sequence' => ['The sequence field should not be blank'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForSequenceIsNumberOnEditAction
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testSequenceIsNumberOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        /** @var ProjectRole $role */
        $role = $this->em
            ->getRepository(ProjectRole::class)
            ->findOneBy(['name' => ProjectRole::ROLE_MANAGER]);

        $this->client->request(
            'PATCH',
            sprintf('/api/project-roles/%d', $role->getId()),
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
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function getDataForSequenceIsNumberOnEditAction()
    {
        return [
            [
                [
                    'name' => ProjectRole::ROLE_MANAGER,
                    'sequence' => 'project-role',
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
        $projectRole = new ProjectRole();
        $projectRole->setName('project-role');
        $projectRole->setSequence(1);
        $this->em->persist($projectRole);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        try {
            $this->client->request(
                'DELETE',
                sprintf('/api/project-roles/%d', $projectRole->getId()),
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
        } finally {
            $this->em->remove($projectRole);
            $this->em->flush();
        }
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

    public function testGetAction()
    {
        $role = new ProjectRole();
        $role->setName(uniqid());
        $role->setSequence(1);
        $role->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $this->em->persist($role);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        try {
            $this->client->request(
                'GET',
                sprintf('/api/project-roles/%d', $role->getId()),
                [],
                [],
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
                ],
                ''
            );
            $response = $this->client->getResponse();
            $actual = json_decode($response->getContent(), true);
            $expected = [
                'id' => $role->getId(),
                'name' => $role->getName(),
                'sequence' => $role->getSequence(),
                'isLead' => $role->getIsLead(),
                'children' => [],
                'createdAt' => $role->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $role->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];

            $this->assertTrue($response->isSuccessful());
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($expected, $actual);
        } finally {
            $this->em->remove($role);
            $this->em->flush();
        }
    }
}
