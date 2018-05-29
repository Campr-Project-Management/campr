<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\WorkingTime;
use AppBundle\Form\WorkingTime\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * WorkingTime admin controller.
 *
 * @Route("/admin/working-time")
 */
class WorkingTimeController extends BaseController
{
    /**
     * Lists all WorkingTime entities.
     *
     * @Route("/list", name="app_admin_working_time_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all WorkingTime entities filtered and paginated.
     *
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
        $response = $dataTableService->paginateByColumn(WorkingTime::class, 'id', $requestParams);

        return $this->createApiResponse($response);
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
                        ->trans('success.working_time.create', [], 'flashes')
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
     * Displays a form to edit an existing WorkingTime entity.
     *
     * @Route("/{id}/edit", name="app_admin_working_time_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param WorkingTime $workingTime
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, WorkingTime $workingTime)
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
                        ->trans('success.working_time.edit', [], 'flashes')
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
     * Deletes a specific WorkingTime entity.
     *
     * @Route("/{id}/delete", name="app_admin_working_time_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param WorkingTime $workingTime
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, WorkingTime $workingTime)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workingTime);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.working_time.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_working_time_list');
    }
}
