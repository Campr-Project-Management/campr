<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\WorkPackageStatus;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\WorkPackageStatus\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * WorkPackageStatus admin controller.
 *
 * @Route("/admin/workpackage-status")
 */
class WorkPackageStatusController extends BaseController
{
    /**
     * List all WorkPackageStatus entities.
     *
     * @Route("/list", name="app_admin_workpackage_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $workPackageStatuses = $this
            ->getDoctrine()
            ->getRepository(WorkPackageStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkPackageStatus:list.html.twig',
            [
                'workPackageStatuses' => $workPackageStatuses,
            ]
        );
    }

    /**
     * Lists all WorkPackageStatus entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_workpackage_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(WorkPackageStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays WorkPackageStatus entity.
     *
     * @Route("/{id}/show", name="app_admin_workpackage_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Response
     */
    public function showAction(WorkPackageStatus $workPackageStatus)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackageStatus:show.html.twig',
            [
                'workPackageStatus' => $workPackageStatus,
            ]
        );
    }

    /**
     * Create a new WorkPackageStatus entity.
     *
     * @Route("/create", name="app_admin_workpackage_status_create")
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
                        ->trans('success.workpackage_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_status_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing WorkPackageStatus entity.
     *
     * @Route("/{id}/edit", name="app_admin_workpackage_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, WorkPackageStatus $workPackageStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workPackageStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workPackageStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workpackage_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_status_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageStatus:edit.html.twig',
            [
                'id' => $workPackageStatus->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific WorkPackageStatus entity.
     *
     * @Route("/{id}/delete", name="app_admin_workpackage_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request           $request
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, WorkPackageStatus $workPackageStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPackageStatus);
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
                    ->trans('success.workpackage_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_workpackage_status_list');
    }
}
