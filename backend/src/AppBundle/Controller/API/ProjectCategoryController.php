<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectCategory;
use AppBundle\Form\ProjectCategory\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-categories")
 */
class ProjectCategoryController extends ApiController
{
    /**
     * Get all project categories.
     *
     * @Route(name="app_api_project_categories_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectCategories = $this
            ->getDoctrine()
            ->getRepository(ProjectCategory::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectCategories);
    }

    /**
     * Create a new Project Category.
     *
     * @Route(name="app_api_project_categories_create")
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
     * Get Project Category by id.
     *
     * @Route("/{id}", name="app_api_project_categories_get")
     * @Method({"GET"})
     *
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function getAction(ProjectCategory $projectCategory)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectCategory->getProject());

        return $this->createApiResponse($projectCategory);
    }

    /**
     * Edit a specific Project Category.
     *
     * @Route("/{id}", name="app_api_project_categories_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request         $request
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectCategory $projectCategory)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectCategory->getProject());

        $form = $this->createForm(CreateType::class, $projectCategory, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCategory);
            $em->flush();

            return $this->createApiResponse($projectCategory, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Category.
     *
     * @Route("/{id}", name="app_api_project_categories_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectCategory $projectCategory)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectCategory->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCategory);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
