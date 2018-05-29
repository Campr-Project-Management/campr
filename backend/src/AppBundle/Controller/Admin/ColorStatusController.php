<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ColorStatus;
use AppBundle\Form\ColorStatus\CreateType as ColorStatusCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ColorStatus admin controller.
 *
 * @Route("/admin/color-status")
 */
class ColorStatusController extends BaseController
{
    /**
     * Lists all ColorStatus entities.
     *
     * @Route("/list", name="app_admin_color_status_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $colorStatuses = $em
            ->getRepository(ColorStatus::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ColorStatus:list.html.twig',
            [
                'color_statuses' => $colorStatuses,
            ]
        );
    }

    /**
     * Lists all ColorStatus entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_color_status_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ColorStatus::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ColorStatus entity.
     *
     * @Route("/create", name="app_admin_color_status_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $colorStatus = new ColorStatus();
        $form = $this->createForm(ColorStatusCreateType::class, $colorStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($colorStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.color_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_color_status_list');
        }

        return $this->render(
            'AppBundle:Admin/ColorStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ColorStatus entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_color_status_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param ColorStatus $colorStatus
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ColorStatus $colorStatus)
    {
        $form = $this->createForm(ColorStatusCreateType::class, $colorStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($colorStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.color_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_color_status_list');
        }

        return $this->render(
            'AppBundle:Admin/ColorStatus:edit.html.twig',
            [
                'color_status' => $colorStatus,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ColorStatus entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_color_status_show")
     * @Method({"GET"})
     *
     * @param ColorStatus $colorStatus
     *
     * @return Response
     */
    public function showAction(ColorStatus $colorStatus)
    {
        return $this->render(
            'AppBundle:Admin/ColorStatus:show.html.twig',
            [
                'color_status' => $colorStatus,
            ]
        );
    }

    /**
     * Deletes a ColorStatus entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_color_status_delete")
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param ColorStatus $colorStatus
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ColorStatus $colorStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($colorStatus);
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
                    ->trans('success.color_status.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_color_status_list');
    }
}
