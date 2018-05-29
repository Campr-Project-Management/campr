<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Unit;
use AppBundle\Form\Unit\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Unit admin controller.
 *
 * @Route("/admin/unit")
 */
class UnitController extends BaseController
{
    /**
     * List all Unit entities.
     *
     * @Route("/list", name="app_admin_unit_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $units = $this
            ->getDoctrine()
            ->getRepository(Unit::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Unit:list.html.twig',
            [
                'units' => $units,
            ]
        );
    }

    /**
     * Lists all Unit entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_unit_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Unit::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Unit entity.
     *
     * @Route("/{id}/show", name="app_admin_unit_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Unit $unit
     *
     * @return Response
     */
    public function showAction(Unit $unit)
    {
        return $this->render(
            'AppBundle:Admin/Unit:show.html.twig',
            [
                'unit' => $unit,
            ]
        );
    }

    /**
     * Create a new Unit entity.
     *
     * @Route("/create", name="app_admin_unit_create")
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
                        ->trans('success.unit.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_unit_list');
        }

        return $this->render(
            'AppBundle:Admin/Unit:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Unit entity.
     *
     * @Route("/{id}/edit", name="app_admin_unit_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Unit $unit)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit->setUpdatedAt(new \DateTime());
            $em->persist($unit);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.unit.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_unit_list');
        }

        return $this->render(
            'AppBundle:Admin/Unit:edit.html.twig',
            [
                'id' => $unit->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Unit entity.
     *
     * @Route("/{id}/delete", name="app_admin_unit_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Unit $unit)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($unit);
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
                    ->trans('success.unit.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_unit_list');
    }
}
