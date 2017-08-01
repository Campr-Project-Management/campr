<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\InfoCategory;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api/info-categories")
 */
class InfoCategoryController extends ApiController
{
    const ENTITY_CLASS = InfoCategory::class;

    /**
     * @Route("", name="app_api_info_categories", options={"expose"=true})
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
