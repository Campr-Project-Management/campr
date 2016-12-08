<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Form\ProjectTeam\CreateType as ProjectTeamCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectTeam admin controller.
 *
 * @Route("/admin/project-team")
 */
class ProjectTeamController extends Controller
{
    /**
     * Lists all ProjectTeam entities.
     *
     * @Route("/list", name="app_admin_project_team_list")
     * @Method("GET")
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectTeams = $em
            ->getRepository(ProjectTeam::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectTeam:list.html.twig',
            [
                'project_teams' => $projectTeams,
            ]
        );
    }

    /**
     * Lists all ProjectTeam- entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_team_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectTeam::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new ProjectTeam entity.
     *
     * @Route("/create", name="app_admin_project_team_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectTeam = new ProjectTeam();
        $form = $this->createForm(ProjectTeamCreateType::class, $projectTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTeam);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_team.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_team_list');
        }

        return $this->render('AppBundle:Admin/ProjectTeam:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectTeam entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_team_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param ProjectTeam $projectTeam
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectTeam $projectTeam)
    {
        $form = $this->createForm(ProjectTeamCreateType::class, $projectTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectTeam->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTeam);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.project_team.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_project_team_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectTeam:edit.html.twig',
            [
                'project_team' => $projectTeam,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectTeam entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_team_show")
     * @Method({"GET"})
     *
     * @param ProjectTeam $projectTeam
     *
     * @return Response
     */
    public function showAction(ProjectTeam $projectTeam)
    {
        return $this->render(
            'AppBundle:Admin/ProjectTeam:show.html.twig',
            [
                'project_team' => $projectTeam,
            ]
        );
    }

    /**
     * Deletes a ProjectTeam entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_team_delete")
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param ProjectTeam $projectTeam
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectTeam $projectTeam)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectTeam);
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
                    ->trans('admin.project_team.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_team_list');
    }
}
