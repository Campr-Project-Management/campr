<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MeetingObjective;
use AppBundle\Form\MeetingObjective\BaseType;
use Symfony\Component\HttpFoundation\Response;

/**
 * MeetingObjective admin controller.
 *
 * @Route("/admin/meeting-objective")
 */
class MeetingObjectiveController extends BaseController
{
    /**
     * List all Meeting Objective entities.
     *
     * @Route("/list", name="app_admin_meeting_objective_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $meetingObjectives = $this
            ->getDoctrine()
            ->getRepository(MeetingObjective::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/MeetingObjective:list.html.twig',
            [
                'meetingObjectives' => $meetingObjectives,
            ]
        );
    }

    /**
     * Lists all Meeting Objective entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_meeting_objective_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(MeetingObjective::class, 'description', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays MeetingObjective entity.
     *
     * @Route("/{id}/show", name="app_admin_meeting_objective_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingObjective $meetingObjective
     *
     * @return Response
     */
    public function showAction(MeetingObjective $meetingObjective)
    {
        return $this->render(
            'AppBundle:Admin/MeetingObjective:show.html.twig',
            [
                'meetingObjective' => $meetingObjective,
            ]
        );
    }

    /**
     * Creates a new Meeting Objective entity.
     *
     * @Route("/create", name="app_admin_meeting_objective_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(BaseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($form->getData());
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.meeting_objective.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_objective_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingObjective:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing MeetingObjective entity.
     *
     * @Route("/{id}/edit", name="app_admin_meeting_objective_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param MeetingObjective $meetingObjective
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, MeetingObjective $meetingObjective)
    {
        $form = $this->createForm(BaseType::class, $meetingObjective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($meetingObjective);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.meeting_objective.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_meeting_objective_list');
        }

        return $this->render(
            'AppBundle:Admin/MeetingObjective:edit.html.twig',
            [
                'id' => $meetingObjective->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Meeting Objective entity.
     *
     * @Route("/{id}/delete", name="app_admin_meeting_objective_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param MeetingObjective $meetingObjective
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, MeetingObjective $meetingObjective)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingObjective);
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
                    ->trans('success.meeting_objective.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_meeting_objective_list');
    }
}
