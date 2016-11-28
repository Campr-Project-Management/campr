<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectStatus;
use AppBundle\Form\ProjectStatus\CreateType as ProjectStatusCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectStatus admin controller.
 *
 * @Route("/admin/project-status")
 */
class ProjectStatusController extends Controller
{
    /**
     * Lists all ProjectStatus entities.
     *
     * @Route("/list", name="app_admin_project_status_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectStatuses = $em
            ->getRepository(ProjectStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectStatus:list.html.twig',
            [
                'project_statuses' => $projectStatuses,
            ]
        );
    }

    /**
     * Lists all ProjectStatus entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_status_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectStatus::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectStatus entity.
     *
     * @Route("/create", name="app_admin_project_status_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectStatus = new ProjectStatus();
        $form = $this->createForm(ProjectStatusCreateType::class, $projectStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_status.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_status_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectStatus entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_status_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request       $request
     * @param ProjectStatus $projectStatus
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectStatus $projectStatus)
    {
        $form = $this->createForm(ProjectStatusCreateType::class, $projectStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectStatus->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_status.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_status_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectStatus:edit.html.twig',
            [
                'project_status' => $projectStatus,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectStatus entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_status_show")
     * @Method({"GET"})
     *
     * @param ProjectStatus $projectStatus
     *
     * @return Response
     */
    public function showAction(ProjectStatus $projectStatus)
    {
        return $this->render(
            'AppBundle:Admin/ProjectStatus:show.html.twig',
            [
                'project_status' => $projectStatus,
            ]
        );
    }

    /**
     * Deletes a ProjectStatus entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_status_delete")
     * @Method({"GET"})
     *
     * @param Request       $request
     * @param ProjectStatus $projectStatus
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectStatus $projectStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectStatus);
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
                    ->trans('admin.project_status.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_status_list');
    }
}
