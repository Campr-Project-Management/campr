<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class AssignmentControllerTest extends BaseController
{
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

        $this->client->request('GET', $url, [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
                '/api/assignments/1',
                true,
                Response::HTTP_OK,
                [
                    'workPackage' => 2,
                    'workPackageName' => 'work-package2',
                    'percentWorkComplete' => 0,
                    'workPackageProjectWorkCostType' => 1,
                    'workPackageProjectWorkCostTypeName' => 'work-package-project-work-cost-type1',
                    'id' => 1,
                    'timephases' => [
                        [
                            'assignment' => 1,
                            'id' => 1,
                            'type' => 1,
                            'unit' => 1,
                            'value' => 'value1',
                            'startedAt' => '2016-12-12 11:30:00',
                            'finishedAt' => '2016-12-12 13:00:00',
                        ],
                    ],
                    'milestone' => 1,
                    'confirmed' => false,
                    'startedAt' => '2016-12-12 00:00:00',
                    'finishedAt' => '2017-01-01 00:00:00',
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

        $workPackage = $this->em->getRepository(WorkPackage::class)->find(1);
        $assignment = (new Assignment())
            ->setMilestone(1)
            ->setPercentWorkComplete(0)
            ->setWorkPackage($workPackage)
        ;
        $this->em->persist($assignment);
        $this->em->flush();

        $this->client->request('PATCH', '/api/assignments/4', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], json_encode($content));
        $response = $this->client->getResponse();

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
                    'milestone' => 2,
                    'percentWorkComplete' => 100,
                ],
                true,
                Response::HTTP_ACCEPTED,
                [
                    'workPackage' => 1,
                    'workPackageName' => 'work-package1',
                    'percentWorkComplete' => 100,
                    'workPackageProjectWorkCostType' => null,
                    'workPackageProjectWorkCostTypeName' => null,
                    'id' => 4,
                    'timephases' => [],
                    'milestone' => 2,
                    'confirmed' => false,
                    'startedAt' => null,
                    'finishedAt' => null,
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

        $this->client->request('DELETE', '/api/assignments/4', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token)], '');
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
