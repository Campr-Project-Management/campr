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
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $notes = $this
            ->getDoctrine()
            ->getRepository(Note::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($notes);
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
        if ($project = $note->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $note, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->createApiResponse($note);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Note.
     *
     * @Route("/{id}/delete", name="app_api_note_delete")
     * @Method({"GET"})
     *
     * @param Note $note
     *
     * @return JsonResponse
     */
    public function deleteAction(Note $note)
    {
        if ($project = $note->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($note);
        $em->flush();

        return $this->createApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
