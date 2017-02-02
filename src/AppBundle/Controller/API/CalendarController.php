<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Project;
use AppBundle\Form\Calendar\CreateType;
use AppBundle\Form\Calendar\EditType;
use AppBundle\Security\CalendarVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/calendar")
 */
class CalendarController extends ApiController
{
    /**
     * All Calendars for a specific Project.
     *
     * @Route("/{id}/list", name="app_api_calendar_list")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        return $this->createApiResponse($project->getCalendars());
    }

    /**
     * Retrieve Calendar information.
     *
     * @Route("/{id}", name="app_api_calendar_get")
     * @Method({"GET"})
     *
     * @param Calendar $calendar
     *
     * @return JsonResponse
     */
    public function getAction(Calendar $calendar)
    {
        $project = $calendar->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(CalendarVoter::VIEW, $project);

        return $this->createApiResponse($calendar);
    }

    /**
     * Create a new Calendar.
     *
     * @Route("/create", name="app_api_calendar_create")
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
     * Edit a specific Calendar.
     *
     * @Route("/{id}/edit", name="app_api_calendar_edit")
     * @Method({"PATCH"})
     *
     * @param Request  $request
     * @param Calendar $calendar
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Calendar $calendar)
    {
        $project = $calendar->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(CalendarVoter::EDIT, $project);

        $form = $this->createForm(EditType::class, $calendar, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            return $this->createApiResponse($calendar, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Calendar.
     *
     * @Route("/{id}/delete", name="app_api_calendar_delete")
     * @Method({"DELETE"})
     *
     * @param Calendar $calendar
     *
     * @return JsonResponse
     */
    public function deleteAction(Calendar $calendar)
    {
        $project = $calendar->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(CalendarVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($calendar);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
