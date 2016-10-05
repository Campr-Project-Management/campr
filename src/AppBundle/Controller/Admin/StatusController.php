<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Status;
use AppBundle\Form\Status\CreateType;

/**
 * @Route("/admin/status")
 */
class StatusController extends Controller
{
    /**
     * @Route("/list", name="app_admin_status_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $status = $this
            ->getDoctrine()
            ->getRepository(Status::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin\Status:list.html.twig',
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
            'AppBundle:Admin\Status:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/edit/{id}", name="app_admin_status_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
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
            'AppBundle:Admin\Status:edit.html.twig',
            [
                'id' => $status->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="app_admin_status_delete")
     * @Method({"GET"})
     *
     * @param Request $request
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
                    ->trans('admin.status.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_status_list');
    }
}
