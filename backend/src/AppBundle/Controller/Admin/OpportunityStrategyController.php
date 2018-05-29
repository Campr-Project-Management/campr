<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\OpportunityStrategy;
use AppBundle\Form\OpportunityStrategy\AdminType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * OpportunityStrategy admin controller.
 *
 * @Route("/admin/opportunity-strategy")
 */
class OpportunityStrategyController extends BaseController
{
    /**
     * List all OpportunityStrategy entities.
     *
     * @Route("/list", name="app_admin_opportunity_strategy_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $opportunityStrategies = $this
            ->getDoctrine()
            ->getRepository(OpportunityStrategy::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/OpportunityStrategy:list.html.twig',
            [
                'opportunityStrategies' => $opportunityStrategies,
            ]
        );
    }

    /**
     * Lists all OpportunityStrategy entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_opportunity_strategy_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(OpportunityStrategy::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays OpportunityStrategy entity.
     *
     * @Route("/{id}/show", name="app_admin_opportunity_strategy_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return Response
     */
    public function showAction(OpportunityStrategy $opportunityStrategy)
    {
        return $this->render(
            'AppBundle:Admin/OpportunityStrategy:show.html.twig',
            [
                'opportunityStrategy' => $opportunityStrategy,
            ]
        );
    }

    /**
     * Creates a new OpportunityStrategy entity.
     *
     * @Route("/create", name="app_admin_opportunity_strategy_create")
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
                        ->trans('success.opportunity_strategy.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_strategy_list');
        }

        return $this->render(
            'AppBundle:Admin/OpportunityStrategy:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing OpportunityStrategy entity.
     *
     * @Route("/{id}/edit", name="app_admin_opportunity_strategy_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request             $request
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, OpportunityStrategy $opportunityStrategy)
    {
        $form = $this->createForm(AdminType::class, $opportunityStrategy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($opportunityStrategy);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.opportunity_strategy.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_opportunity_strategy_list');
        }

        return $this->render(
            'AppBundle:Admin/OpportunityStrategy:edit.html.twig',
            [
                'id' => $opportunityStrategy->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific OpportunityStrategy entity.
     *
     * @Route("/{id}/delete", name="app_admin_opportunity_strategy_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request             $request
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, OpportunityStrategy $opportunityStrategy)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($opportunityStrategy);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.opportunity_strategy.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.opportunity_strategy.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.opportunity_strategy.delete.generic', [], 'flashes')
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

        return $this->redirectToRoute('app_admin_opportunity_strategy_list');
    }
}
