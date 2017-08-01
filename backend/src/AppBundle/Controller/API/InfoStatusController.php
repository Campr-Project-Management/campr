<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\InfoStatus;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api/info-statuses")
 */
class InfoStatusController extends ApiController
{
    const ENTITY_CLASS = InfoStatus::class;

    /**
     * @Route("", name="app_api_info_statuses", options={"expose"=true})
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
