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

/**
 * @Route("/api/project-cost-type")
 */
class ProjectCostTypeController extends ApiController
{
    /**
     * Get all project cost types.
     *
     * @Route("/list", name="app_api_project_cost_type_list")
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
     * @Route("/create", name="app_api_project_cost_type_create")
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
     * Get Project Cost Type by id.
     *
     * @Route("/{id}", name="app_api_project_cost_type_get")
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
     * @Route("/{id}/edit", name="app_api_project_cost_type_edit")
     * @Method({"POST"})
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

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectCostType, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectCostType->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCostType);
            $em->flush();

            return $this->createApiResponse($projectCostType);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Cost Type.
     *
     * @Route("/{id}/delete", name="app_api_project_cost_type_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
