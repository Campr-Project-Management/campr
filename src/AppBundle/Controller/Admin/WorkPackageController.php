<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;

/**
 * @Route("/admin/workpackage")
 */
class WorkPackageController extends Controller
{
    /**
     * @Route("/list", name="app_admin_workpackage_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $workPackages = $this
            ->getDoctrine()
            ->getRepository(WorkPackage::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkPackage:list.html.twig',
            [
                'workPackages' => $workPackages,
            ]
        );
    }

    /**
     * Displays WorkPackage entity.
     *
     * @Route("/{id}/show", name="app_admin_workpackage_show")
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function showAction(WorkPackage $workPackage)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackage:show.html.twig',
            [
                'workPackage' => $workPackage,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_workpackage_create")
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
                        ->trans('admin.workpackage.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackage:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_workpackage_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(WorkPackage $workPackage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workPackage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($workPackage);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.workpackage.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_workpackage_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackage:edit.html.twig',
            [
                'id' => $workPackage->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_workpackage_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(WorkPackage $workPackage)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPackage);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.workpackage.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_workpackage_list');
    }
}
