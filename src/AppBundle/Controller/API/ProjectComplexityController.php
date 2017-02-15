<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectComplexity;
use AppBundle\Form\ProjectComplexity\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-complexities")
 */
class ProjectComplexityController extends ApiController
{
    /**
     * Get all project complexities.
     *
     * @Route(name="app_api_project_complexities_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectComplexities = $this
            ->getDoctrine()
            ->getRepository(ProjectComplexity::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectComplexities);
    }

    /**
     * Create a new Project Complexity.
     *
     * @Route(name="app_api_project_complexities_create")
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
     * Get Project Complexity by id.
     *
     * @Route("/{id}", name="app_api_project_complexities_get")
     * @Method({"GET"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function getAction(ProjectComplexity $projectComplexity)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectComplexity->getProject());

        return $this->createApiResponse($projectComplexity);
    }

    /**
     * Edit a specific Project Complexity.
     *
     * @Route("/{id}", name="app_api_project_complexities_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request           $request
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectComplexity $projectComplexity)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectComplexity->getProject());

        $form = $this->createForm(CreateType::class, $projectComplexity, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $projectComplexity->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            return $this->createApiResponse($projectComplexity, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Complexity.
     *
     * @Route("/{id}", name="app_api_project_complexity_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectComplexity $projectComplexity)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectComplexity->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectComplexity);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
