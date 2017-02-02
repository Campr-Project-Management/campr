<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Form\Meeting\CreateType;
use AppBundle\Security\MeetingVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meeting")
 */
class MeetingController extends ApiController
{
    /**
     * All meetings for a specific Project.
     *
     * @Route("/{id}/list", name="app_api_meeting_list")
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $meetings = $this
            ->getDoctrine()
            ->getRepository(Meeting::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($meetings);
    }

    /**
     * Retrieve Meeting information.
     *
     * @Route("/{id}", name="app_api_meeting_get")
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function getAction(Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::VIEW, $meeting);

        return $this->createApiResponse($meeting);
    }

    /**
     * Create a new Meeting.
     *
     * @Route("/create", name="app_api_meeting_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $meeting = new Meeting();
        $meeting->setCreatedBy($this->getUser());

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $meeting, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return $this->createApiResponse($meeting, Response::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Edit a specific Meeting.
     *
     * @Route("/{id}/edit", name="app_api_meeting_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::EDIT, $meeting);

        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $meeting, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return $this->createApiResponse($meeting);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Meeting.
     *
     * @Route("/{id}/delete", name="app_api_meeting_delete")
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function deleteAction(Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::DELETE, $meeting);

        $em = $this->getDoctrine()->getManager();
        $em->remove($meeting);
        $em->flush();

        return $this->createApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
