<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\MeetingCategory;
use AppBundle\Form\MeetingCategory\BaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meeting-categories")
 */
class MeetingCategoryController extends ApiController
{
    const ENTITY_CLASS = MeetingCategory::class;
    const FORM_CLASS = BaseType::class;

    /**
     * Get all meeting categories.
     *
     * @Route(name="app_api_meeting_categories_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }

    /**
     * Create a new Meeting Category.
     *
     * @Route(name="app_api_meeting_categories_create", options={"expose"=true})
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
     * Get Meeting Category by id.
     *
     * @Route("/{id}", name="app_api_meeting_categories_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param MeetingCategory $meetingCategory
     *
     * @return JsonResponse
     */
    public function getAction(MeetingCategory $meetingCategory)
    {
        return $this->createApiResponse($meetingCategory);
    }

    /**
     * Edit a specific Meeting Category.
     *
     * @Route("/{id}", name="app_api_meeting_categories_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request         $request
     * @param MeetingCategory $meetingCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, MeetingCategory $meetingCategory)
    {
        $form = $this->getForm($meetingCategory, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($meetingCategory);

            return $this->createApiResponse($meetingCategory, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Meeting Category.
     *
     * @Route("/{id}", name="app_api_meeting_categories_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param MeetingCategory $meetingCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(MeetingCategory $meetingCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($meetingCategory);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
