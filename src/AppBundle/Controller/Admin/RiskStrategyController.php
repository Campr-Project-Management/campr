<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
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
 * RiskStrategy admin controller.
 *
 * @Route("/admin/risk-strategy")
 */
class RiskStrategyController extends Controller
{
    /**
     * List all RiskStrategy controller.
     *
     * @Route("/list", name="app_admin_risk_strategy_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
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
     * Lists all RiskStrategy entities filtered and paginated.
     *
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
     * Creates a new RiskStrategy entity.
     *
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
                        ->trans('success.risk_strategy.create', [], 'flashes')
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
     * Displays a form to edit an existing RiskStrategy entity.
     *
     * @Route("/{id}/edit", name="app_admin_risk_strategy_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param RiskStrategy $riskStrategy
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, RiskStrategy $riskStrategy)
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
                        ->trans('success.risk_strategy.edit', [], 'flashes')
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
     * Deletes a specific RiskStrategy entity.
     *
     * @Route("/{id}/delete", name="app_admin_risk_strategy_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param RiskStrategy $riskStrategy
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, RiskStrategy $riskStrategy)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskStrategy);
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
                    ->trans('success.risk_strategy.delete.general', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_strategy_list');
    }
}
