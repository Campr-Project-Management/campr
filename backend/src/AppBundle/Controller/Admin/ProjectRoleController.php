<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectRole;
use AppBundle\Form\ProjectRole\CreateType as ProjectRoleCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectRole admin controller.
 *
 * @Route("/admin/project-role")
 */
class ProjectRoleController extends BaseController
{
    /**
     * Lists all ProjectRole entities.
     *
     * @Route("/list", name="app_admin_project_role_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectRoles = $em
            ->getRepository(ProjectRole::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectRole:list.html.twig',
            [
                'project_roles' => $projectRoles,
            ]
        );
    }

    /**
     * Lists all ProjectRole entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_role_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectRole::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectRole entity.
     *
     * @Route("/create", name="app_admin_project_role_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectRole = new ProjectRole();
        $form = $this->createForm(ProjectRoleCreateType::class, $projectRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_role.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_role_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectRole:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectRole entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_role_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param ProjectRole $projectRole
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectRole $projectRole)
    {
        $form = $this->createForm(ProjectRoleCreateType::class, $projectRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectRole->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_role.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_role_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectRole:edit.html.twig',
            [
                'project_role' => $projectRole,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectRole entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_role_show")
     * @Method({"GET"})
     *
     * @param ProjectRole $projectRole
     *
     * @return Response
     */
    public function showAction(ProjectRole $projectRole)
    {
        return $this->render(
            'AppBundle:Admin/ProjectRole:show.html.twig',
            [
                'project_role' => $projectRole,
            ]
        );
    }

    /**
     * Deletes a ProjectRole entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_role_delete")
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param ProjectRole $projectRole
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectRole $projectRole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectRole);
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
                    ->trans('success.project_role.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_role_list');
    }
}
