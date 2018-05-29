<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectModule;
use AppBundle\Form\ProjectModule\CreateType as ProjectModuleCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectModule admin controller.
 *
 * @Route("/admin/project-module")
 */
class ProjectModuleController extends BaseController
{
    /**
     * Lists all ProjectModule entities.
     *
     * @Route("/list", name="app_admin_project_module_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectModules = $em
            ->getRepository(ProjectModule::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectModule:list.html.twig',
            [
                'project_modules' => $projectModules,
            ]
        );
    }

    /**
     * Lists all ProjectModule entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_module_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectModule::class, 'module', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectModule entity.
     *
     * @Route("/create", name="app_admin_project_module_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectModule = new ProjectModule();
        $form = $this->createForm(ProjectModuleCreateType::class, $projectModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectModule);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_module.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_module_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectModule:create.html.twig',
            [
                'projectModule' => $projectModule,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectModule entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_module_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request       $request
     * @param ProjectModule $projectModule
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectModule $projectModule)
    {
        $form = $this->createForm(ProjectModuleCreateType::class, $projectModule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectModule->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectModule);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_module.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_module_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectModule:edit.html.twig',
            [
                'project_module' => $projectModule,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectModule entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_module_show")
     * @Method({"GET"})
     *
     * @param ProjectModule $projectModule
     *
     * @return Response
     */
    public function showAction(ProjectModule $projectModule)
    {
        return $this->render(
            'AppBundle:Admin/ProjectModule:show.html.twig',
            [
                'project_module' => $projectModule,
            ]
        );
    }

    /**
     * Deletes a ProjectModule entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_module_delete")
     * @Method({"GET"})
     *
     * @param Request       $request
     * @param ProjectModule $projectModule
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectModule $projectModule)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectModule);
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
                    ->trans('success.project_module.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_module_list');
    }
}
