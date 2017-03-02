<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackageStatus;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/workpackage-statuses")
 */
class WorkPackageStatusController extends ApiController
{
    /**
     * All WorkPackageStatuses
     *
     * @Route(name="app_api_workpackage_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getRepository(WorkPackageStatus::class)
                ->findAll()
        );
    }
}
