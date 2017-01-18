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
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
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
        if ($project = $todo->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $todo, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->createApiResponse($todo);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Todo.
     *
     * @Route("/{id}/delete", name="app_api_todo_delete")
     * @Method({"GET"})
     *
     * @param Todo $todo
     *
     * @return JsonResponse
     */
    public function deleteAction(Todo $todo)
    {
        if ($project = $todo->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();

        return $this->createApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
