<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\OpportunityStatus;
use AppBundle\Form\OpportunityStatus\AdminType;
use Symfony\Component\HttpFoundation\Response;

/**
 * OpportunityStatus admin controller.
 *
 * @Route("/admin/opportunity-status")
 */
class OpportunityStatusController extends BaseController
{
    /**
     * List all OpportunityStrategy entities.
     *
     * @Route("/list", name="app_admin_opportunity_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $opportunityStatuses = $this
            ->getDoctrine()
            ->getRepository(OpportunityStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/OpportunityStatus:list.html.twig',
            [
                'opportunityStatuses' => $opportunityStatuses,
            ]
        );
    }

    /**
     * Lists all OpportunityStatus entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_opportunity_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(OpportunityStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays OpportunityStatus entity.
     *
     * @Route("/{id}/show", name="app_admin_opportunity_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param OpportunityStatus $opportunityStatus
     *
     * @return Response
     */
    public function showAction(OpportunityStatus $opportunityStatus)
    {
        return $this->render(
            'AppBundle:Admin/OpportunityStatus:show.html.twig',
            [
                'opportunityStatus' => $opportunityStatus,
            ]
        );
    }

    /**
     * Creates a new OpportunityStatus entity.
     *
     * @Route("/create", name="app_admin_opportunity_status_create")
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
                        ->trans('success.opportunity_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_status_list');
        }

        return $this->render(
            'AppBundle:Admin/OpportunityStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing OpportunityStatus entity.
     *
     * @Route("/{id}/edit", name="app_admin_opportunity_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param OpportunityStatus $opportunityStatus
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, OpportunityStatus $opportunityStatus)
    {
        $form = $this->createForm(AdminType::class, $opportunityStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($opportunityStatus);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.opportunity_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_status_list');
        }

        return $this->render(
            'AppBundle:Admin/OpportunityStatus:edit.html.twig',
            [
                'id' => $opportunityStatus->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific OpportunityStatus entity.
     *
     * @Route("/{id}/delete", name="app_admin_opportunity_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request           $request
     * @param OpportunityStatus $opportunityStatus
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, OpportunityStatus $opportunityStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($opportunityStatus);
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
                    ->trans('success.opportunity_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_opportunity_status_list');
    }
}
