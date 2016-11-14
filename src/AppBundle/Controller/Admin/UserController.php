<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\User\CreateType;
use AppBundle\Form\User\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="app_admin_user_list")
     * @Method({"GET"})
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
            'AppBundle:Admin/User:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_user_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(User::class, 'username', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays User entity.
     *
     * @Route("/{id}/show", name="app_admin_user_show", options={"expose"=true})
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
     * @Route("/{id}/edit", name="app_admin_user_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response|RedirectResponse
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
     * @Route("/{id}/delete", name="app_admin_user_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
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
                    ->trans('admin.user.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_user_list');
    }
}
