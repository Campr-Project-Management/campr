<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Rasci;
use AppBundle\Form\Rasci\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rasci admin controller.
 *
 * @Route("/admin/rasci")
 */
class RasciController extends BaseController
{
    /**
     * List all Rasci entities.
     *
     * @Route("/list", name="app_admin_rasci_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $rasci = $this
            ->getDoctrine()
            ->getRepository(Rasci::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Rasci:list.html.twig',
            [
                'rasci' => $rasci,
            ]
        );
    }

    /**
     * Lists all Rasci entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_rasci_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Rasci::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Display a specific Rasci entity.
     *
     * @Route("/{id}/show", name="app_admin_rasci_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Rasci $rasci
     *
     * @return Response
     */
    public function showAction(Rasci $rasci)
    {
        return $this->render(
            'AppBundle:Admin/Rasci:show.html.twig',
            [
                'rasci' => $rasci,
            ]
        );
    }

    /**
     * Creates a new Rasci entity.
     *
     * @Route("/create", name="app_admin_rasci_create")
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
                        ->trans('success.rasci.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_rasci_list');
        }

        return $this->render(
            'AppBundle:Admin/Rasci:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Rasci entity.
     *
     * @Route("/{id}/edit", name="app_admin_rasci_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Rasci   $rasci
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Rasci $rasci)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $rasci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($rasci);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.rasci.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_rasci_list');
        }

        return $this->render(
            'AppBundle:Admin/Rasci:edit.html.twig',
            [
                'id' => $rasci->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Rasci entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_rasci_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Rasci   $rasci
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Rasci $rasci)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($rasci);
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
                    ->trans('success.rasci.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_rasci_list');
    }
}
