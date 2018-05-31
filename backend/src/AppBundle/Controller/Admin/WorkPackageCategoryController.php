<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\WorkPackageCategory;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\WorkPackageCategory\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * WorkPackageCategory admin controller.
 *
 * @Route("/admin/workpackage-category")
 */
class WorkPackageCategoryController extends BaseController
{
    /**
     * Lists all WorkPackageCategory entities.
     *
     * @Route("/list", name="app_admin_workpackage_category_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $workpackageCategories = $em
            ->getRepository(WorkPackageCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkPackageCategory:list.html.twig',
            [
                'workpackageCategories' => $workpackageCategories,
            ]
        );
    }

    /**
     * Lists all WorkPackageCategory entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_workpackage_category_list_filtered")
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
        $response = $dataTableService->paginateByColumn(WorkPackageCategory::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays WorkPackageCategory entity.
     *
     * @Route("/{id}/show", name="app_admin_workpackage_category_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackageCategory $workPackageCategory
     *
     * @return Response
     */
    public function showAction(WorkPackageCategory $workPackageCategory)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackageCategory:show.html.twig',
            [
                'workPackageCategory' => $workPackageCategory,
            ]
        );
    }

    /**
     * Creates a new WorkPackageCategory entity.
     *
     * @Route("/create", name="app_admin_workpackage_category_create", options={"expose"=true})
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
                        ->trans('success.workpackage_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_category_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing WorkPackageCategory entity.
     *
     * @Route("/{id}/edit", name="app_admin_workpackage_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request             $request
     * @param WorkPackageCategory $workPackageCateory
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, WorkPackageCategory $workPackageCateory)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workPackageCateory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workPackageCateory);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workpackage_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_category_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageCategory:edit.html.twig',
            [
                'id' => $workPackageCateory->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific WorkPackageCategory entity.
     *
     * @Route("/{id}/delete", name="app_admin_workpackage_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request             $request
     * @param WorkPackageCategory $workPackageCategory
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, WorkPackageCategory $workPackageCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPackageCategory);
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
                    ->trans('success.workpackage_category.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_workpackage_category_list');
    }
}
