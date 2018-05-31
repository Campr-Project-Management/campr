<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cost;
use AppBundle\Form\Cost\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cost admin controller.
 *
 * @Route("/admin/cost")
 */
class CostController extends BaseController
{
    /**
     * List all Cost entities.
     *
     * @Route("/list", name="app_admin_cost_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $costs = $this
            ->getDoctrine()
            ->getRepository(Cost::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Cost:list.html.twig',
            [
                'costs' => $costs,
            ]
        );
    }

    /**
     * Lists all Cost entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_cost_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Cost::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Cost entity.
     *
     * @Route("/{id}/show", name="app_admin_cost_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Cost $cost
     *
     * @return Response
     */
    public function showAction(Cost $cost)
    {
        return $this->render(
            'AppBundle:Admin/Cost:show.html.twig',
            [
                'cost' => $cost,
            ]
        );
    }

    /**
     * Creates a new Cost entity.
     *
     * @Route("/create", name="app_admin_cost_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
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
                        ->trans('success.cost.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_cost_list');
        }

        return $this->render(
            'AppBundle:Admin/Cost:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Cost entity.
     *
     * @Route("/{id}/edit", name="app_admin_cost_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Cost    $cost
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Cost $cost)
    {
        $form = $this->createForm(CreateType::class, $cost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($cost);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.cost.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_cost_list');
        }

        return $this->render(
            'AppBundle:Admin/Cost:edit.html.twig',
            [
                'id' => $cost->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Cost entity.
     *
     * @Route("/{id}/delete", name="app_admin_cost_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Cost    $cost
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Cost $cost)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cost);
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
                    ->trans('success.cost.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_cost_list');
    }
}
