<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectUser;
use AppBundle\Form\ProjectUser\CreateType as ProjectUserCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectUser admin controller.
 *
 * @Route("/admin/project-user")
 */
class ProjectUserController extends Controller
{
    /**
     * Lists all ProjectUser entities.
     *
     * @Route("/list", name="app_admin_project_user_list")
     * @Method("GET")
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectUsers = $em
            ->getRepository(ProjectUser::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectUser:list.html.twig',
            [
                'project_users' => $projectUsers,
            ]
        );
    }

    /**
     * Lists all ProjectUser entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_user_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectUser::class, 'username', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectUser entity.
     *
     * @Route("/create", name="app_admin_project_user_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectUser = new ProjectUser();
        $form = $this->createForm(ProjectUserCreateType::class, $projectUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectUser);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_user.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_user_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectUser:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectUser entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_user_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param ProjectUser $projectUser
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectUser $projectUser)
    {
        $form = $this->createForm(ProjectUserCreateType::class, $projectUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectUser->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectUser);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_user.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_user_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectUser:edit.html.twig',
            [
                'project_user' => $projectUser,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectUser entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_user_show")
     * @Method({"GET"})
     *
     * @param ProjectUser $projectUser
     *
     * @return Response
     */
    public function showAction(ProjectUser $projectUser)
    {
        return $this->render(
            'AppBundle:Admin/ProjectUser:show.html.twig',
            [
                'project_user' => $projectUser,
            ]
        );
    }

    /**
     * Deletes a ProjectUser entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_user_delete")
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param ProjectUser $projectUser
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectUser $projectUser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectUser);
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
                    ->trans('admin.project_user.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_user_list');
    }
}
