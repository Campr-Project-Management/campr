<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectCostType;
use AppBundle\Form\ProjectCostType\CreateType as ProjectCategoryTypeCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectCostType controller.
 *
 * @Route("/admin/project-cost-type")
 */
class ProjectCostTypeController extends Controller
{
    /**
     * Lists all ProjectCostType entities.
     *
     * @Route("/list", name="app_admin_project_cost_type_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectCostTypes = $em
            ->getRepository(ProjectCostType::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectCostType:list.html.twig',
            [
                'project_cost_types' => $projectCostTypes,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_cost_type_list_filtered")
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
        $response = $dataTableService->paginate(ProjectCostType::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectCostType entity.
     *
     * @Route("/create", name="app_admin_project_cost_type_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectCostType = new ProjectCostType();
        $form = $this->createForm(ProjectCategoryTypeCreateType::class, $projectCostType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCostType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_cost_type.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_cost_type_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCostType:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectCostType entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_cost_type_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request         $request
     * @param ProjectCostType $projectCostType
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectCostType $projectCostType)
    {
        $form = $this->createForm(ProjectCategoryTypeCreateType::class, $projectCostType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectCostType->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCostType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_cost_type.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_cost_type_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCostType:edit.html.twig',
            [
                'project_cost_type' => $projectCostType,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectCostType entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_cost_type_show")
     * @Method({"GET"})
     *
     * @param ProjectCostType $projectCostType
     *
     * @return Response
     */
    public function showAction(ProjectCostType $projectCostType)
    {
        return $this->render(
            'AppBundle:Admin/ProjectCostType:show.html.twig',
            [
                'project_cost_type' => $projectCostType,
            ]
        );
    }

    /**
     * Deletes a ProjectCostType entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_cost_type_delete")
     * @Method({"GET"})
     *
     * @param ProjectCostType $projectCostType
     * @param Request         $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(ProjectCostType $projectCostType, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCostType);
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
                    ->trans('admin.project_cost_type.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_cost_type_list');
    }
}
