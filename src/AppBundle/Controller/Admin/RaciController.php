<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
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
 * Raci admin controller.
 *
 * @Route("/admin/raci")
 */
class RaciController extends Controller
{
    /**
     * List all Raci entities.
     *
     * @Route("/list", name="app_admin_raci_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
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
     * Lists all Raci entities filtered and paginated.
     *
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
     * Display a specific Raci entity.
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
     * Creates a new Raci entity.
     *
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
                        ->trans('success.raci.create', [], 'flashes')
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
     * Displays a form to edit an existing Raci entity.
     *
     * @Route("/{id}/edit", name="app_admin_raci_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Raci    $raci
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Raci $raci)
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
                        ->trans('success.raci.edit', [], 'flashes')
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
     * Deletes a specific Raci entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_raci_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Raci    $raci
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Raci $raci)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($raci);
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
                    ->trans('success.raci.delete.general', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_raci_list');
    }
}
