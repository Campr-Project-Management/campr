<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectObjective;
use AppBundle\Form\ProjectObjective\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-objectives")
 */
class ProjectObjectiveController extends ApiController
{
    /**
     * Retrieve ProjectObjective information.
     *
     * @Route("/{id}", name="app_api_project_objective_get")
     * @Method({"GET"})
     *
     * @param ProjectObjective $projectObjective
     *
     * @return JsonResponse
     */
    public function getAction(ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectObjective->getProject());

        return $this->createApiResponse($projectObjective);
    }

    /**
     * Edit a specific ProjectObjective.
     *
     * @Route("/{id}", name="app_api_project_objective_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request          $request
     * @param ProjectObjective $projectObjective
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectObjective->getProject());

        $form = $this->createForm(CreateType::class, $projectObjective, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectObjective);
            $em->flush();

            return $this->createApiResponse($projectObjective, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific ProjectObjective.
     *
     * @Route("/{id}", name="app_api_project_objective_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectObjective $projectObjective
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectObjective $projectObjective)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectObjective->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectObjective);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
