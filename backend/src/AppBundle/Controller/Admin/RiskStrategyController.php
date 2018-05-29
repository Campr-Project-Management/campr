<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\RiskStrategy;
use AppBundle\Form\RiskStrategy\AdminType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RiskStrategy admin controller.
 *
 * @Route("/admin/risk-strategy")
 */
class RiskStrategyController extends BaseController
{
    /**
     * List all RiskStrategy controller.
     *
     * @Route("/list", name="app_admin_risk_strategy_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
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

        return $this->createApiResponse($response);
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
        $riskStrategy = new RiskStrategy();
        $form = $this->createForm(AdminType::class, $riskStrategy);
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
        $form = $this->createForm(AdminType::class, $riskStrategy);
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
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($riskStrategy);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.risk_strategy.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.risk_strategy.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.risk_strategy.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                $flashKey,
                $flashMessage
            )
        ;

        return $this->redirectToRoute('app_admin_risk_strategy_list');
    }
}
