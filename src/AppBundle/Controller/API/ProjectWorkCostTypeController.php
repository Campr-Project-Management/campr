<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectWorkCostType;
use AppBundle\Form\ProjectWorkCostType\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-work-cost-types")
 */
class ProjectWorkCostTypeController extends ApiController
{
    /**
     * Get all project work cost types.
     *
     * @Route(name="app_api_project_work_cost_types_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectWorkCostType = $this
            ->getDoctrine()
            ->getRepository(ProjectWorkCostType::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectWorkCostType);
    }

    /**
     * Create a new Project Work Cost Type.
     *
     * @Route(name="app_api_project_work_cost_types_create")
     *
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
     * Get Project Work Cost Type by id.
     *
     * @Route("/{id}", name="app_api_project_work_cost_types_get")
     * @Method({"GET"})
     *
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return JsonResponse
     */
    public function getAction(ProjectWorkCostType $projectWorkCostType)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectWorkCostType->getProject());

        return $this->createApiResponse($projectWorkCostType);
    }

    /**
     * Edit a specific Project Work Cost Type.
     *
     * @Route("/{id}", name="app_api_project_work_cost_types_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request             $request
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectWorkCostType $projectWorkCostType)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectWorkCostType->getProject());

        $form = $this->createForm(CreateType::class, $projectWorkCostType, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectWorkCostType);
            $em->flush();

            return $this->createApiResponse($projectWorkCostType, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Work Cost Type.
     *
     * @Route("/{id}", name="app_api_project_work_cost_types_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectWorkCostType $projectWorkCostType)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectWorkCostType->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectWorkCostType);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
