<?php

namespace AppBundle\Controller\Admin;

use Proxies\__CG__\AppBundle\Entity\Risk;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\RiskCategory;
use AppBundle\Form\RiskCategory\CreateType;
use AppBundle\Form\RiskCategory\EditType;

/**
 * @Route("/admin/risk-category")
 */
class RiskCategoryController extends Controller
{
    /**
     * @Route("/list", name="app_admin_risk_category_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
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
        $response = $dataTableService->paginate(RiskCategory::class, 'name', $requestParams);

        return new JsonResponse($response);
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
    public function showAction(RiskCategory $category)
    {
        return $this->render(
            'AppBundle:Admin/RiskCategory:show.html.twig',
            [
                'category' => $category,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_risk_category_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
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
                        ->trans('admin.risk_category.create.success', [], 'admin')
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
     * @Route("/{id}/edit", name="app_admin_risk_category_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(RiskCategory $riskCategory, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $riskCategory);
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
                        ->trans('admin.risk_category.edit.success', [], 'admin')
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
     * @Route("/{id}/delete", name="app_admin_risk_category_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(RiskCategory $riskCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskCategory);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.risk_category.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_category_list');
    }
}
