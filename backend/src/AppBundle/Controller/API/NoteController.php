<?php

namespace AppBundle\Controller\API;

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
 * @Route("/api/notes")
 */
class NoteController extends ApiController
{
    /**
     * Retrieve Note information.
     *
     * @Route("/{id}", name="app_api_notes_get", options={"expose"=true})
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
     * Edit a specific Note.
     *
     * @Route("/{id}", name="app_api_notes_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Note    $note
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Note $note)
    {
        $form = $this->createForm(CreateType::class, $note, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

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
     * @Route("/{id}", name="app_api_notes_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Note $note
     *
     * @return JsonResponse
     */
    public function deleteAction(Note $note)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($note);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
