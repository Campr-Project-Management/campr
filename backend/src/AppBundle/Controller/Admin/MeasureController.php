<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Measure;
use AppBundle\Form\Measure\BaseType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Measure admin controller.
 *
 * @Route("/admin/measure")
 */
class MeasureController extends BaseController
{
    /**
     * List all Measure entities.
     *
     * @Route("/list", name="app_admin_measure_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $measures = $this
            ->getDoctrine()
            ->getRepository(Measure::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Measure:list.html.twig',
            [
                'measures' => $measures,
            ]
        );
    }

    /**
     * Lists all $measures entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_measure_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Measure::class, 'title', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Measure entity.
     *
     * @Route("/{id}/show", name="app_admin_measure_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Measure $measure
     *
     * @return Response
     */
    public function showAction(Measure $measure)
    {
        return $this->render(
            'AppBundle:Admin/Measure:show.html.twig',
            [
                'measure' => $measure,
            ]
        );
    }

    /**
     * Creates a new Measure entity.
     *
     * @Route("/create", name="app_admin_measure_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(BaseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($form->getData());
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.measure.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_measure_list');
        }

        return $this->render(
            'AppBundle:Admin/Measure:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Measure entity.
     *
     * @Route("/{id}/edit", name="app_admin_measure_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Measure $measure
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Measure $measure)
    {
        $form = $this->createForm(BaseType::class, $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($measure);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.measure.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_measure_list');
        }

        return $this->render(
            'AppBundle:Admin/Measure:edit.html.twig',
            [
                'id' => $measure->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Measure entity.
     *
     * @Route("/{id}/delete", name="app_admin_measure_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Measure $measure
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Measure $measure)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($measure);
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
                    ->trans('success.measure.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_measure_list');
    }
}
