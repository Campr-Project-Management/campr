<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\TodoStatus;
use AppBundle\Form\TodoStatus\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/todo-statuses")
 */
class TodoStatusController extends ApiController
{
    const ENTITY_CLASS = TodoStatus::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Get all todo statuses.
     *
     * @Route(name="app_api_todo_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
