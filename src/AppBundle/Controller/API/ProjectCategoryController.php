<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectCategory;
use AppBundle\Form\ProjectCategory\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-category")
 */
class ProjectCategoryController extends ApiController
{
    /**
     * Get all project categories.
     *
     * @Route("/list", name="app_api_project_category_list")
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
     * @Route("/create", name="app_api_project_category_create")
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
     * Get Project Category by id.
     *
     * @Route("/{id}", name="app_api_project_category_get")
     * @Method({"GET"})
     *
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function getAction(ProjectCategory $projectCategory)
    {
        return $this->createApiResponse($projectCategory);
    }

    /**
     * Edit a specific Project Category.
     *
     * @Route("/{id}/edit", name="app_api_project_category_edit")
     * @Method({"POST"})
     *
     * @param Request         $request
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectCategory $projectCategory)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectCategory, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectCategory->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCategory);
            $em->flush();

            return $this->createApiResponse($projectCategory);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Category.
     *
     * @Route("/{id}/delete", name="app_api_project_category_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectCategory $projectCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectCategory $projectCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCategory);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
