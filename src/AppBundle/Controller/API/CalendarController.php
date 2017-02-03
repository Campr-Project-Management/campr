<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Form\Calendar\CreateType;
use AppBundle\Security\CalendarVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/calendars")
 */
class CalendarController extends ApiController
{
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
     * Edit a specific Calendar.
     *
     * @Route("/{id}", name="app_api_calendar_edit")
     * @Method({"PUT", "PATCH"})
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

        $form = $this->createForm(CreateType::class, $calendar, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

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
     * @Route("/{id}", name="app_api_calendar_delete")
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
