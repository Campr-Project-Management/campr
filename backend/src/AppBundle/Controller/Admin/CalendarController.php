<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Calendar;
use AppBundle\Form\Calendar\CreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Calendar admin controller.
 *
 * @Route("/admin/calendar")
 */
class CalendarController extends BaseController
{
    /**
     * Lists all Calendar entities.
     *
     * @Route("/list", name="app_admin_calendar_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $calendars = $em
            ->getRepository(Calendar::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Calendar:list.html.twig',
            [
                'calendars' => $calendars,
            ]
        );
    }

    /**
     * Lists all Calendar entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_calendar_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Calendar::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Calendar entity.
     *
     * @Route("/create", name="app_admin_calendar_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.calendar.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_calendar_list');
        }

        return $this->render(
            'AppBundle:Admin/Calendar:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Calendar entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_calendar_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param Calendar $calendar
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Calendar $calendar)
    {
        $form = $this->createForm(CreateType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.calendar.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_calendar_list');
        }

        return $this->render(
            'AppBundle:Admin/Calendar:edit.html.twig',
            [
                'id' => $calendar->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Calendar entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_calendar_show")
     * @Method({"GET"})
     *
     * @param Calendar $calendar
     *
     * @return Response
     */
    public function showAction(Calendar $calendar)
    {
        return $this->render(
            'AppBundle:Admin/Calendar:show.html.twig',
            [
                'calendar' => $calendar,
            ]
        );
    }

    /**
     * Deletes a Calendar entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_calendar_delete")
     * @Method({"GET"})
     *
     * @param Request  $request
     * @param Calendar $calendar
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Calendar $calendar)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($calendar);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.calendar.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.calendar.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.calendar.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        }
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                $flashKey,
                $flashMessage
            )
        ;

        return $this->redirectToRoute('app_admin_calendar_list');
    }
}
