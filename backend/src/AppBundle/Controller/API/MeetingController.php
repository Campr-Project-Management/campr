<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Meeting;
use AppBundle\Form\Meeting\CreateType;
use AppBundle\Security\MeetingVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meetings")
 */
class MeetingController extends ApiController
{
    /**
     * Retrieve Meeting information.
     *
     * @Route("/{id}", name="app_api_meeting_get", options={"expose"=true})
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
     * Edit a specific Meeting.
     *
     * @Route("/{id}", name="app_api_meeting_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::EDIT, $meeting);

        $form = $this->createForm(CreateType::class, $meeting, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return $this->createApiResponse($meeting, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Meeting.
     *
     * @Route("/{id}", name="app_api_meeting_delete", options={"expose"=true})
     * @Method({"DELETE"})
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

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
