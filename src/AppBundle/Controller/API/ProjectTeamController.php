<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectTeam;
use AppBundle\Form\ProjectTeam\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-team")
 */
class ProjectTeamController extends ApiController
{
    /**
     * Get all project teams.
     *
     * @Route("/list", name="app_api_project_team_list")
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
     * @Route("/create", name="app_api_project_team_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), JsonResponse::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Get Project Team by id.
     *
     * @Route("/{id}", name="app_api_project_team_get")
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
     * @Route("/{id}/edit", name="app_api_project_team_edit")
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param ProjectTeam $projectTeam
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectTeam $projectTeam)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectTeam, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectTeam->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTeam);
            $em->flush();

            return $this->createApiResponse($projectTeam);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Team.
     *
     * @Route("/{id}/delete", name="app_api_project_team_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
