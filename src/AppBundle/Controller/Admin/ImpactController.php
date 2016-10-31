<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Impact;
use AppBundle\Form\Impact\CreateType;
use AppBundle\Form\Impact\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ImpactController controller.
 *
 * @Route("/admin/impact")
 */
class ImpactController extends Controller
{
    /**
     * Lists all Impact entities.
     *
     * @Route("/list", name="app_admin_impact_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $impacts = $em
            ->getRepository(Impact::class)
            ->findAll();

        return $this->render(
            'AppBundle:Admin/Impact:list.html.twig',
            [
                'impacts' => $impacts,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_impact_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Impact::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Impact entity.
     *
     * @Route("/{id}/show", name="app_admin_impact_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Impact $impact
     *
     * @return Response
     */
    public function showAction(Impact $impact)
    {
        return $this->render(
            'AppBundle:Admin/Impact:show.html.twig',
            [
                'impact' => $impact,
            ]
        );
    }

    /**
     * Creates a new Impact entity.
     *
     * @Route("/create", name="app_admin_impact_create", options={"expose"=true})
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
                        ->trans('admin.impact.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_impact_list');
        }

        return $this->render(
            'AppBundle:Admin/Impact:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_impact_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Impact  $impact
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Impact $impact, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $impact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($impact);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.impact.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_impact_list');
        }

        return $this->render(
            'AppBundle:Admin/Impact:edit.html.twig',
            [
                'id' => $impact->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_impact_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Impact  $impact
     * @param Request $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Impact $impact, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($impact);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message, Response::HTTP_OK);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.impact.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_impact_list');
    }
}
