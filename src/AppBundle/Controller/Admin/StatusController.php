<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Status;
use AppBundle\Form\Status\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/status")
 */
class StatusController extends Controller
{
    /**
     * @Route("/list", name="app_admin_status_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction()
    {
        $statuses = $this
            ->getDoctrine()
            ->getRepository(Status::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Status:list.html.twig',
            [
                'statuses' => $statuses,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_status_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Status::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Status entity.
     *
     * @Route("/{id}/show", name="app_admin_status_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Status $status
     *
     * @return Response
     */
    public function showAction(Status $status)
    {
        return $this->render(
            'AppBundle:Admin/Status:show.html.twig',
            [
                'status' => $status,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_status_create")
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
                        ->trans('admin.status.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_status_list');
        }

        return $this->render(
            'AppBundle:Admin/Status:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_status_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Status  $status
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Status $status, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($status);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.status.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_status_list');
        }

        return $this->render(
            'AppBundle:Admin/Status:edit.html.twig',
            [
                'id' => $status->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_status_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Status $status
     *
     * @return RedirectResponse
     */
    public function deleteAction(Status $status)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($status);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.status.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_status_list');
    }
}
