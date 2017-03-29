<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectCostType;
use AppBundle\Form\ProjectCostType\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-cost-types")
 */
class ProjectCostTypeController extends ApiController
{
    /**
     * Get all project cost types.
     *
     * @Route(name="app_api_project_cost_types_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectCostTypes = $this
            ->getDoctrine()
            ->getRepository(ProjectCostType::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectCostTypes);
    }

    /**
     * Create a new Project Cost Type.
     *
     * @Route(name="app_api_project_cost_types_create")
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
     * Get Project Cost Type by id.
     *
     * @Route("/{id}", name="app_api_project_cost_types_get")
     * @Method({"GET"})
     *
     * @param ProjectCostType $projectCostType
     *
     * @return JsonResponse
     */
    public function getAction(ProjectCostType $projectCostType)
    {
        $project = $projectCostType->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->createApiResponse($projectCostType);
    }

    /**
     * Edit a specific Project Cost Type.
     *
     * @Route("/{id}", name="app_api_project_cost_types_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request         $request
     * @param ProjectCostType $projectCostType
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectCostType $projectCostType)
    {
        $project = $projectCostType->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(CreateType::class, $projectCostType, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $projectCostType->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCostType);
            $em->flush();

            return $this->createApiResponse($projectCostType, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Cost Type.
     *
     * @Route("/{id}", name="app_api_project_cost_types_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectCostType $projectCostType
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectCostType $projectCostType)
    {
        $project = $projectCostType->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCostType);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
