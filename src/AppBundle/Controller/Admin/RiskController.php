<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Risk;
use AppBundle\Form\Risk\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Risk admin controller.
 *
 * @Route("/admin/risk")
 */
class RiskController extends Controller
{
    /**
     * List all Risk entities.
     *
     * @Route("/list", name="app_admin_risk_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
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

        return new JsonResponse($response);
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
                        ->trans('admin.risk.create.success', [], 'admin')
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
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $risk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($risk);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.risk.edit.success', [], 'admin')
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
     * @return RedirectResponse
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
                    ->trans('admin.risk.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_risk_list');
    }
}
