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
                true,
                Response::HTTP_OK,
                [
                    [
                        'id' => 1,
                        'name' => 'label.todo',
                        'sequence' => 0,
                        'visible' => true,
                        'workPackages' => [],
                    ],
                    [
                        'id' => 2,
                        'name' => 'label.in_progress',
                        'sequence' => 1,
                        'visible' => true,
                        'workPackages' => [],
                    ],
                    [
                        'id' => 3,
                        'name' => 'label.code_review',
                        'sequence' => 2,
                        'visible' => true,
                        'workPackages' => [],
                    ],
                    [
                        'id' => 4,
                        'name' => 'label.finished',
                        'sequence' => 3,
                        'visible' => true,
                        'workPackages' => [],
                    ],
                    [
                        'id' => 5,
                        'name' => 'label.closed',
                        'sequence' => -1,
                        'visible' => false,
                        'workPackages' => [],
                    ],
                ],
            ],
        ];
    }
}
