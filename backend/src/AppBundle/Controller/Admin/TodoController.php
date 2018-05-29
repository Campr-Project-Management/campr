<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Todo;
use AppBundle\Form\Todo\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Todo admin controller.
 *
 * @Route("/admin/todo")
 */
class TodoController extends BaseController
{
    /**
     * List all Todo entities.
     *
     * @Route("/list", name="app_admin_todo_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $todos = $this
            ->getDoctrine()
            ->getRepository(Todo::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Todo:list.html.twig',
            [
                'todos' => $todos,
            ]
        );
    }

    /**
     * Lists all Todo entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_todo_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Todo::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Todo entity.
     *
     * @Route("/{id}/show", name="app_admin_todo_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Todo $todo
     *
     * @return Response
     */
    public function showAction(Todo $todo)
    {
        return $this->render(
            'AppBundle:Admin/Todo:show.html.twig',
            [
                'todo' => $todo,
            ]
        );
    }

    /**
     * Creates a new Todo entity.
     *
     * @Route("/create", name="app_admin_todo_create")
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
                        ->trans('success.todo.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_list');
        }

        return $this->render(
            'AppBundle:Admin/Todo:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     * @Route("/{id}/edit", name="app_admin_todo_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Todo    $todo
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todo->setUpdatedAt(new \DateTime());
            $em->persist($todo);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.todo.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_list');
        }

        return $this->render(
            'AppBundle:Admin/Todo:edit.html.twig',
            [
                'id' => $todo->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_todo_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Todo    $todo
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
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
                    ->trans('success.todo.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_todo_list');
    }
}
