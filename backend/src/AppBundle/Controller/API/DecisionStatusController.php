<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\DecisionStatus;
use AppBundle\Form\DecisionStatus\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/decision-statuses")
 */
class DecisionStatusController extends ApiController
{
    const ENTITY_CLASS = DecisionStatus::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Get all decision statuses.
     *
     * @Route(name="app_api_decision_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
