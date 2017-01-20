<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/wppwct")
 */
class WorkPackageProjectWorkCostTypeController extends ApiController
{
    /**
     * All wppwct for a specific Project.
     *
     * @Route("/{id}/list", name="app_api_wppwct_list")
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $wppwct = $this
            ->getDoctrine()
            ->getRepository(WorkPackageProjectWorkCostType::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($wppwct);
    }
}
