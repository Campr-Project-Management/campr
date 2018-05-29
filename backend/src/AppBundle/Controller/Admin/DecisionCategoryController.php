<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DecisionCategory;
use AppBundle\Form\DecisionCategory\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * DecisionCategories admin controller.
 *
 * @Route("/admin/decision-category")
 */
class DecisionCategoryController extends BaseController
{
    /**
     * List all DecisionCategory entities.
     *
     * @Route("/list", name="app_admin_decision_category_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(DecisionCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/DecisionCategory:list.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Lists Decision Categories entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_decision_category_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(DecisionCategory::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Show Decision Category entity.
     *
     * @Route("/{id}/show", name="app_admin_decision_category_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param DecisionCategory $decisionCategory
     *
     * @return Response
     */
    public function showAction(DecisionCategory $decisionCategory)
    {
        return $this->render(
            'AppBundle:Admin/DecisionCategory:show.html.twig',
            [
                'category' => $decisionCategory,
            ]
        );
    }

    /**
     * Create a Decision Category.
     *
     * @Route("/create", name="app_admin_decision_category_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
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
                        ->trans('success.decision_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_category_list');
        }

        return $this->render(
            'AppBundle:Admin/DecisionCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Decision category entity.
     *
     * @Route("/{id}/edit", name="app_admin_decision_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param DecisionCategory $decisionCategory
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, DecisionCategory $decisionCategory)
    {
        $form = $this->createForm(CreateType::class, $decisionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($decisionCategory);

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.decision_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_category_list');
        }

        return $this->render(
            'AppBundle:Admin/DecisionCategory:edit.html.twig',
            [
                'id' => $decisionCategory->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes  Decision Category.
     *
     * @Route("/{id}/delete", name="app_admin_decision_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param DecisionCategory $decisionCategory
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, DecisionCategory $decisionCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($decisionCategory);
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
                    ->trans('success.decision_category.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_decision_category_list');
    }
}
