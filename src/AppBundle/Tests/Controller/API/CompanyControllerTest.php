<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Company;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends BaseController
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
                '/api/company/list',
                true,
                Response::HTTP_OK,
                [
                    [
                        'id' => 1,
                        'name' => 'company1',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                    ],
                    [
                        'id' => 2,
                        'name' => 'company2',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
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

        $this->client->request(
            'POST',
            '/api/company/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $company = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $company['createdAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $company = $this
            ->em
            ->getRepository(Company::class)
            ->find($company['id'])
        ;
        $this->em->remove($company);
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
                    'name' => 'company-test',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'id' => 3,
                    'name' => 'company-test',
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
        $company = (new Company())->setName('company-test');
        $this->em->persist($company);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/company/create',
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

        $this->em->remove($company);
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
                    'name' => 'company-test',
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
     * @dataProvider getDataForNameNotBlankOnCreateAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNameNotBlankOnCreateAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/company/create',
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
    public function getDataForNameNotBlankOnCreateAction()
    {
        return [
            [
                [
                    'name' => '',
                ],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
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
            '/api/company/1/edit',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $company = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $company['updatedAt'];

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
                    'name' => 'company1',
                ],
                true,
                Response::HTTP_OK,
                [
                    'id' => 1,
                    'name' => 'company1',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => '',
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
            '/api/company/1/edit',
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
                    'name' => 'company2',
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
     * @dataProvider getDataForNameNotBlankOnEditAction
     *
     * @param array $content
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testNameNotBlankOnEditAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/company/1/edit',
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
    public function getDataForNameNotBlankOnEditAction()
    {
        return [
            [
                [
                    'name' => '',
                ],
                true,
                Response::HTTP_OK,
                [
                    'The name field should not be blank',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForDeleteAction
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     */
    public function testDeleteAction(
        $isResponseSuccessful,
        $responseStatusCode
    ) {
        $company = (new Company())->setName('company-test');
        $this->em->persist($company);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/company/%d/delete', $company->getId()),
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
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
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
                '/api/company/2',
                true,
                Response::HTTP_OK,
                [
                    'id' => 2,
                    'name' => 'company2',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }
}
