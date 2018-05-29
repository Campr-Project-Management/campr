<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TodoStatus;
use AppBundle\Form\TodoStatus\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TodoStatus admin controller.
 *
 * @Route("/admin/todo-status")
 */
class TodoStatusController extends BaseController
{
    /**
     * List all Todo Status entities.
     *
     * @Route("/list", name="app_admin_todo_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $statuses = $this
            ->getDoctrine()
            ->getRepository(TodoStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/TodoStatus:list.html.twig',
            [
                'statuses' => $statuses,
            ]
        );
    }

    /**
     * Lists all Todo Status entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_todo_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(TodoStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Todo Status entity.
     *
     * @Route("/{id}/show", name="app_admin_todo_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param TodoStatus $status
     *
     * @return Response
     */
    public function showAction(TodoStatus $status)
    {
        return $this->render(
            'AppBundle:Admin/TodoStatus:show.html.twig',
            [
                'status' => $status,
            ]
        );
    }

    /**
     * Create a new Todo Status entity.
     *
     * @Route("/create", name="app_admin_todo_status_create")
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
                        ->trans('success.todo_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_status_list');
        }

        return $this->render(
            'AppBundle:Admin/TodoStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Todo Status entity.
     *
     * @Route("/{id}/edit", name="app_admin_todo_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param TodoStatus $status
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, TodoStatus $status)
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
                        ->trans('success.todo_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_status_list');
        }

        return $this->render(
            'AppBundle:Admin/TodoStatus:edit.html.twig',
            [
                'id' => $status->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Todo Status entity.
     *
     * @Route("/{id}/delete", name="app_admin_todo_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param TodoStatus $status
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, TodoStatus $status)
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
                    ->trans('success.todo_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_todo_status_list');
    }
}
