<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Schedule;
use AppBundle\Form\Schedule\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/schedule")
 */
class ScheduleController extends Controller
{
    /**
     * @Route("/list", name="app_admin_schedule_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction()
    {
        $schedules = $this
            ->getDoctrine()
            ->getRepository(Schedule::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Schedule:list.html.twig',
            [
                'schedules' => $schedules,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_schedule_list_filtered", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Schedule::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Schedule entity.
     *
     * @Route("/{id}/show", name="app_admin_schedule_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Schedule $schedule
     *
     * @return Response
     */
    public function showAction(Schedule $schedule)
    {
        return $this->render(
            'AppBundle:Admin/Schedule:show.html.twig',
            [
                'schedule' => $schedule,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_schedule_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.schedule.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_schedule_list');
        }

        return $this->render(
            'AppBundle:Admin/Schedule:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_schedule_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Schedule $schedule
     * @param Request  $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Schedule $schedule, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($schedule);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.schedule.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_schedule_list');
        }

        return $this->render(
            'AppBundle:Admin/Schedule:edit.html.twig',
            [
                'id' => $schedule->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_schedule_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Schedule $schedule
     *
     * @return RedirectResponse
     */
    public function deleteAction(Schedule $schedule)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($schedule);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.schedule.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_schedule_list');
    }
}
