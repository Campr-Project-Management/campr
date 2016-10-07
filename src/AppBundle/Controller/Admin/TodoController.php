<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Todo;
use AppBundle\Form\Todo\CreateType;

/**
 * @Route("/admin/todo")
 */
class TodoController extends Controller
{
    /**
     * @Route("/list", name="app_admin_todo_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $todos = $this
            ->getDoctrine()
            ->getRepository(Todo::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin\Todo:list.html.twig',
            [
                'todos' => $todos,
            ]
        );
    }

    /**
     * Displays Todo entity.
     *
     * @Route("/{id}/show", name="app_admin_todo_show")
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
     * @Route("/create", name="app_admin_todo_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
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
                        ->trans('admin.todo.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_list');
        }

        return $this->render(
            'AppBundle:Admin\Todo:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_todo_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(Todo $todo, Request $request)
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
                        ->trans('admin.todo.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_todo_list');
        }

        return $this->render(
            'AppBundle:Admin\Todo:edit.html.twig',
            [
                'id' => $todo->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_todo_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.todo.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_todo_list');
    }
}
