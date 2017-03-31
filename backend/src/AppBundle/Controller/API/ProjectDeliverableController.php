<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectDeliverable;
use AppBundle\Form\ProjectDeliverable\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-deliverables")
 */
class ProjectDeliverableController extends ApiController
{
    /**
     * Retrieve ProjectDeliverable information.
     *
     * @Route("/{id}", name="app_api_project_deliverable_get")
     * @Method({"GET"})
     *
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return JsonResponse
     */
    public function getAction(ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectDeliverable->getProject());

        return $this->createApiResponse($projectDeliverable);
    }

    /**
     * Edit a specific ProjectDeliverable.
     *
     * @Route("/{id}", name="app_api_project_deliverable_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request            $request
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectDeliverable->getProject());

        $form = $this->createForm(CreateType::class, $projectDeliverable, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDeliverable);
            $em->flush();

            return $this->createApiResponse($projectDeliverable, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific ProjectDeliverable.
     *
     * @Route("/{id}", name="app_api_project_deliverable_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectDeliverable $projectDeliverable)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectDeliverable->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectDeliverable);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
