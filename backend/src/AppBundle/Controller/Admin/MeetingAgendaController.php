<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MeetingAgenda;
use AppBundle\Form\MeetingAgenda\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MeetingAgenda admin controller.
 *
 * @Route("/admin/meeting-agenda")
 */
class MeetingAgendaController extends BaseController
{
    /**
     * Lists all MeetingAgenda entities.
     *
     * @Route("/list", name="app_admin_meeting_agenda_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all MeetingAgenda entities filtered and paginated.
     *
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
        $response = $dataTableService->paginateByColumn(MeetingAgenda::class, 'name', $requestParams);

        return $this->createApiResponse($response);
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
                        ->trans('success.meeting_agenda.create', [], 'flashes')
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
     * Displays a form to edit an existing MeetingAgenda entity.
     *
     * @Route("/{id}/edit", name="app_admin_meeting_agenda_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request       $request
     * @param MeetingAgenda $meetingAgenda
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, MeetingAgenda $meetingAgenda)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $meetingAgenda);
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
                        ->trans('success.meeting_agenda.edit', [], 'flashes')
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
     * Deletes a specific MeetingAgenda entity.
     *
     * @Route("/{id}/delete", name="app_admin_meeting_agenda_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request       $request
     * @param MeetingAgenda $meetingAgenda
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, MeetingAgenda $meetingAgenda)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingAgenda);
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
                    ->trans('success.meeting_agenda.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_agenda_list');
    }
}
