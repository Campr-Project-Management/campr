<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectScope;
use AppBundle\Form\ProjectScope\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-scope")
 */
class ProjectScopeController extends ApiController
{
    /**
     * Get all project scopes.
     *
     * @Route("/list", name="app_api_project_scope_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectScopes = $this
            ->getDoctrine()
            ->getRepository(ProjectScope::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectScopes);
    }

    /**
     * Create a new Project Scope.
     *
     * @Route("/create", name="app_api_project_scope_create")
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
     * Get Project Scope by id.
     *
     * @Route("/{id}", name="app_api_project_scope_get")
     * @Method({"GET"})
     *
     * @param ProjectScope $projectScope
     *
     * @return JsonResponse
     */
    public function getAction(ProjectScope $projectScope)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectScope->getProject());

        return $this->createApiResponse($projectScope);
    }

    /**
     * Edit a specific Project Scope.
     *
     * @Route("/{id}/edit", name="app_api_project_scope_edit")
     * @Method({"POST"})
     *
     * @param Request      $request
     * @param ProjectScope $projectScope
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectScope $projectScope)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectScope->getProject());

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectScope, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectScope->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectScope);
            $em->flush();

            return $this->createApiResponse($projectScope);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Scope.
     *
     * @Route("/{id}/delete", name="app_api_project_scope_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectScope $projectScope
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectScope $projectScope)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectScope->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectScope);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
