<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Risk;
use AppBundle\Form\Risk\AdminType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Risk admin controller.
 *
 * @Route("/admin/risk")
 */
class RiskController extends BaseController
{
    /**
     * List all Risk entities.
     *
     * @Route("/list", name="app_admin_risk_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $risks = $this
            ->getDoctrine()
            ->getRepository(Risk::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Risk:list.html.twig',
            [
                'risks' => $risks,
            ]
        );
    }

    /**
     * Lists all Risk entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_risk_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Risk::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Risk entity.
     *
     * @Route("/{id}/show", name="app_admin_risk_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Risk $risk
     *
     * @return Response
     */
    public function showAction(Risk $risk)
    {
        return $this->render(
            'AppBundle:Admin/Risk:show.html.twig',
            [
                'risk' => $risk,
            ]
        );
    }

    /**
     * Creates a new Risk entity.
     *
     * @Route("/create", name="app_admin_risk_create")
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
                        ->trans('success.risk.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_list');
        }

        return $this->render(
            'AppBundle:Admin/Risk:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Risk entity.
     *
     * @Route("/{id}/edit", name="app_admin_risk_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Risk    $risk
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Risk $risk)
    {
        $form = $this->createForm(AdminType::class, $risk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($risk);

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.risk.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_risk_list');
        }

        return $this->render(
            'AppBundle:Admin/Risk:edit.html.twig',
            [
                'id' => $risk->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Risk entity.
     *
     * @Route("/{id}/delete", name="app_admin_risk_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Risk    $risk
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Risk $risk)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($risk);
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
                    ->trans('success.risk.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_list');
    }
}
