<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MeetingAgenda;
use AppBundle\Form\MeetingAgenda\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MeetingAgenda controller.
 *
 * @Route("/admin/meeting-agenda")
 */
class MeetingAgendaController extends Controller
{
    /**
     * Lists all MeetingAgenda entities.
     *
     * @Route("/list", name="app_admin_meeting_agenda_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetingAgendas = $em
            ->getRepository(MeetingAgenda::class)
            ->findAll();

        return $this->render(
            'AppBundle:Admin/MeetingAgenda:list.html.twig',
            [
                'meeting_agendas' => $meetingAgendas,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_meeting_agenda_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginate(MeetingAgenda::class, $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays MeetingAgenda entity.
     *
     * @Route("/{id}/show", name="app_admin_meeting_agenda_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingAgenda $meetingAgenda
     *
     * @return Response
     */
    public function showAction(MeetingAgenda $meetingAgenda)
    {
        return $this->render(
            'AppBundle:Admin/MeetingAgenda:show.html.twig',
            [
                'meeting_agenda' => $meetingAgenda,
            ]
        );
    }

    /**
     * Creates a new MeetingAgenda entity.
     *
     * @Route("/create", name="app_admin_meeting_agenda_create", options={"expose"=true})
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
                        ->trans('admin.meeting_agenda.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_agenda_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingAgenda:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_meeting_agenda_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(MeetingAgenda $meetingAgenda, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $meetingAgenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meetingAgenda);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.meeting_agenda.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_agenda_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingAgenda:edit.html.twig',
            [
                'id' => $meetingAgenda->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_meeting_agenda_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(MeetingAgenda $meetingAgenda, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingAgenda);
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
                    ->trans('admin.meeting_agenda.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_agenda_list');
    }
}
