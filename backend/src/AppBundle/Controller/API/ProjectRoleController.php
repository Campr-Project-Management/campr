<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectRole;
use AppBundle\Form\ProjectRole\CreateType;
use AppBundle\Security\AdminVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-roles")
 */
class ProjectRoleController extends ApiController
{
    /**
     * Get all project roles.
     *
     * @Route(name="app_api_project_roles_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectRoles = $this
            ->getDoctrine()
            ->getRepository(ProjectRole::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectRoles);
    }

    /**
     * Create a new Project Role.
     *
     * @Route(name="app_api_project_roles_create")
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
     * Get Project Role by id.
     *
     * @Route("/{id}", name="app_api_project_roles_get")
     * @Method({"GET"})
     *
     * @param ProjectRole $projectRole
     *
     * @return JsonResponse
     */
    public function getAction(ProjectRole $projectRole)
    {
        $this->denyAccessUnlessGranted(AdminVoter::VIEW, $projectRole);

        return $this->createApiResponse($projectRole);
    }

    /**
     * Edit a specific Project Role.
     *
     * @Route("/{id}", name="app_api_project_roles_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request     $request
     * @param ProjectRole $projectRole
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectRole $projectRole)
    {
        $this->denyAccessUnlessGranted(AdminVoter::EDIT, $projectRole);

        $form = $this->createForm(CreateType::class, $projectRole, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $projectRole->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();

            return $this->createApiResponse($projectRole, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Role.
     *
     * @Route("/{id}", name="app_api_project_role_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectRole $projectRole
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectRole $projectRole)
    {
        $this->denyAccessUnlessGranted(AdminVoter::DELETE, $projectRole);

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectRole);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
