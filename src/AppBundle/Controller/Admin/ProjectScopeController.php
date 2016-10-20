<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectScope;
use AppBundle\Form\ProjectScope\CreateType as ProjectScopeCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectScope controller.
 *
 * @Route("/admin/project-scope")
 */
class ProjectScopeController extends Controller
{
    /**
     * Lists all ProjectScope entities.
     *
     * @Route("/list", name="app_admin_project_scope_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectScopes = $em
            ->getRepository(ProjectScope::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectScope:list.html.twig',
            [
                'project_scopes' => $projectScopes,
            ]
        );
    }

    /**
     * Creates a new ProjectScope entity.
     *
     * @Route("/create", name="app_admin_project_scope_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectScope = new ProjectScope();
        $form = $this->createForm(ProjectScopeCreateType::class, $projectScope);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectScope);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_scope.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_scope_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectScope:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectScope entity.
     *
     * @Route("/{id}/edit", name="app_admin_project_scope_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request      $request
     * @param ProjectScope $projectScope
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectScope $projectScope)
    {
        $form = $this->createForm(ProjectScopeCreateType::class, $projectScope);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectScope->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectScope);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_scope.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_scope_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectScope:edit.html.twig',
            [
                'project_scope' => $projectScope,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectScope entity.
     *
     * @Route("/{id}/show", name="app_admin_project_scope_show")
     * @Method({"GET"})
     *
     * @param ProjectScope $projectScope
     *
     * @return Response
     */
    public function showAction(ProjectScope $projectScope)
    {
        return $this->render(
            'AppBundle:Admin/ProjectScope:show.html.twig',
            [
                'project_scope' => $projectScope,
            ]
        );
    }

    /**
     * Deletes a ProjectScope entity.
     *
     * @Route("/{id}/delete", name="app_admin_project_scope_delete")
     * @Method({"GET"})
     *
     * @param ProjectScope $projectScope
     *
     * @return RedirectResponse
     */
    public function deleteAction(ProjectScope $projectScope)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectScope);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.project_scope.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_scope_list');
    }
}
