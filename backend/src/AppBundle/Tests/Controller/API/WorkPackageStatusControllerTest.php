<?php

namespace AppBundle\Tests\Controller\API;

use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class WorkPackageStatusControllerTest extends BaseController
{
    /**
     * @dataProvider getDataForListAction()
     *
     * @param $isResponseSuccessful
     * @param $responseStatusCode
     * @param $responseContent
     */
    public function testListAction(
        $isResponseSuccessful,
        $responseStatusCode,
        $responseContent
    ) {
        $user = $this->getUserByUsername('user4');
        $token = $user->getApiToken();

        $this->client->request('GET', '/api/workpackage-statuses', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);

        for ($i = 0; $i < sizeof($responseContent); ++$i) {
            if (!isset($content[$i])) {
                continue;
            }

            $responseContent[$i]['createdAt'] = $content[$i]['createdAt'];
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
                true,
                Response::HTTP_OK,
                [
                    [
                        'project' => null,
                        'projectName' => null,
                        'id' => 1,
                        'name' => 'label.open',
                        'sequence' => 0,
                        'visible' => true,
                        'createdAt' => null,
                    ],
                    [
                        'project' => null,
                        'projectName' => null,
                        'id' => 2,
                        'name' => 'label.pending',
                        'sequence' => 1,
                        'visible' => true,
                        'createdAt' => null,
                    ],
                    [
                        'project' => null,
                        'projectName' => null,
                        'id' => 3,
                        'name' => 'label.ongoing',
                        'sequence' => 2,
                        'visible' => true,
                        'createdAt' => null,
                    ],
                    [
                        'project' => null,
                        'projectName' => null,
                        'id' => 4,
                        'name' => 'label.completed',
                        'sequence' => 3,
                        'visible' => true,
                        'createdAt' => null,
                    ],
                    [
                        'project' => null,
                        'projectName' => null,
                        'id' => 5,
                        'name' => 'label.closed',
                        'sequence' => -1,
                        'visible' => false,
                        'createdAt' => null,
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

        $this->client->request('POST', '/api/workpackage-statuses', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);
        $responseContent['createdAt'] = $content['createdAt'];

        $this->assertEquals($isResponseSuccessful, $response->isSuccessful());
        $this->assertEquals($responseStatusCode, $response->getStatusCode());
        $this->assertEquals(json_encode($responseContent), $response->getContent());
    }

    /**
     * @return array
     */
    public function getDataForCreateAction()
    {
        return [
            [
                [
                    'name' => 'workpackage-status',
                    'sequence' => 1,
                    'visible' => true,
                    'project' => 1,
                ],
                true,
                Response::HTTP_CREATED,
                [
                    'project' => 1,
                    'projectName' => 'project1',
                    'id' => 6,
                    'name' => 'workpackage-status',
                    'sequence' => 1,
                    'visible' => true,
                    'createdAt' => null,
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

        $this->client->request('DELETE', '/api/workpackage-statuses/6', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
}
