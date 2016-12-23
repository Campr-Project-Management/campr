<?php

namespace MainBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\User\CreateType;
use AppBundle\Form\User\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * User admin controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * List all user entities.
     *
     * @Route("/list", name="main_admin_user_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findAll()
        ;

        return $this->render(
            'MainBundle:Admin/User:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * Displays User entity.
     *
     * @Route("/{id}/show", name="main_admin_user_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(User $user)
    {
        return $this->render(
            'MainBundle:Admin/User:show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create", name="main_admin_user_create")
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

            return $this->redirectToRoute('main_admin_user_list');
        }

        return $this->render(
            'MainBundle:Admin/User:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="main_admin_user_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, User $user)
    {
        if ($user->hasRole(User::ROLE_SUPER_ADMIN) && $this->getUser() !== $user) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'error',
                    $this
                        ->get('translator')
                        ->trans('admin.user.edit.superadmin', [], 'admin')
                )
            ;

            return $this->redirectToRoute('main_admin_user_list');
        }

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

            return $this->redirectToRoute('main_admin_user_list');
        }

        return $this->render(
            'MainBundle:Admin/User:edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific User entity.
     *
     * @Route("/{id}/delete", name="main_admin_user_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function deleteAction(User $user)
    {
        if ($this->getUser() === $user) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'error',
                    $this
                        ->get('translator')
                        ->trans('admin.user.delete.yourself', [], 'admin')
                )
            ;

            return $this->redirectToRoute('main_admin_user_list');
        } elseif ($user->hasRole(User::ROLE_SUPER_ADMIN)) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'error',
                    $this
                        ->get('translator')
                        ->trans('admin.user.delete.superadmin', [], 'admin')
                )
            ;

            return $this->redirectToRoute('main_admin_user_list');
        }

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
                    ->trans('admin.user.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('main_admin_user_list');
    }
}
