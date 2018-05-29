<?php

namespace AppBundle\Controller\Admin;

use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
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
class UserController extends BaseController
{
    /**
     * List all user entities.
     *
     * @Route("/list", name="app_admin_user_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all User entities filtered and paginated.
     *
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

        return $this->createApiResponse($response);
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
     * Creates a new User entity.
     *
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
                        ->trans('success.user.create', [], 'flashes')
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
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="app_admin_user_edit", options={"expose"=true})
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
                        ->trans('failed.user.edit.superadmin', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_user_list');
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
                        ->trans('success.user.edit', [], 'flashes')
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
     * Deletes a specific User entity.
     *
     * @Route("/{id}/delete", name="app_admin_user_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    public function deleteAction(Request $request, User $user)
    {
        if ($this->getUser() === $user) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'error',
                    $this
                        ->get('translator')
                        ->trans('failed.user.delete.yourself', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_user_list');
        } elseif ($user->hasRole(User::ROLE_SUPER_ADMIN)) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'error',
                    $this
                        ->get('translator')
                        ->trans('failed.user.delete.superadmin', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_user_list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
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
                    ->trans('success.user.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_user_list');
    }
}
