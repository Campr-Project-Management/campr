<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectDepartment;
use AppBundle\Form\ProjectDepartment\CreateType;
use AppBundle\Security\AdminVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-departments")
 */
class ProjectDepartmentController extends ApiController
{
    /**
     * Get all project departments.
     *
     * @Route(name="app_api_project_departments_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectDepartments = $this
            ->getDoctrine()
            ->getRepository(ProjectDepartment::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectDepartments);
    }

    /**
     * Create a new Project Department.
     *
     * @Route(name="app_api_project_departments_create")
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
     * Get Project Department by id.
     *
     * @Route("/{id}", name="app_api_project_departments_get")
     * @Method({"GET"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function getAction(ProjectDepartment $projectDepartment)
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $projectDepartment);

        return $this->createApiResponse($projectDepartment);
    }

    /**
     * Edit a specific Project Department.
     *
     * @Route("/{id}", name="app_api_project_departments_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request           $request
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectDepartment $projectDepartment)
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $projectDepartment);

        $form = $this->createForm(CreateType::class, $projectDepartment, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $projectDepartment->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDepartment);
            $em->flush();

            return $this->createApiResponse($projectDepartment, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Department.
     *
     * @Route("/{id}", name="app_api_project_departments_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectDepartment $projectDepartment)
    {
        $this->denyAccessUnlessGranted(AdminVoter::DELETE, $projectDepartment);

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectDepartment);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
