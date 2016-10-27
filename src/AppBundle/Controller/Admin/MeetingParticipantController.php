<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MeetingParticipant;
use AppBundle\Form\MeetingParticipant\CreateType;
use AppBundle\Form\MeetingParticipant\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MeetingParticipant controller.
 *
 * @Route("/admin/meeting-participant")
 */
class MeetingParticipantController extends Controller
{
    /**
     * Lists all MeetingParticipant entities.
     *
     * @Route("/list", name="app_admin_meeting_participant_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetingParticipants = $em
            ->getRepository(MeetingParticipant::class)
            ->findAll();

        return $this->render(
            'AppBundle:Admin/MeetingParticipant:list.html.twig',
            [
                'meeting_participants' => $meetingParticipants,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_meeting_participant_list_filtered", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginate(MeetingParticipant::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays MeetingParticipant entity.
     *
     * @Route("/{id}/show", name="app_admin_meeting_participant_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingParticipant $meetingParticipant
     *
     * @return Response
     */
    public function showAction(MeetingParticipant $meetingParticipant)
    {
        return $this->render(
            'AppBundle:Admin/MeetingParticipant:show.html.twig',
            [
                'meeting_participant' => $meetingParticipant,
            ]
        );
    }

    /**
     * Creates a new MeetingParticipant entity.
     *
     * @Route("/create", name="app_admin_meeting_participant_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.meeting_participant.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_participant_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingParticipant:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_meeting_participant_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param MeetingParticipant $meetingParticipant
     * @param Request            $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(MeetingParticipant $meetingParticipant, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $meetingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meetingParticipant);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.meeting_participant.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_participant_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingParticipant:edit.html.twig',
            [
                'id' => $meetingParticipant->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_meeting_participant_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingParticipant $meetingParticipant
     * @param Request            $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(MeetingParticipant $meetingParticipant, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingParticipant);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message, Response::HTTP_OK);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.meeting_participant.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_participant_list');
    }
}
