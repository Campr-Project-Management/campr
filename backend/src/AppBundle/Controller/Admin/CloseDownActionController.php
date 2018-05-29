<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\CloseDownAction;
use AppBundle\Form\CloseDownAction\AdminType as CloseDownActionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * CloseDownAction admin controller.
 *
 * @Route("/admin/close-down-action")
 */
class CloseDownActionController extends BaseController
{
    /**
     * Lists all CloseDownAction entities.
     *
     * @Route("/list", name="app_admin_close_down_action_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $closeDownActions = $em
            ->getRepository(CloseDownAction::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/CloseDownAction:list.html.twig',
            [
                'closeDownActions' => $closeDownActions,
            ]
        );
    }

    /**
     * Lists all CloseDownAction entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_close_down_action_filtered")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(CloseDownAction::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new CloseDownAction entity.
     *
     * @Route("/create", name="app_admin_close_down_action_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $closeDownAction = new CloseDownAction();
        $form = $this->createForm(CloseDownActionType::class, $closeDownAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($closeDownAction);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.close_down_action.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_close_down_action_list');
        }

        return $this->render(
            'AppBundle:Admin/CloseDownAction:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing CloseDownAction entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_close_down_action_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request         $request
     * @param CloseDownAction $closeDownAction
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, CloseDownAction $closeDownAction)
    {
        $form = $this->createForm(CloseDownActionType::class, $closeDownAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($closeDownAction);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.close_down_action.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_close_down_action_list');
        }

        return $this->render(
            'AppBundle:Admin/CloseDownAction:edit.html.twig',
            [
                'closeDownAction' => $closeDownAction,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a CloseDownAction entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_close_down_action_show")
     * @Method({"GET"})
     *
     * @param CloseDownAction $closeDownAction
     *
     * @return Response
     */
    public function showAction(CloseDownAction $closeDownAction)
    {
        return $this->render(
            'AppBundle:Admin/CloseDownAction:show.html.twig',
            [
                'closeDownAction' => $closeDownAction,
            ]
        );
    }

    /**
     * Deletes a CloseDownAction entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_close_down_action_delete")
     * @Method({"GET"})
     *
     * @param Request         $request
     * @param CloseDownAction $closeDownAction
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, CloseDownAction $closeDownAction)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($closeDownAction);
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
                    ->trans('success.close_down_action.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_close_down_action_list');
    }
}
