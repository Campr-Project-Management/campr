<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectScope;
use AppBundle\Form\ProjectScope\CreateType as ProjectScopeCreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * ProjectScope admin controller.
 *
 * @Route("/admin/project-scope")
 */
class ProjectScopeController extends BaseController
{
    /**
     * Lists all ProjectScope entities.
     *
     * @Route("/list", name="app_admin_project_scope_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all ProjectScope entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_scope_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectScope::class, 'name', $requestParams);

        return $this->createApiResponse($response);
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
                        ->trans('success.project_scope.create', [], 'flashes')
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
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_scope_edit")
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
                        ->trans('success.project_scope.edit', [], 'flashes')
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
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_scope_show")
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
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_scope_delete")
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param ProjectScope $projectScope
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectScope $projectScope)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projectScope);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.project_scope.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project_scope.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project_scope.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                $flashKey,
                $flashMessage
            )
        ;

        return $this->redirectToRoute('app_admin_project_scope_list');
    }
}
