<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Raci;
use AppBundle\Form\Raci\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/raci")
 */
class RaciController extends Controller
{
    /**
     * @Route("/list", name="app_admin_raci_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction()
    {
        $raci = $this
            ->getDoctrine()
            ->getRepository(Raci::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Raci:list.html.twig',
            [
                'raci' => $raci,
            ]
        );
    }

    /**
     * @Route("/list/filtered", name="app_admin_raci_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Raci::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Raci entity.
     *
     * @Route("/{id}/show", name="app_admin_raci_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Raci $raci
     *
     * @return Response
     */
    public function showAction(Raci $raci)
    {
        return $this->render(
            'AppBundle:Admin/Raci:show.html.twig',
            [
                'raci' => $raci,
            ]
        );
    }

    /**
     * @Route("/create", name="app_admin_raci_create")
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
                        ->trans('admin.raci.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_raci_list');
        }

        return $this->render(
            'AppBundle:Admin/Raci:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_raci_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Raci    $raci
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Raci $raci, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $raci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($raci);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.raci.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_raci_list');
        }

        return $this->render(
            'AppBundle:Admin/Raci:edit.html.twig',
            [
                'id' => $raci->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_raci_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Raci $raci
     *
     * @return RedirectResponse
     */
    public function deleteAction(Raci $raci)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($raci);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.raci.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_raci_list');
    }
}
