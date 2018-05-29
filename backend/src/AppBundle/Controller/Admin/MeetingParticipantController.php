<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MeetingParticipant;
use AppBundle\Form\MeetingParticipant\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MeetingParticipant admin controller.
 *
 * @Route("/admin/meeting-participant")
 */
class MeetingParticipantController extends BaseController
{
    /**
     * Lists all MeetingParticipant entities.
     *
     * @Route("/list", name="app_admin_meeting_participant_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all MeetingParticipant entities filtered and paginated.
     *
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
        $response = $dataTableService->paginateByColumn(MeetingParticipant::class, 'name', $requestParams);

        return $this->createApiResponse($response);
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
                        ->trans('success.meeting_participant.create', [], 'flashes')
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
     * Displays a form to edit an existing MeetingParticipant entity.
     *
     * @Route("/{id}/edit", name="app_admin_meeting_participant_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request            $request
     * @param MeetingParticipant $meetingParticipant
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, MeetingParticipant $meetingParticipant)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $meetingParticipant);
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
                        ->trans('success.meeting_participant.edit', [], 'flashes')
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
     * Deletes a specific MeetingParticipant entity.
     *
     * @Route("/{id}/delete", name="app_admin_meeting_participant_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request            $request
     * @param MeetingParticipant $meetingParticipant
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, MeetingParticipant $meetingParticipant)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingParticipant);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.meeting_participant.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_participant_list');
    }
}
