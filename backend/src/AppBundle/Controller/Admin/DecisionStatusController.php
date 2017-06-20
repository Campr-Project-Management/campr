<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DecisionStatus;
use AppBundle\Form\DecisionStatus\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * DecisionStatus admin controller.
 *
 * @Route("/admin/decision-status")
 */
class DecisionStatusController extends BaseController
{
    /**
     * List all Decision Status entities.
     *
     * @Route("/list", name="app_admin_decision_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $statuses = $this
            ->getDoctrine()
            ->getRepository(DecisionStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/DecisionStatus:list.html.twig',
            [
                'statuses' => $statuses,
            ]
        );
    }

    /**
     * Lists all Decision Status entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_decision_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(DecisionStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Decision Status entity.
     *
     * @Route("/{id}/show", name="app_admin_decision_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param DecisionStatus $status
     *
     * @return Response
     */
    public function showAction(DecisionStatus $status)
    {
        return $this->render(
            'AppBundle:Admin/DecisionStatus:show.html.twig',
            [
                'status' => $status,
            ]
        );
    }

    /**
     * Create a new Status entity.
     *
     * @Route("/create", name="app_admin_decision_status_create")
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
                        ->trans('success.decision_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_status_list');
        }

        return $this->render(
            'AppBundle:Admin/DecisionStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     * @Route("/{id}/edit", name="app_admin_decision_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request        $request
     * @param DecisionStatus $status
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, DecisionStatus $status)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.decision_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_decision_status_list');
        }

        return $this->render(
            'AppBundle:Admin/DecisionStatus:edit.html.twig',
            [
                'id' => $status->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Decision Status entity.
     *
     * @Route("/{id}/delete", name="app_admin_decision_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request        $request
     * @param DecisionStatus $status
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, DecisionStatus $status)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($status);
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
                    ->trans('success.decision_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_decision_status_list');
    }
}
