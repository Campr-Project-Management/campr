<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Subteam;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MainBundle\Controller\API\ApiController;
use AppBundle\Form\Subteam\CreateType as SubteamCreateType;

/**
 * Subteam admin controller.
 *
 * @Route("/admin/subteam")
 */
class SubteamController extends ApiController
{
    /**
     * Lists all Subteam entities.
     *
     * @Route("/list", name="app_admin_subteam_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $subteams = $em
            ->getRepository(Subteam::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Subteam:list.html.twig',
            [
                'subteams' => $subteams,
            ]
        );
    }

    /**
     * Lists all Subteam entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_subteam_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Subteam::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Subteam entity.
     *
     * @Route("/create", name="app_admin_subteam_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $subteam = new Subteam();
        $form = $this->createForm(SubteamCreateType::class, $subteam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteam);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_list');
        }

        return $this->render(
            'AppBundle:Admin/Subteam:create.html.twig',
            [
                'subteam' => $subteam,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Subteam entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_subteam_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Subteam $subteam
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Subteam $subteam)
    {
        $form = $this->createForm(SubteamCreateType::class, $subteam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteam);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_list');
        }

        return $this->render(
            'AppBundle:Admin/Subteam:edit.html.twig',
            [
                'subteam' => $subteam,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Subteam entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_subteam_show")
     * @Method({"GET"})
     *
     * @param Subteam $subteam
     *
     * @return Response
     */
    public function showAction(Subteam $subteam)
    {
        return $this->render(
            'AppBundle:Admin/Subteam:show.html.twig',
            [
                'subteam' => $subteam,
            ]
        );
    }

    /**
     * Deletes a Subteam entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_subteam_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Subteam $subteam
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Subteam $subteam)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subteam);
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
                    ->trans('success.subteam.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_subteam_list');
    }
}
