<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Meeting;
use AppBundle\Form\Meeting\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Meeting admin controller.
 *
 * @Route("/admin/meeting")
 */
class MeetingController extends BaseController
{
    /**
     * Lists all Meeting entities.
     *
     * @Route("/list", name="app_admin_meeting_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetings = $em
            ->getRepository(Meeting::class)
            ->findAll();

        return $this->render(
            'AppBundle:Admin/Meeting:list.html.twig',
            [
                'meetings' => $meetings,
            ]
        );
    }

    /**
     * Lists all Meeting entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_meeting_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Meeting ::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Meeting entity.
     *
     * @Route("/{id}/show", name="app_admin_meeting_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return Response
     */
    public function showAction(Meeting $meeting)
    {
        return $this->render(
            'AppBundle:Admin/Meeting:show.html.twig',
            [
                'meeting' => $meeting,
            ]
        );
    }

    /**
     * Creates a new Meeting entity.
     *
     * @Route("/create", name="app_admin_meeting_create", options={"expose"=true})
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
                        ->trans('success.meeting.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_list');
        }

        return $this->render(
            'AppBundle:Admin/Meeting:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Meeting entity.
     *
     * @Route("/{id}/edit", name="app_admin_meeting_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($meeting);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.meeting.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_list');
        }

        return $this->render(
            'AppBundle:Admin/Meeting:edit.html.twig',
            [
                'id' => $meeting->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Meeting entity.
     *
     * @Route("/{id}/delete", name="app_admin_meeting_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Meeting $meeting)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meeting);
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
                    ->trans('success.meeting.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_list');
    }
}
