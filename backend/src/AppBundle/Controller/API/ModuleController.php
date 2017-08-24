<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/modules")
 */
class ModuleController extends ApiController
{
    /**
     * Get all modules.
     *
     * @Route(name="app_api_modules_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return
            $this->createApiResponse(ProjectModuleTypeEnum::ELEMENTS)
        ;
    }
}
