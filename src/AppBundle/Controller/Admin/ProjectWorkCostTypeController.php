<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectWorkCostType;
use AppBundle\Form\ProjectWorkCostType\CreateType as ProjectWorkCostTypeCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectWorkCostType controller.
 *
 * @Route("/admin/project-work-cost-type")
 */
class ProjectWorkCostTypeController extends Controller
{
    /**
     * Lists all ProjectWorkCostType entities.
     *
     * @Route("/list", name="app_admin_project_work_cost_type_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectWorkCostTypes = $em
            ->getRepository(ProjectWorkCostType::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectWorkCostType:list.html.twig',
            [
                'project_work_cost_types' => $projectWorkCostTypes,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_work_cost_type_list_filtered")
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
        $response = $dataTableService->paginate(ProjectWorkCostType::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectWorkCostType entity.
     *
     * @Route("/create", name="app_admin_project_work_cost_type_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectWorkCostType = new ProjectWorkCostType();
        $form = $this->createForm(ProjectWorkCostTypeCreateType::class, $projectWorkCostType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectWorkCostType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_work_cost_type.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_work_cost_type_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectWorkCostType:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectWorkCostType entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_work_cost_type_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request             $request
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectWorkCostType $projectWorkCostType)
    {
        $form = $this->createForm(ProjectWorkCostTypeCreateType::class, $projectWorkCostType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectWorkCostType->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectWorkCostType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_work_cost_type.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_work_cost_type_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectWorkCostType:edit.html.twig',
            [
                'project_work_cost_type' => $projectWorkCostType,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectWorkCostType entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_work_cost_type_show")
     * @Method({"GET"})
     *
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return Response
     */
    public function showAction(ProjectWorkCostType $projectWorkCostType)
    {
        return $this->render(
            'AppBundle:Admin/ProjectWorkCostType:show.html.twig',
            [
                'project_work_cost_type' => $projectWorkCostType,
            ]
        );
    }

    /**
     * Deletes a ProjectWorkCostType entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_work_cost_type_delete")
     * @Method({"GET"})
     *
     * @param ProjectWorkCostType $projectWorkCostType
     * @param Request             $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(ProjectWorkCostType $projectWorkCostType, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectWorkCostType);
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
                    ->trans('admin.project_work_cost_type.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_work_cost_type_list');
    }
}
