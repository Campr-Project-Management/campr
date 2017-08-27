<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectDepartment;
use AppBundle\Form\ProjectDepartment\BaseType;
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
     * Get Project Department by id.
     *
     * @Route("/{id}", name="app_api_project_departments_get", options={"expose"=true})
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
     * @Route("/{id}", name="app_api_project_departments_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request           $request
     * @param ProjectDepartment $projectDepartment
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectDepartment $projectDepartment)
    {
        $form = $this->createForm(BaseType::class, $projectDepartment, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $this->persistAndFlush($projectDepartment);

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
     * @Route("/{id}", name="app_api_project_departments_delete", options={"expose"=true})
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

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
