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

/**
 * @Route("/api/project-role")
 */
class ProjectRoleController extends ApiController
{
    /**
     * Get all project roles.
     *
     * @Route("/list", name="app_api_project_role_list")
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
     * @Route("/create", name="app_api_project_role_create")
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
     * Get Project Role by id.
     *
     * @Route("/{id}", name="app_api_project_role_get")
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
     * @Route("/{id}/edit", name="app_api_project_role_edit")
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param ProjectRole $projectRole
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectRole $projectRole)
    {
        $this->denyAccessUnlessGranted(AdminVoter::EDIT, $projectRole);

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectRole, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectRole->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectRole);
            $em->flush();

            return $this->createApiResponse($projectRole);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Role.
     *
     * @Route("/{id}/delete", name="app_api_project_role_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
