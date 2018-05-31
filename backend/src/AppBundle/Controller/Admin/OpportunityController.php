<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Opportunity;
use AppBundle\Form\Opportunity\AdminType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Opportunity admin controller.
 *
 * @Route("/admin/opportunity")
 */
class OpportunityController extends BaseController
{
    /**
     * List all Opportunity entities.
     *
     * @Route("/list", name="app_admin_opportunity_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $opportunities = $this
            ->getDoctrine()
            ->getRepository(Opportunity::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Opportunity:list.html.twig',
            [
                'opportunities' => $opportunities,
            ]
        );
    }

    /**
     * Lists all Opportunity entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_opportunity_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Opportunity::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Opportunity entity.
     *
     * @Route("/{id}/show", name="app_admin_opportunity_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Opportunity $opportunity
     *
     * @return Response
     */
    public function showAction(Opportunity $opportunity)
    {
        return $this->render(
            'AppBundle:Admin/Opportunity:show.html.twig',
            [
                'opportunity' => $opportunity,
            ]
        );
    }

    /**
     * Creates a new Opportunity entity.
     *
     * @Route("/create", name="app_admin_opportunity_create")
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
                        ->trans('success.opportunity.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_list');
        }

        return $this->render(
            'AppBundle:Admin/Opportunity:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Opportunity entity.
     *
     * @Route("/{id}/edit", name="app_admin_opportunity_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param Opportunity $opportunity
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Opportunity $opportunity)
    {
        $form = $this->createForm(AdminType::class, $opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($opportunity);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.opportunity.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_list');
        }

        return $this->render(
            'AppBundle:Admin/Opportunity:edit.html.twig',
            [
                'id' => $opportunity->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Opportunity entity.
     *
     * @Route("/{id}/delete", name="app_admin_opportunity_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param Opportunity $opportunity
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Opportunity $opportunity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($opportunity);
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
                    ->trans('success.opportunity.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_opportunity_list');
    }
}
