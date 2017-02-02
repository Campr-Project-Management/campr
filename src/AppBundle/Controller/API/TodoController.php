<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Project;
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
 * @Route("/api/todo")
 */
class TodoController extends ApiController
{
    /**
     * All Todos for the current project.
     *
     * @Route("/{id}/list", name="app_api_todo_list")
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $todos = $this
            ->getDoctrine()
            ->getRepository(Todo::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($todos);
    }

    /**
     * Retrieve todo information.
     *
     * @Route("/{id}", name="app_api_todo_get")
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
     * Create a new Todo.
     *
     * @Route("/create", name="app_api_todo_create")
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
     * Edit a specific Todo.
     *
     * @Route("/{id}/edit", name="app_api_todo_edit")
     * @Method({"PATCH"})
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
        $this->processForm($request, $form, false);

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
     * @Route("/{id}/delete", name="app_api_todo_delete")
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
