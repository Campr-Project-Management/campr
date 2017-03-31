<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectLimitation;
use AppBundle\Form\ProjectLimitation\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-limitations")
 */
class ProjectLimitationController extends ApiController
{
    /**
     * Retrieve ProjectLimitation information.
     *
     * @Route("/{id}", name="app_api_project_limitation_get")
     * @Method({"GET"})
     *
     * @param ProjectLimitation $projectLimitation
     *
     * @return JsonResponse
     */
    public function getAction(ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectLimitation->getProject());

        return $this->createApiResponse($projectLimitation);
    }

    /**
     * Edit a specific ProjectLimitation.
     *
     * @Route("/{id}", name="app_api_project_limitation_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request           $request
     * @param ProjectLimitation $projectLimitation
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectLimitation->getProject());

        $form = $this->createForm(CreateType::class, $projectLimitation, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectLimitation);
            $em->flush();

            return $this->createApiResponse($projectLimitation, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific ProjectLimitation.
     *
     * @Route("/{id}", name="app_api_project_limitation_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectLimitation $projectLimitation
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectLimitation->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectLimitation);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
