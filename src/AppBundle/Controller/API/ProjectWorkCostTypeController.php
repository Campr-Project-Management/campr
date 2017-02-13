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

/**
 * @Route("/api/project-work-cost-type")
 */
class ProjectWorkCostTypeController extends ApiController
{
    /**
     * Get all project work cost types.
     *
     * @Route("/list", name="app_api_project_work_cost_type_team_list")
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
     * @Route("/create", name="app_api_project_work_cost_type_create")
     *
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
     * Get Project Work Cost Type by id.
     *
     * @Route("/{id}", name="app_api_project_work_cost_type_get")
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
     * @Route("/{id}/edit", name="app_api_project_work_cost_type_edit")
     * @Method({"POST"})
     *
     * @param Request             $request
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectWorkCostType $projectWorkCostType)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectWorkCostType->getProject());

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectWorkCostType, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectWorkCostType->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectWorkCostType);
            $em->flush();

            return $this->createApiResponse($projectWorkCostType);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Work Cost Type.
     *
     * @Route("/{id}/delete", name="app_api_project_work_cost_type_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
