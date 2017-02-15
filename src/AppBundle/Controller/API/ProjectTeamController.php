<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectTeam;
use AppBundle\Form\ProjectTeam\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-teams")
 */
class ProjectTeamController extends ApiController
{
    /**
     * Get all project teams.
     *
     * @Route(name="app_api_project_teams_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectTeams = $this
            ->getDoctrine()
            ->getRepository(ProjectTeam::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectTeams);
    }

    /**
     * Create a new Project Team.
     *
     * @Route(name="app_api_project_teams_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Project Team by id.
     *
     * @Route("/{id}", name="app_api_project_teams_get")
     * @Method({"GET"})
     *
     * @param ProjectTeam $projectTeam
     *
     * @return JsonResponse
     */
    public function getAction(ProjectTeam $projectTeam)
    {
        return $this->createApiResponse($projectTeam);
    }

    /**
     * Edit a specific Project Team.
     *
     * @Route("/{id}", name="app_api_project_teams_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request     $request
     * @param ProjectTeam $projectTeam
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectTeam $projectTeam)
    {
        $form = $this->createForm(CreateType::class, $projectTeam, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTeam);
            $em->flush();

            return $this->createApiResponse($projectTeam, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Team.
     *
     * @Route("/{id}", name="app_api_project_team_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectTeam $projectTeam
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectTeam $projectTeam)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectTeam);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
