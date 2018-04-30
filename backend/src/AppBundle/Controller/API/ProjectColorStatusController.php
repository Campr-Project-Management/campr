<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/project-color-statuses")
 */
class ProjectColorStatusController extends ApiController
{
    /**
     * Get all ProjectColorStatuses.
     *
     * @Route(name="app_api_project_color_status_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return
            $this->createApiResponse(ProjectModuleTypeEnum::PROJECT_COLOR_STATUSES)
        ;
    }
}
