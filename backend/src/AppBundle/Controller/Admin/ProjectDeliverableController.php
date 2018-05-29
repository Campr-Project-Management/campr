<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ProjectDeliverable;
use AppBundle\Security\ProjectVoter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ProjectDeliverable\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectDeliverable admin controller.
 *
 * @Route("/admin/project-deliverable")
 */
class ProjectDeliverableController extends BaseController
{
    /**
     * Lists all ProjectDeliverable entities.
     *
     * @Route("/list", name="app_admin_project_deliverable_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectDeliverables = $em
            ->getRepository(ProjectDeliverable::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectDeliverable:list.html.twig',
            [
                'projectDeliverables' => $projectDeliverables,
            ]
        );
    }

    /**
     * Lists all ProjectDeliverable entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_deliverable_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listFilteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(ProjectDeliverable::class, 'description', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectDeliverable entity.
     *
     * @Route("/create", name="app_admin_project_deliverable_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_deliverable.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_deliverable_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectDeliverable:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectDeliverable entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_deliverable_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request            $request
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectDeliverable->getProject());
        $form = $this->createForm(CreateType::class, $projectDeliverable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDeliverable);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_deliverable.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_project_deliverable_show',
                [
                    'id' => $projectDeliverable->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Admin/ProjectDeliverable:edit.html.twig',
            [
                'id' => $projectDeliverable->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a ProjectDeliverable entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_deliverable_show")
     * @Method({"GET"})
     *
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return Response
     */
    public function showAction(ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectDeliverable->getProject());

        return $this->render(
            'AppBundle:Admin/ProjectDeliverable:show.html.twig',
            [
                'projectDeliverable' => $projectDeliverable,
            ]
        );
    }

    /**
     * Deletes a ProjectDeliverable entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_deliverable_delete")
     * @Method({"GET"})
     *
     * @param Request            $request
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectDeliverable->getProject());
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectDeliverable);
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
                    ->trans('success.project_deliverable.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_deliverable_list');
    }
}
