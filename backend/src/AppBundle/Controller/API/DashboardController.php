<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackage;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/dashboard")
 */
class DashboardController extends ApiController
{
    /**
     * Sidebar information.
     *
     * @Route(name="app_api_dashboard_sidebar", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function sidebarAction()
    {
        $wpTotal = $this
            ->get('app.repository.work_package')
            ->countTotalByUserAndFilters($this->getUser(), ['type' => WorkPackage::TYPE_TASK, 'userRasci' => true])
        ;
        $projectTotal = $this
            ->get('app.repository.project')
            ->countTotalByUserAndFilters($this->getUser())
        ;

        return $this->createApiResponse(
            [
                'taskTotal' => $wpTotal,
                'projectTotal' => $projectTotal,
                'total' => $wpTotal + $projectTotal,
            ]
        );
    }
}
