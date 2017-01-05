<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Schedule;
use AppBundle\Form\Schedule\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/schedule")
 */
class ScheduleController extends ApiController
{
    /**
     * Get all schedules.
     *
     * @Route("/list", name="app_api_schedule_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $schedules = $this
            ->getDoctrine()
            ->getRepository(Schedule::class)
            ->findAll()
        ;

        return $this->createApiResponse($schedules);
    }

    /**
     * Create a new Schedule.
     *
     * @Route("/create", name="app_api_schedule_create")
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

            return $this->createApiResponse($form->getData(), JsonResponse::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Get Schedule by id.
     *
     * @Route("/{id}", name="app_api_schedule_get")
     * @Method({"GET"})
     *
     * @param Schedule $schedule
     *
     * @return JsonResponse
     */
    public function getAction(Schedule $schedule)
    {
        return $this->createApiResponse($schedule);
    }

    /**
     * Edit a specific Schedule.
     *
     * @Route("/{id}/edit", name="app_api_schedule_edit")
     * @Method({"POST"})
     *
     * @param Request  $request
     * @param Schedule $schedule
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Schedule $schedule)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $schedule, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($schedule);
            $em->flush();

            return $this->createApiResponse($schedule);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Schedule.
     *
     * @Route("/{id}/delete", name="app_api_schedule_delete")
     * @Method({"DELETE"})
     *
     * @param Schedule $schedule
     *
     * @return JsonResponse
     */
    public function deleteAction(Schedule $schedule)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($schedule);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
