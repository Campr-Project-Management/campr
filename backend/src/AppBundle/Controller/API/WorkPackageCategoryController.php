<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackageCategory;
use AppBundle\Form\WorkPackageCategory\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/workpackage-categories")
 */
class WorkPackageCategoryController extends ApiController
{
    /**
     * Get all workpackage categories.
     *
     * @Route(name="app_api_workpackage_categories_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $workpackageCategories = $this
            ->getDoctrine()
            ->getRepository(WorkPackageCategory::class)
            ->findAll()
        ;

        return $this->createApiResponse($workpackageCategories);
    }

    /**
     * Create a new WorkPackageCategory.
     *
     * @Route(name="app_api_workpackage_categories_create")
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
     * Get WorkPackageCategory by id.
     *
     * @Route("/{id}", name="app_api_workpackage_categories_get")
     * @Method({"GET"})
     *
     * @param WorkPackageCategory $workPackageCategory
     *
     * @return JsonResponse
     */
    public function getAction(WorkPackageCategory $workPackageCategory)
    {
        return $this->createApiResponse($workPackageCategory);
    }

    /**
     * Edit a specific WorkPackageCategory.
     *
     * @Route("/{id}", name="app_api_workpackage_categories_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request             $request
     * @param WorkPackageCategory $workPackageCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackageCategory $workPackageCategory)
    {
        $form = $this->createForm(CreateType::class, $workPackageCategory, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workPackageCategory);
            $em->flush();

            return $this->createApiResponse($workPackageCategory, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific WorkPackageCategory.
     *
     * @Route("/{id}", name="app_api_workpackage_categories_delete")
     * @Method({"DELETE"})
     *
     * @param WorkPackageCategory $workPackageCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(WorkPackageCategory $workPackageCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPackageCategory);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
