<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectDepartment;
use AppBundle\Form\ProjectDepartment\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-department")
 */
class ProjectDepartmentController extends ApiController
{
    /**
     * Get all project departments.
     *
     * @Route("/list", name="app_api_project_department_list")
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
     * @Route("/create", name="app_api_project_department_create")
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
     * Get Project Department by id.
     *
     * @Route("/{id}", name="app_api_project_department_get")
     * @Method({"GET"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function getAction(ProjectDepartment $projectDepartment)
    {
        return $this->createApiResponse($projectDepartment);
    }

    /**
     * Edit a specific Project Department.
     *
     * @Route("/{id}/edit", name="app_api_project_department_edit")
     * @Method({"POST"})
     *
     * @param Request           $request
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectDepartment $projectDepartment)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectDepartment, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectDepartment->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDepartment);
            $em->flush();

            return $this->createApiResponse($projectDepartment);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Department.
     *
     * @Route("/{id}/delete", name="app_api_project_department_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectDepartment $projectDepartment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectDepartment);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
