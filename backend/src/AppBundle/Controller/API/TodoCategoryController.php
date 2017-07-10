<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\TodoCategory;
use AppBundle\Form\TodoCategory\BaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/todo-categories")
 */
class TodoCategoryController extends ApiController
{
    const ENTITY_CLASS = TodoCategory::class;
    const FORM_CLASS = BaseType::class;
    /**
     * Get the todos categories.
     *
     * @Route(name="app_api_todo_categories_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }

    /**
     * Create Todo Category.
     *
     * @Route(name="app_api_todo_categories_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm(null, ['method' => $request->getMethod()]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $this->persistAndFlush($form->getData());

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Todo Category by id.
     *
     * @Route("/{id}", name="app_api_todo_categories_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param TodoCategory $todoCategory
     *
     * @return JsonResponse
     */
    public function getAction(TodoCategory $todoCategory)
    {
        return $this->createApiResponse($todoCategory);
    }

    /**
     * edit Todo Category.
     *
     * @Route("/{id}", name="app_api_todo_categories_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request      $request
     * @param TodoCategory $todoCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, TodoCategory $todoCategory)
    {
        $form = $this->getForm($todoCategory, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($todoCategory);

            return $this->createApiResponse($todoCategory, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete Todo  Category.
     *
     * @Route("/{id}", name="app_api_todo_categories_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param TodoCategory $todoCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(TodoCategory $todoCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($todoCategory);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
