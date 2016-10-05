<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ColorStatus;
use AppBundle\Form\ColorStatus\CreateType as ColorStatusCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ColorStatus controller.
 *
 * @Route("/admin/color-status")
 */
class ColorStatusController extends Controller
{
    /**
     * Lists all ColorStatus entities.
     *
     * @Route("/list", name="app_admin_color_status_list")
     * @Method("GET")
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
                        ->trans('admin.color_status.create.success', [], 'admin')
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
     * @Route("/{id}/edit", name="app_admin_color_status_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
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
                        ->trans('admin.color_status.edit.success', [], 'admin')
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
     * @Route("/{id}/show", name="app_admin_color_status_show")
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
     * @Route("/{id}", name="app_admin_color_status_delete")
     * @Method({"GET"})
     *
     * @param ColorStatus $colorStatus
     *
     * @return RedirectResponse
     */
    public function deleteAction(ColorStatus $colorStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($colorStatus);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.color_status.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_color_status_list');
    }
}
