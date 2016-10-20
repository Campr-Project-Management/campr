<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Schedule;
use AppBundle\Form\Schedule\CreateType;

/**
 * @Route("/admin/schedule")
 */
class ScheduleController extends Controller
{
    /**
     * @Route("/list", name="app_admin_schedule_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
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
     * Displays Schedule entity.
     *
     * @Route("/{id}/show", name="app_admin_schedule_show")
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
     * @Route("/{id}/edit", name="app_admin_schedule_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
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
     * @Route("/{id}/delete", name="app_admin_schedule_delete")
     * @Method({"GET"})
     *
     * @param Request $request
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
