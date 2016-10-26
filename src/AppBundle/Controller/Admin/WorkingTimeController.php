<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\WorkingTime;
use AppBundle\Form\WorkingTime\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * WorkingTime controller.
 *
 * @Route("/admin/working-time")
 */
class WorkingTimeController extends Controller
{
    /**
     * Lists all WorkingTime entities.
     *
     * @Route("/list", name="app_admin_working_time_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $workingTimes = $em
            ->getRepository(WorkingTime::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkingTime:list.html.twig',
            [
                'workingTimes' => $workingTimes,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_working_time_list_filtered")
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
        $response = $dataTableService->paginate(WorkingTime::class, $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays WorkingTime entity.
     *
     * @Route("/{id}/show", name="app_admin_working_time_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkingTime $workingTime
     *
     * @return Response
     */
    public function showAction(WorkingTime $workingTime)
    {
        return $this->render(
            'AppBundle:Admin/WorkingTime:show.html.twig',
            [
                'workingTime' => $workingTime,
            ]
        );
    }

    /**
     * Creates a new WorkingTime entity.
     *
     * @Route("/create", name="app_admin_working_time_create", options={"expose"=true})
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
                        ->trans('admin.working_time.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_working_time_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkingTime:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_working_time_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(WorkingTime $workingTime, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workingTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workingTime);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.working_time.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_working_time_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkingTime:edit.html.twig',
            [
                'id' => $workingTime->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_working_time_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(WorkingTime $workingTime, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workingTime);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message, Response::HTTP_OK);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.working_time.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_working_time_list');
    }
}
