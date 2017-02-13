<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\Note;
use AppBundle\Form\Note\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/note")
 */
class NoteController extends ApiController
{
    /**
     * All notes for the current project.
     *
     * @Route("/{id}/list", name="app_api_note_list")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        return $this->createApiResponse($project->getNotes());
    }

    /**
     * Retrieve Note information.
     *
     * @Route("/{id}", name="app_api_note_get")
     * @Method({"GET"})
     *
     * @param Note $note
     *
     * @return JsonResponse
     */
    public function getAction(Note $note)
    {
        $project = $note->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->createApiResponse($note);
    }

    /**
     * Create a new Note.
     *
     * @Route("/create", name="app_api_note_create")
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
     * Edit a specific Note.
     *
     * @Route("/{id}/edit", name="app_api_note_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param Note    $note
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Note $note)
    {
        $project = $note->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(CreateType::class, $note, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->createApiResponse($note, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Note.
     *
     * @Route("/{id}/delete", name="app_api_note_delete")
     * @Method({"DELETE"})
     *
     * @param Note $note
     *
     * @return JsonResponse
     */
    public function deleteAction(Note $note)
    {
        $project = $note->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($note);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
