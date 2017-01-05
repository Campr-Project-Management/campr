<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Project;
use AppBundle\Form\Calendar\CreateType;
use AppBundle\Form\Calendar\EditType;
use AppBundle\Security\ProjectVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/calendar")
 */
class CalendarController extends Controller
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

        $calendarArray = [];
        foreach ($calendars as $calendar) {
            $calendarArray[] = $this->serialize($calendar);
        }

        return new JsonResponse($calendarArray);
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
        return new JsonResponse($this->serialize($calendar));
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

            return new JsonResponse($this->serialize($form->getData()));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
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

            return new JsonResponse($this->serialize($calendar));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
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

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create array with needed information from Calendar object.
     *
     * @param Calendar $calendar
     *
     * @return array
     */
    private function serialize(Calendar $calendar)
    {
        $calendarInfo = [
            'id' => $calendar->getId(),
            'name' => $calendar->getName(),
            'is_based' => $calendar->getIsBased(),
            'is_baseline' => $calendar->getIsBaseline(),
            'parent_id' => $calendar->getParent() ? $calendar->getParent()->getId() : null,
            'project' => $calendar->getProject() ? $calendar->getProject()->getId() : null,
            'project_name' => $calendar->getProject() ? $calendar->getProject()->getName() : null,
            'days' => [],
        ];

        if (!$calendar->getDays()->isEmpty()) {
            foreach ($calendar->getDays() as $day) {
                $dayInfo = [
                    'id' => $day->getId(),
                    'type' => $day->getType(),
                    'working' => $day->getWorking(),
                    'working_times' => [],
                ];
                if (!$day->getWorkingTimes()->isEmpty()) {
                    foreach ($day->getWorkingTimes() as $wt) {
                        $dayInfo['working_times'][] = [
                            'id' => $wt->getId(),
                            'from_time' => $wt->getFromTime() ? $wt->getFromTime()->format('H:i:s') : null,
                            'to_time' => $wt->getToTime() ? $wt->getToTime()->format('H:i:s') : null,
                        ];
                    }
                }
                $calendarInfo['days'][] = $dayInfo;
            }
        }

        return $calendarInfo;
    }
}
