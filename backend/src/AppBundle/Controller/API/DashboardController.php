<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        $em = $this->getDoctrine()->getManager();
        $wpTotal = $em->getRepository(WorkPackage::class)->countTotalByUserAndFilters($this->getUser(), ['type' => WorkPackage::TYPE_TASK, 'userRasci' => true]);
        $projectTotal = $em->getRepository(Project::class)->countTotalByUserAndFilters($this->getUser());

        return $this->createApiResponse([
            'taskTotal' => $wpTotal,
            'projectTotal' => $projectTotal,
            'total' => $wpTotal + $projectTotal,
        ]);
    }
}
