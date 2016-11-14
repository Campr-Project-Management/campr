<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\RiskStrategy;
use AppBundle\Form\RiskStrategy\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/risk-strategy")
 */
class RiskStrategyController extends Controller
{
    /**
     * @Route("/list", name="app_admin_risk_strategy_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction()
    {
        $strategies = $this
            ->getDoctrine()
            ->getRepository(RiskStrategy::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/RiskStrategy:list.html.twig',
            [
                'strategies' => $strategies,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_risk_strategy_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(RiskStrategy::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays RiskStrategy entity.
     *
     * @Route("/{id}/show", name="app_admin_risk_strategy_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param RiskStrategy $strategy
     *
     * @return Response
     */
    public function showAction(RiskStrategy $strategy)
    {
        return $this->render(
            'AppBundle:Admin/RiskStrategy:show.html.twig',
            [
                'strategy' => $strategy,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_risk_strategy_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
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
                        ->trans('admin.risk_strategy.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_strategy_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskStrategy:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_risk_strategy_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param RiskStrategy $riskStrategy
     * @param Request      $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(RiskStrategy $riskStrategy, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $riskStrategy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($riskStrategy);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.risk_strategy.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_strategy_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskStrategy:edit.html.twig',
            [
                'id' => $riskStrategy->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_risk_strategy_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param RiskStrategy $riskStrategy
     *
     * @return RedirectResponse
     */
    public function deleteAction(RiskStrategy $riskStrategy)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskStrategy);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.risk_strategy.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_strategy_list');
    }
}
