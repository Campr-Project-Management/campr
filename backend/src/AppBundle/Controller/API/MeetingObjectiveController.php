<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\MeetingObjective;
use AppBundle\Form\MeetingObjective\BaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meeting-objectives")
 */
class MeetingObjectiveController extends ApiController
{
    const ENTITY_CLASS = MeetingObjective::class;
    const FORM_CLASS = BaseType::class;

    /**
     * Edit a specific Meeting Objective.
     *
     * @Route("/{id}", name="app_api_meeting_objectives_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request          $request
     * @param MeetingObjective $meetingObjective
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, MeetingObjective $meetingObjective)
    {
        $form = $this->getForm($meetingObjective, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($meetingObjective);

            return $this->createApiResponse($meetingObjective, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Meeting Objective.
     *
     * @Route("/{id}", name="app_api_meeting_objectives_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param MeetingObjective $meetingObjective
     *
     * @return JsonResponse
     */
    public function deleteAction(MeetingObjective $meetingObjective)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingObjective);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
