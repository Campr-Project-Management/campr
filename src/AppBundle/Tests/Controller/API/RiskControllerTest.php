<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Risk;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RiskControllerTest extends BaseController
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
        for ($i = 1; $i <= 2; ++$i) {
            $pm = $this->em->getRepository(Risk::class)->find($i);
            $responseContent[$i - 1]['updatedAt'] = $pm->getUpdatedAt()->format('Y-m-d H:i:s');
        }

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
                '/api/risk/list',
                true,
                Response::HTTP_OK,
                [
                    [
                        'impact' => 1,
                        'impactName' => 'impact1',
                        'riskStrategy' => 1,
                        'riskStrategyName' => 'risk-strategy1',
                        'riskCategory' => 1,
                        'riskCategoryName' => 'risk-category1',
                        'responsibility' => 3,
                        'responsibilityFullName' => 'FirstName3 LastName3',
                        'status' => 1,
                        'statusName' => 'status1',
                        'id' => 1,
                        'title' => 'title1',
                        'description' => 'description1',
                        'cost' => 'cost1',
                        'budget' => 'budget1',
                        'delay' => 'delay1',
                        'priority' => 'priority1',
                        'measure' => 'measure1',
                        'dueDate' => '2017-03-03 00:00:00',
                        'createdAt' => '2017-01-01 12:00:00',
                        'updatedAt' => null,
                    ],
                    [
                        'impact' => 2,
                        'impactName' => 'impact2',
                        'riskStrategy' => 2,
                        'riskStrategyName' => 'risk-strategy2',
                        'riskCategory' => 2,
                        'riskCategoryName' => 'risk-category2',
                        'responsibility' => 3,
                        'responsibilityFullName' => 'FirstName3 LastName3',
                        'status' => 2,
                        'statusName' => 'status2',
                        'id' => 2,
                        'title' => 'title2',
                        'description' => 'description2',
                        'cost' => 'cost2',
                        'budget' => 'budget2',
                        'delay' => 'delay2',
                        'priority' => 'priority2',
                        'measure' => 'measure2',
                        'dueDate' => '2017-03-03 00:00:00',
                        'createdAt' => '2017-01-01 12:00:00',
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
            '/api/risk/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $risk = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $risk['createdAt'];
        $responseContent['updatedAt'] = $risk['updatedAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());

        $risk = $this
            ->em
            ->getRepository(Risk::class)
            ->findOneBy([
                'title' => $risk['title'],
            ])
        ;
        $this->em->remove($risk);
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
                    'title' => 'risk3',
                    'description' => 'description3',
                    'cost' => 'cost3',
                    'budget' => 'budget3',
                    'delay' => 'delay3',
                    'priority' => 'priority3',
                    'measure' => 'measure3',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'impact' => null,
                    'impactName' => null,
                    'riskStrategy' => null,
                    'riskStrategyName' => null,
                    'riskCategory' => null,
                    'riskCategoryName' => null,
                    'responsibility' => null,
                    'responsibilityFullName' => null,
                    'status' => null,
                    'statusName' => null,
                    'id' => 3,
                    'title' => 'risk3',
                    'description' => 'description3',
                    'cost' => 'cost3',
                    'budget' => 'budget3',
                    'delay' => 'delay3',
                    'priority' => 'priority3',
                    'measure' => 'measure3',
                    'dueDate' => null,
                    'createdAt' => '',
                    'updatedAt' => null,
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
            '/api/risk/create',
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
                        'title' => ['The title should not be blank'],
                        'description' => ['The description should not be blank'],
                        'cost' => ['The cost should not be blank'],
                        'budget' => ['The budget should not be blank'],
                        'delay' => ['The delay should not be blank'],
                        'priority' => ['The priority should not be blank'],
                        'measure' => ['The measure should not be blank'],
                    ],
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
            'PATCH',
            '/api/risk/1/edit',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $risk = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $risk['updatedAt'];

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
                    'title' => 'title1',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'impact' => 1,
                    'impactName' => 'impact1',
                    'riskStrategy' => 1,
                    'riskStrategyName' => 'risk-strategy1',
                    'riskCategory' => 1,
                    'riskCategoryName' => 'risk-category1',
                    'responsibility' => 3,
                    'responsibilityFullName' => 'FirstName3 LastName3',
                    'status' => 1,
                    'statusName' => 'status1',
                    'id' => 1,
                    'title' => 'title1',
                    'description' => 'description1',
                    'cost' => 'cost1',
                    'budget' => 'budget1',
                    'delay' => 'delay1',
                    'priority' => 'priority1',
                    'measure' => 'measure1',
                    'dueDate' => '2017-03-03 00:00:00',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => '',
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
            '/api/risk/1/edit',
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
                    'title' => '',
                    'description' => '',
                    'cost' => '',
                    'budget' => '',
                    'delay' => '',
                    'priority' => '',
                    'measure' => '',
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'title' => ['The title should not be blank'],
                        'description' => ['The description should not be blank'],
                        'cost' => ['The cost should not be blank'],
                        'budget' => ['The budget should not be blank'],
                        'delay' => ['The delay should not be blank'],
                        'priority' => ['The priority should not be blank'],
                        'measure' => ['The measure should not be blank'],
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
        $risk = (new Risk())
            ->setTitle('risk3')
            ->setDescription('description3')
            ->setCost('cost3')
            ->setBudget('budget3')
            ->setDelay('delay3')
            ->setPriority('priority3')
            ->setMeasure('measure3')
        ;
        $this->em->persist($risk);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/risk/%d/delete', $risk->getId()),
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
        $risk = json_decode($response->getContent(), true);
        $responseContent['updatedAt'] = $risk['updatedAt'];

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
                '/api/risk/2',
                true,
                Response::HTTP_OK,
                [
                    'impact' => 2,
                    'impactName' => 'impact2',
                    'riskStrategy' => 2,
                    'riskStrategyName' => 'risk-strategy2',
                    'riskCategory' => 2,
                    'riskCategoryName' => 'risk-category2',
                    'responsibility' => 3,
                    'responsibilityFullName' => 'FirstName3 LastName3',
                    'status' => 2,
                    'statusName' => 'status2',
                    'id' => 2,
                    'title' => 'title2',
                    'description' => 'description2',
                    'cost' => 'cost2',
                    'budget' => 'budget2',
                    'delay' => 'delay2',
                    'priority' => 'priority2',
                    'measure' => 'measure2',
                    'dueDate' => '2017-03-03 00:00:00',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }
}
