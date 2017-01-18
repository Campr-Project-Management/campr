<?php

namespace AppBundle\Tests\Controller\API;

use AppBundle\Entity\WorkPackageProjectWorkCostType;
use MainBundle\Tests\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class WorkPackageProjectWorkCostTypeControllerTest extends BaseController
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
                '/api/wppwct/1/list',
                true,
                Response::HTTP_OK,
                [
                   [
                        'workPackage' => 1,
                        'workPackageName' => 'work-package1',
                        'projectWorkCostType' => 1,
                        'projectWorkCostTypeName' => 'project-work-cost-type1',
                        'calendar' => null,
                        'calendarName' => null,
                        'id' => 1,
                        'name' => 'work-package-project-work-cost-type1',
                        'base' => null,
                        'change' => null,
                        'actual' => null,
                        'remaining' => null,
                        'forecast' => null,
                        'isGeneric' => false,
                        'isInactive' => false,
                        'isEnterprise' => false,
                        'isCostResource' => false,
                        'isBudget' => false,
                        'assignments' => [
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
                       'createdAt' => '2017-01-20',
                   ],
                   [
                       'workPackage' => 2,
                       'workPackageName' => 'work-package2',
                       'projectWorkCostType' => 2,
                       'projectWorkCostTypeName' => 'project-work-cost-type2',
                       'calendar' => null,
                       'calendarName' => null,
                       'id' => 2,
                       'name' => 'work-package-project-work-cost-type2',
                       'base' => null,
                       'change' => null,
                       'actual' => null,
                       'remaining' => null,
                       'forecast' => null,
                       'isGeneric' => false,
                       'isInactive' => false,
                       'isEnterprise' => false,
                       'isCostResource' => false,
                       'isBudget' => false,
                       'assignments' => [
                           [
                               'workPackage' => 2,
                               'workPackageName' => 'work-package2',
                               'percentWorkComplete' => 0,
                               'workPackageProjectWorkCostType' => 2,
                               'workPackageProjectWorkCostTypeName' => 'work-package-project-work-cost-type2',
                               'id' => 2,
                               'timephases' => [
                                   [
                                       'assignment' => 2,
                                       'id' => 2,
                                       'type' => 2,
                                       'unit' => 4,
                                       'value' => 'value2',
                                       'startedAt' => '2016-12-12 11:30:00',
                                       'finishedAt' => '2016-12-12 13:00:00',
                                   ],
                               ],
                               'milestone' => 4,
                               'confirmed' => false,
                               'startedAt' => '2016-12-12 00:00:00',
                               'finishedAt' => '2017-01-01 00:00:00',
                           ],
                       ],
                       'createdAt' => '2017-01-20',
                   ],
                ],
            ],
        ];
    }
}
