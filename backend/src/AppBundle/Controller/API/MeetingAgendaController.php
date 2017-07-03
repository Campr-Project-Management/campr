<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\MeetingAgenda;
use AppBundle\Form\MeetingAgenda\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meeting-agendas")
 */
class MeetingAgendaController extends ApiController
{
    const ENTITY_CLASS = MeetingAgenda::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Edit a specific Meeting Agenda.
     *
     * @Route("/{id}", name="app_api_meeting_agendas_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request       $request
     * @param MeetingAgenda $meetingAgenda
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, MeetingAgenda $meetingAgenda)
    {
        $form = $this->getForm($meetingAgenda, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($meetingAgenda);

            return $this->createApiResponse($meetingAgenda, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Meeting Agenda.
     *
     * @Route("/{id}", name="app_api_meeting_agendas_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param MeetingAgenda $meetingAgenda
     *
     * @return JsonResponse
     */
    public function deleteAction(MeetingAgenda $meetingAgenda)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingAgenda);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
