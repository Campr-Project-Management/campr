<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Impact;
use AppBundle\Form\Impact\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Impact admin controller.
 *
 * @Route("/admin/impact")
 */
class ImpactController extends BaseController
{
    /**
     * Lists all Impact entities.
     *
     * @Route("/list", name="app_admin_impact_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
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
     * Lists all Impact entities filtered and paginated.
     *
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

        return $this->createApiResponse($response);
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
        $impact = new Impact();
        $form = $this->createForm(CreateType::class, $impact);
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
                        ->trans('success.impact.create', [], 'flashes')
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
     * Displays a form to edit an existing Impact entity.
     *
     * @Route("/{id}/edit", name="app_admin_impact_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Impact  $impact
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Impact $impact)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $impact);
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
                        ->trans('success.impact.edit', [], 'flashes')
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
     * Deletes a specific Impact entity.
     *
     * @Route("/{id}/delete", name="app_admin_impact_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Impact  $impact
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Impact $impact)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($impact);
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
                    ->trans('success.impact.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_impact_list');
    }
}
