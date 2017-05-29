<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Risk;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RiskControllerTest extends BaseController
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
            '/api/risks/1',
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
        $responseContent['responsibilityAvatar'] = $risk['responsibilityAvatar'];

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
                    'project' => null,
                    'projectName' => null,
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
                    'impact' => 10,
                    'probability' => 10,
                    'cost' => 'cost1',
                    'currency' => '$',
                    'budget' => 'budget1',
                    'delay' => 'delay1',
                    'delayUnit' => 'days',
                    'priority' => 'priority1',
                    'measures' => [],
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
            '/api/risks/1',
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
                ],
                true,
                Response::HTTP_BAD_REQUEST,
                [
                    'messages' => [
                        'title' => ['The title field should not be blank'],
                        'description' => ['The description field should not be blank'],
                        'cost' => ['The cost field should not be blank'],
                        'budget' => ['The budget field should not be blank'],
                        'delay' => ['The delay field should not be blank'],
                        'priority' => ['The priority field should not be blank'],
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
            ->setImpact(10)
            ->setProbability(10)
            ->setDescription('description3')
            ->setCost('cost3')
            ->setCurrency('$')
            ->setBudget('budget3')
            ->setDelay('delay3')
            ->setDelayUnit('choices.days')
            ->setPriority('priority3')
        ;
        $this->em->persist($risk);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/risks/%d', $risk->getId()),
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
        $responseContent['responsibilityAvatar'] = $risk['responsibilityAvatar'];

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
                '/api/risks/2',
                true,
                Response::HTTP_OK,
                [
                    'project' => null,
                    'projectName' => null,
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
                    'impact' => 20,
                    'probability' => 20,
                    'cost' => 'cost2',
                    'currency' => '$',
                    'budget' => 'budget2',
                    'delay' => 'delay2',
                    'delayUnit' => 'days',
                    'priority' => 'priority2',
                    'measures' => [],
                    'dueDate' => '2017-03-03 00:00:00',
                    'createdAt' => '2017-01-01 12:00:00',
                    'updatedAt' => null,
                ],
            ],
        ];
    }
}
