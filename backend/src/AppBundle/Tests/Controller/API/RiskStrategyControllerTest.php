<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\RiskStrategy;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RiskStrategyControllerTest extends BaseController
{
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
            '/api/risk-strategies',
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

        try {
            $responseContent['id'] = $actual['id'];

            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $riskStrategy = $this
                ->em
                ->getRepository(RiskStrategy::class)
                ->find($actual['id']);
            $this->em->remove($riskStrategy);
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
                    'id' => 7,
                    'name' => 'foobar',
                    'sequence' => 1,
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
        $riskStrategy = new RiskStrategy();
        $riskStrategy->setName('foobar');
        $riskStrategy->setSequence(1);
        $this->em->persist($riskStrategy);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/risk-strategies',
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

        try {
            $this->assertEquals($isResponseSuccessful, $response->isClientError());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $this->em->remove($riskStrategy);
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
                    'name' => 'foobar',
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
            '/api/risk-strategies',
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
                [
                    'name' => '',
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
            '/api/risk-strategies',
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
                    'name' => sprintf('risk-strategy%d', time()),
                    'sequence' => 'risk-strategy',
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
        $strategy = new RiskStrategy();
        $strategy->setName('foobar');
        $strategy->setSequence(1);
        $this->em->persist($strategy);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'PATCH',
            sprintf('/api/risk-strategies/%d', $strategy->getId()),
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

        try {
            $responseContent['id'] = $actual['id'];
            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
            $this->assertEquals($responseContent, $actual);
        } finally {
            $this->em->remove($strategy);
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
                    'name' => 'foobar2',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'id' => 5,
                    'name' => 'foobar2',
                    'sequence' => 1,
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

        $this->client->request(
            'PATCH',
            '/api/risk-strategies/1',
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
                    'name' => 'risk-strategy2',
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

        $this->client->request(
            'PATCH',
            '/api/risk-strategies/1',
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

        $this->client->request(
            'PATCH',
            '/api/risk-strategies/1',
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
                    'name' => sprintf('risk-strategy%d', time()),
                    'sequence' => 'risk-strategy',
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
        $riskStrategy = new RiskStrategy();
        $riskStrategy->setName('risk-strategy3');
        $riskStrategy->setSequence(1);
        $this->em->persist($riskStrategy);
        $this->em->flush();

        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            sprintf('/api/risk-strategies/%d', $riskStrategy->getId()),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            ''
        );
        $response = $this->client->getResponse();

        try {
            $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
            $this->assertEquals($responseStatusCode, $response->getStatusCode());
        } finally {
            $this->em->remove($riskStrategy);
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
        $actual = json_decode($response->getContent(), true);
        $responseContent['id'] = $actual['id'];

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
                '/api/risk-strategies/1',
                true,
                Response::HTTP_OK,
                [
                    'id' => 1,
                    'name' => 'label.avoid',
                    'sequence' => 0,
                ],
            ],
        ];
    }
}
