<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/workpackage")
 */
class WorkPackageController extends Controller
{
    /**
     * @Route("/list", name="app_admin_workpackage_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction()
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
     * @Route("/list/filtered", name="app_admin_workpackage_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(WorkPackage::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays WorkPackage entity.
     *
     * @Route("/{id}/show", name="app_admin_workpackage_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
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
     * @Route("/{id}/edit", name="app_admin_workpackage_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param WorkPackage $workPackage
     * @param Request     $request
     *
     * @return Response|RedirectResponse
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
     * @Route("/{id}/delete", name="app_admin_workpackage_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return RedirectResponse
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
