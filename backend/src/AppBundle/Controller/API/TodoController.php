<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Todo;
use AppBundle\Form\Todo\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/todos")
 */
class TodoController extends ApiController
{
    /**
     * Retrieve todo information.
     *
     * @Route("/{id}", name="app_api_todos_get")
     * @Method({"GET"})
     *
     * @param Todo $todo
     *
     * @return JsonResponse
     */
    public function getAction(Todo $todo)
    {
        $project = $todo->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        return $this->createApiResponse($todo);
    }

    /**
     * Edit a specific Todo.
     *
     * @Route("/{id}", name="app_api_todos_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Todo    $todo
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Todo $todo)
    {
        $project = $todo->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(CreateType::class, $todo, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->createApiResponse($todo, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Todo.
     *
     * @Route("/{id}", name="app_api_todos_delete")
     * @Method({"DELETE"})
     *
     * @param Todo $todo
     *
     * @return JsonResponse
     */
    public function deleteAction(Todo $todo)
    {
        $project = $todo->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
