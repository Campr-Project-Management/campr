<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\DecisionCategory;
use AppBundle\Form\DecisionCategory\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/decision-categories")
 */
class DecisionCategoryController extends ApiController
{
    const ENTITY_CLASS = DecisionCategory::class;
    const FORM_CLASS = CreateType::class;
    /**
     * Get the decision categories.
     *
     * @Route(name="app_api_decision_categories_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
