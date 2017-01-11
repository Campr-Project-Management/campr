<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Project;
use AppBundle\Form\Calendar\CreateType;
use AppBundle\Form\Calendar\EditType;
use AppBundle\Security\ProjectVoter;
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
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $calendars = $this
            ->getDoctrine()
            ->getRepository(Calendar::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($calendars);
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
        $data = json_decode($request->getContent(), true);
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
        if ($project = $calendar->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(EditType::class, $calendar, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            return $this->createApiResponse($calendar);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Calendar.
     *
     * @Route("/{id}/delete", name="app_api_calendar_delete")
     * @Method({"GET"})
     *
     * @param Calendar $calendar
     *
     * @return JsonResponse
     */
    public function deleteAction(Calendar $calendar)
    {
        if ($project = $calendar->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($calendar);
        $em->flush();

        return $this->createApiResponse([], Response::HTTP_NO_CONTENT);
    }
}
