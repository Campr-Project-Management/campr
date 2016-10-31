<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectComplexity;
use AppBundle\Form\ProjectComplexity\CreateType as ProjectComplexityCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectComplexity controller.
 *
 * @Route("/admin/project-complexity")
 */
class ProjectComplexityController extends Controller
{
    /**
     * Lists all ProjectComplexity entities.
     *
     * @Route("/list", name="app_admin_project_complexity_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectComplexities = $em
            ->getRepository(ProjectComplexity::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:list.html.twig',
            [
                'project_complexities' => $projectComplexities,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_complexity_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectComplexity::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectComplexity entity.
     *
     * @Route("/create", name="app_admin_project_complexity_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectComplexity = new ProjectComplexity();
        $form = $this->createForm(ProjectComplexityCreateType::class, $projectComplexity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_complexity.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_complexity_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectComplexity entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_complexity_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param ProjectComplexity $projectComplexity
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectComplexity $projectComplexity)
    {
        $form = $this->createForm(ProjectComplexityCreateType::class, $projectComplexity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectComplexity->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_complexity.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_complexity_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:edit.html.twig',
            [
                'project_complexity' => $projectComplexity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectComplexity entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_complexity_show")
     * @Method({"GET"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return Response
     */
    public function showAction(ProjectComplexity $projectComplexity)
    {
        return $this->render(
            'AppBundle:Admin/ProjectComplexity:show.html.twig',
            [
                'project_complexity' => $projectComplexity,
            ]
        );
    }

    /**
     * Deletes a ProjectComplexity entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_complexity_delete")
     * @Method({"GET"})
     *
     * @param ProjectComplexity $projectComplexity
     * @param Request           $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(ProjectComplexity $projectComplexity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectComplexity);
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
                    ->trans('admin.project_complexity.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_complexity_list');
    }
}
