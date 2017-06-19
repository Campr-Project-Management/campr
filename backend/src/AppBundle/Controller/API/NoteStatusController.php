<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\NoteStatus;
use AppBundle\Form\NoteStatus\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/note-statuses")
 */
class NoteStatusController extends ApiController
{
    const ENTITY_CLASS = NoteStatus::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Get all note statuses.
     *
     * @Route(name="app_api_note_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }
}
