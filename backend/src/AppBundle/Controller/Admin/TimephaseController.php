<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Timephase;
use AppBundle\Form\Timephase\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Timephase admin controller.
 *
 * @Route("/admin/timephase")
 */
class TimephaseController extends BaseController
{
    /**
     * Lists all Timephase entities.
     *
     * @Route("/list", name="app_admin_timephase_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $timephases = $em
            ->getRepository(Timephase::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Timephase:list.html.twig',
            [
                'timephases' => $timephases,
            ]
        );
    }

    /**
     * Lists all Timephase entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_timephase_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Timephase::class, 'value', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Timephase entity.
     *
     * @Route("/create", name="app_admin_timephase_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.timephase.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_timephase_list');
        }

        return $this->render(
            'AppBundle:Admin/Timephase:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Timephase entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_timephase_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Timephase $timephase
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Timephase $timephase)
    {
        $form = $this->createForm(CreateType::class, $timephase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($timephase);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.timephase.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_timephase_list');
        }

        return $this->render(
            'AppBundle:Admin/Timephase:edit.html.twig',
            [
                'id' => $timephase->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Timephase entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_timephase_show")
     * @Method({"GET"})
     *
     * @param Timephase $timephase
     *
     * @return Response
     */
    public function showAction(Timephase $timephase)
    {
        return $this->render(
            'AppBundle:Admin/Timephase:show.html.twig',
            [
                'timephase' => $timephase,
            ]
        );
    }

    /**
     * Deletes a Timephase entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_timephase_delete")
     * @Method({"GET"})
     *
     * @param Request   $request
     * @param Timephase $timephase
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Timephase $timephase)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($timephase);
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
                    ->trans('success.timephase.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_timephase_list');
    }
}
