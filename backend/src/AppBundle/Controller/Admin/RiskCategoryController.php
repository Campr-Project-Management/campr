<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\RiskCategory;
use AppBundle\Form\RiskCategory\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * RiskCategory admin controller.
 *
 * @Route("/admin/risk-category")
 */
class RiskCategoryController extends BaseController
{
    /**
     * List all RiskCategory entities.
     *
     * @Route("/list", name="app_admin_risk_category_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(RiskCategory::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/RiskCategory:list.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Lists all RiskCategory entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_risk_category_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(RiskCategory::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays RiskCategory entity.
     *
     * @Route("/{id}/show", name="app_admin_risk_category_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param RiskCategory $riskCategory
     *
     * @return Response
     */
    public function showAction(RiskCategory $riskCategory)
    {
        return $this->render(
            'AppBundle:Admin/RiskCategory:show.html.twig',
            [
                'category' => $riskCategory,
            ]
        );
    }

    /**
     * Creates a new RiskCategory entity.
     *
     * @Route("/create", name="app_admin_risk_category_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $riskCategory = new RiskCategory();
        $form = $this->createForm(CreateType::class, $riskCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.risk_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_category_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing RiskCategory entity.
     *
     * @Route("/{id}/edit", name="app_admin_risk_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param RiskCategory $riskCategory
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, RiskCategory $riskCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $riskCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($riskCategory);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.risk_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_category_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskCategory:edit.html.twig',
            [
                'id' => $riskCategory->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific RiskCategory entity.
     *
     * @Route("/{id}/delete", name="app_admin_risk_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param RiskCategory $riskCategory
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, RiskCategory $riskCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskCategory);
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
                    ->trans('success.risk_category.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_category_list');
    }
}
