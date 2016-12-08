<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Form\ProjectCategory\CreateType as ProjectCategoryCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectCategory admin controller.
 *
 * @Route("/admin/project-category")
 */
class ProjectCategoryController extends Controller
{
    /**
     * Lists all ProjectCategory entities.
     *
     * @Route("/list", name="app_admin_project_category_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectCategories = $em
            ->getRepository(ProjectCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectCategory:list.html.twig',
            [
                'project_categories' => $projectCategories,
            ]
        );
    }

    /**
     * Lists all ProjectCategory entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_category_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectCategory::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectCategory entity.
     *
     * @Route("/create", name="app_admin_project_category_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectCategory = new ProjectCategory();
        $form = $this->createForm(ProjectCategoryCreateType::class, $projectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCategory);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_category.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_category_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectCategory entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_category_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request         $request
     * @param ProjectCategory $projectCategory
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectCategory $projectCategory)
    {
        $form = $this->createForm(ProjectCategoryCreateType::class, $projectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectCategory->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCategory);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_category.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_category_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCategory:edit.html.twig',
            [
                'project_category' => $projectCategory,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectCategory entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_category_show")
     * @Method({"GET"})
     *
     * @param ProjectCategory $projectCategory
     *
     * @return Response
     */
    public function showAction(ProjectCategory $projectCategory)
    {
        return $this->render(
            'AppBundle:Admin/ProjectCategory:show.html.twig',
            [
                'project_category' => $projectCategory,
            ]
        );
    }

    /**
     * Deletes a ProjectCategory entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_category_delete")
     * @Method({"GET"})
     *
     * @param Request         $request
     * @param ProjectCategory $projectCategory
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectCategory $projectCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCategory);
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
                    ->trans('admin.project_category.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_category_list');
    }
}
