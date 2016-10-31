<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Project;
use AppBundle\Form\Project\CreateType as ProjectCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project controller.
 *
 * @Route("/admin/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/list", name="app_admin_project_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em
            ->getRepository('AppBundle:Project')
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Project:list.html.twig',
            [
                'projects' => $projects,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Project::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/create", name="app_admin_project_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_list');
        }

        return $this->render(
            'AppBundle:Admin/Project:create.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_list');
        }

        return $this->render(
            'AppBundle:Admin/Project:edit.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Project entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_show")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return Response
     */
    public function showAction(Project $project)
    {
        return $this->render(
            'AppBundle:Admin/Project:show.html.twig',
            [
                'project' => $project,
            ]
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_delete")
     * @Method({"GET"})
     *
     * @param Project $project
     * @param Request $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Project $project, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
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
                    ->trans('admin.project.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_list');
    }
}
