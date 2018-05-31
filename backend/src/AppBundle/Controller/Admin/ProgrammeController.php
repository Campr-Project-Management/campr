<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Programme;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Programme\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Programme admin controller.
 *
 * @Route("/admin/programme")
 */
class ProgrammeController extends BaseController
{
    /**
     * Lists all Programme entities.
     *
     * @Route("/list", name="app_admin_programme_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programmes = $em
            ->getRepository(Programme::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Programme:list.html.twig',
            [
                'programmes' => $programmes,
            ]
        );
    }

    /**
     * Lists all Programme entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_programme_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Programme::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Programme entity.
     *
     * @Route("/{id}/show", name="app_admin_programme_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Programme $programme
     *
     * @return Response
     */
    public function showAction(Programme $programme)
    {
        return $this->render(
            'AppBundle:Admin/Programme:show.html.twig',
            [
                'programme' => $programme,
            ]
        );
    }

    /**
     * Creates a new Programme entity.
     *
     * @Route("/create", name="app_admin_programme_create", options={"expose"=true})
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
                        ->trans('success.programme.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_programme_list');
        }

        return $this->render(
            'AppBundle:Admin/Programme:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Programme entity.
     *
     * @Route("/{id}/edit", name="app_admin_programme_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Programme $programme
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Programme $programme)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($programme);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.programme.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_programme_list');
        }

        return $this->render(
            'AppBundle:Admin/Programme:edit.html.twig',
            [
                'id' => $programme->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Programme entity.
     *
     * @Route("/{id}/delete", name="app_admin_programme_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request   $request
     * @param Programme $programme
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Programme $programme)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($programme);
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
                    ->trans('success.programme.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_programme_list');
    }
}
