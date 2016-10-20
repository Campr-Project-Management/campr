<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\User\CreateType;
use AppBundle\Form\User\EditType;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="app_admin_user_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findAll()
        ;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->get('page', 1),
            $this->getParameter('admin.per_page')
        );

        return $this->render(
            'AppBundle:Admin/User:list.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * Displays User entity.
     *
     * @Route("/{id}/show", name="app_admin_user_show")
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render(
            'AppBundle:Admin/User:show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_user_create")
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
            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.user.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render(
            'AppBundle:Admin/User:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_user_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.user.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render(
            'AppBundle:Admin/User:edit.html.twig',
            [
                'id' => $user->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_user_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.user.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_user_list');
    }
}
