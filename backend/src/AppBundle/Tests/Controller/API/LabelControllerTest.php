<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Label;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class LabelControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForLabelsAction()
     *
     * @param $url
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testLabelsAction(
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
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function getDataForLabelsAction()
    {
        return [
            [
                '/api/labels',
                true,
                Response::HTTP_OK,
                [
                    [
                        'openWorkPackagesNumber' => 0,
                        'id' => 1,
                        'title' => 'label-title1',
                        'description' => null,
                        'color' => 'color1',
                    ],
                    [
                        'openWorkPackagesNumber' => 0,
                        'id' => 2,
                        'title' => 'label-title2',
                        'description' => null,
                        'color' => 'color2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDataForCreateLabelAction()
     *
     * @param array $content
     * @param       $isResponseSuccessful
     * @param       $responseStatusCode
     * @param       $responseContent
     */
    public function testCreateLabelAction(
        array $content,
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('superadmin');
        $token = $user->getApiToken();

        $this->client->request(
            'POST',
            '/api/labels',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();

        $responseArray = json_decode($response->getContent(), true);
        $responseContent['id'] = $responseArray['id'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, json_decode($response->getContent(), true));
    }

    /**
     * @return array
     */
    public function getDataForCreateLabelAction()
    {
        return [
            [
                [
                    'title' => 'label-title',
                    'color' => '123',
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'openWorkPackagesNumber' => 0,
                    'id' => null,
                    'title' => 'label-title',
                    'description' => null,
                    'color' => '123',
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

        $label = new Label();
        $label->setTitle('title');
        $label->setColor('123');
        $label->setDescription('desc');
        $this->em->persist($label);
        $this->em->flush();

        $this->client->request(
            'PATCH',
            '/api/labels/'.$label->getId(),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
            ],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $actual = $this->getClientJsonResponse();
        $responseContent['id'] = $label->getId();

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals($responseContent, $actual);
    }

    /**
     * @return array
     */
    public function getDataForEditAction()
    {
        return [
            [
                [
                    'title' => 'new-label',
                    'description' => 'descript',
                    'color' => 'color1',
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'openWorkPackagesNumber' => 0,
                    'id' => 3,
                    'title' => 'new-label',
                    'description' => 'descript',
                    'color' => 'color1',
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
        $token = $user->getApiToken();

        $this->client->request(
            'DELETE',
            '/api/labels/1',
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
                '/api/labels/1',
                true,
                Response::HTTP_OK,
                [
                    'openWorkPackagesNumber' => 0,
                    'id' => 1,
                    'title' => 'label-title1',
                    'description' => null,
                    'color' => 'color1',
                ],
            ],
        ];
    }
}
