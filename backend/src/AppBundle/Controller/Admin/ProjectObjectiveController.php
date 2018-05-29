<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ProjectObjective;
use AppBundle\Security\ProjectVoter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ProjectObjective\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectObjective admin controller.
 *
 * @Route("/admin/project-objective")
 */
class ProjectObjectiveController extends BaseController
{
    /**
     * Lists all Contract entities.
     *
     * @Route("/list", name="app_admin_project_objective_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectObjectives = $em
            ->getRepository(ProjectObjective::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectObjective:list.html.twig',
            [
                'projectObjectives' => $projectObjectives,
            ]
        );
    }

    /**
     * Lists all ProjectObjective entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_objective_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectObjective::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectObjective entity.
     *
     * @Route("/create", name="app_admin_project_objective_create")
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
                        ->trans('success.project_objective.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_objective_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectObjective:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectObjective entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_objective_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param ProjectObjective $projectObjective
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectObjective->getProject());
        $form = $this->createForm(CreateType::class, $projectObjective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectObjective);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_objective.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_project_objective_show',
                [
                    'id' => $projectObjective->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Admin/ProjectObjective:edit.html.twig',
            [
                'id' => $projectObjective->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a ProjectObjective entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_objective_show")
     * @Method({"GET"})
     *
     * @param ProjectObjective $projectObjective
     *
     * @return Response
     */
    public function showAction(ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectObjective->getProject());

        return $this->render(
            'AppBundle:Admin/ProjectObjective:show.html.twig',
            [
                'projectObjective' => $projectObjective,
            ]
        );
    }

    /**
     * Deletes a ProjectObjective entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_objective_delete")
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param ProjectObjective $projectObjective
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectObjective->getProject());
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectObjective);
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
                    ->trans('success.project_objective.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_objective_list');
    }
}
