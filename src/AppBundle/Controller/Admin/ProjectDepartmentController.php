<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Form\ProjectDepartment\CreateType as ProjectDepartmentCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectDepartment controller.
 *
 * @Route("/admin/project-department")
 */
class ProjectDepartmentController extends Controller
{
    /**
     * Lists all ProjectDepartment entities.
     *
     * @Route("/list", name="app_admin_project_department_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectDepartments = $em
            ->getRepository(ProjectDepartment::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectDepartment:list.html.twig',
            [
                'project_departments' => $projectDepartments,
            ]
        );
    }

    /**
     * Creates a new ProjectDepartment entity.
     *
     * @Route("/create", name="app_admin_project_department_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectDepartment = new ProjectDepartment();
        $form = $this->createForm(ProjectDepartmentCreateType::class, $projectDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDepartment);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_department.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_department_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectDepartment:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectDepartment entity.
     *
     * @Route("/{id}/edit", name="app_admin_project_department_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param ProjectDepartment $projectDepartment
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectDepartment $projectDepartment)
    {
        $form = $this->createForm(ProjectDepartmentCreateType::class, $projectDepartment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectDepartment->setUpdatedAt(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDepartment);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_department.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_department_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectDepartment:edit.html.twig',
            [
                'project_department' => $projectDepartment,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectDepartment entity.
     *
     * @Route("/{id}/show", name="app_admin_project_department_show")
     * @Method({"GET"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return Response
     */
    public function showAction(ProjectDepartment $projectDepartment)
    {
        return $this->render(
            'AppBundle:Admin/ProjectDepartment:show.html.twig',
            [
                'project_department' => $projectDepartment,
            ]
        );
    }

    /**
     * Deletes a ProjectDepartment entity.
     *
     * @Route("/{id}/delete", name="app_admin_project_department_delete")
     * @Method({"GET"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return RedirectResponse
     */
    public function deleteAction(ProjectDepartment $projectDepartment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectDepartment);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.project_department.delete.success', [], 'admin')
            )
        ;
        
        return $this->redirectToRoute('app_admin_project_department_list');
    }
}
