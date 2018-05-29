<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\RiskStatus;
use AppBundle\Form\RiskStatus\AdminType;
use Symfony\Component\HttpFoundation\Response;

/**
 * RiskStatus admin controller.
 *
 * @Route("/admin/risk-status")
 */
class RiskStatusController extends BaseController
{
    /**
     * List all RiskStrategy entities.
     *
     * @Route("/list", name="app_admin_risk_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $riskStatuses = $this
            ->getDoctrine()
            ->getRepository(RiskStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/RiskStatus:list.html.twig',
            [
                'riskStatuses' => $riskStatuses,
            ]
        );
    }

    /**
     * Lists all RiskStatus entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_risk_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(RiskStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays RiskStatus entity.
     *
     * @Route("/{id}/show", name="app_admin_risk_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param RiskStatus $riskStatus
     *
     * @return Response
     */
    public function showAction(RiskStatus $riskStatus)
    {
        return $this->render(
            'AppBundle:Admin/RiskStatus:show.html.twig',
            [
                'riskStatus' => $riskStatus,
            ]
        );
    }

    /**
     * Creates a new RiskStatus entity.
     *
     * @Route("/create", name="app_admin_risk_status_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(AdminType::class);
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
                        ->trans('success.risk_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_status_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing RiskStatus entity.
     *
     * @Route("/{id}/edit", name="app_admin_risk_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param RiskStatus $riskStatus
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, RiskStatus $riskStatus)
    {
        $form = $this->createForm(AdminType::class, $riskStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($riskStatus);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.risk_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_status_list');
        }

        return $this->render(
            'AppBundle:Admin/RiskStatus:edit.html.twig',
            [
                'id' => $riskStatus->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific RiskStatus entity.
     *
     * @Route("/{id}/delete", name="app_admin_risk_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param RiskStatus $riskStatus
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, RiskStatus $riskStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskStatus);
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
                    ->trans('success.risk_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_status_list');
    }
}
