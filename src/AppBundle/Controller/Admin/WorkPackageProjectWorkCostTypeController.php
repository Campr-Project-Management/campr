<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Form\WorkPackageProjectWorkCostType\CreateType;

/**
 * @Route("/admin/workpackage-projectworkcost")
 */
class WorkPackageProjectWorkCostTypeController extends Controller
{
    /**
     * @Route("/list", name="app_admin_wppcwct_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $workProjectTypes = $this
            ->getDoctrine()
            ->getRepository(WorkPackageProjectWorkCostType::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin\WorkPackageProjectWorkCostType:list.html.twig',
            [
                'workProjectTypes' => $workProjectTypes,
            ]
        );
    }

    /**
     * Displays WorkPackageProjectCostWorkType entity.
     *
     * @Route("/{id}/show", name="app_admin_wppcwct_show")
     * @Method({"GET"})
     *
     * @param WorkPackageProjectWorkCostType $workProjectType
     *
     * @return Response
     */
    public function showAction(WorkPackageProjectWorkCostType $workProjectType)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackageProjectWorkCostType:show.html.twig',
            [
                'workProjectType' => $workProjectType,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_wppcwct_create")
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
                        ->trans('admin.wppcwct.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_wppcwct_list');
        }

        return $this->render(
            'AppBundle:Admin\WorkPackageProjectWorkCostType:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_wppcwct_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function editAction(WorkPackageProjectWorkCostType $workProjectType, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workProjectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workProjectType->setUpdatedAt(new \DateTime());
            $em->persist($workProjectType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.wppcwct.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_unit_list');
        }

        return $this->render(
            'AppBundle:Admin\WorkPackageProjectWorkCostType:edit.html.twig',
            [
                'id' => $workProjectType->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_wppcwct_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function deleteAction(WorkPackageProjectWorkCostType $workProjectType)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workProjectType);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.wppcwct.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_wppcwct_list');
    }
}
