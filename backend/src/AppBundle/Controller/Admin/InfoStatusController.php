<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\InfoStatus;
use AppBundle\Form\InfoStatus\CreateType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/info-status")
 */
class InfoStatusController extends ApiController
{
    const ENTITY_CLASS = InfoStatus::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_admin_info_status_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     */
    public function indexAction()
    {
        $infoCategories = $this
            ->getRepository()
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/InfoStatus:list.html.twig',
            [
                'infoCategories' => $infoCategories,
            ]
        );
    }

    /**
     * @Route("/filtered", name="app_admin_info_status_filtered", options={"expose"=true})
     */
    public function filteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(self::ENTITY_CLASS, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * @Route("/create", name="app_admin_info_status_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(self::FORM_CLASS);
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
                        ->trans('success.info_status.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_status_list');
        }

        return $this->render(
            'AppBundle:Admin/InfoStatus:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/show", name="app_admin_info_status_show", options={"expose"=true})
     */
    public function showAction(InfoStatus $infoStatus)
    {
        return $this->render(
            'AppBundle:Admin/InfoStatus:show.html.twig',
            [
                'info_status' => $infoStatus,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_info_status_edit", options={"expose"=true})
     */
    public function editAction(Request $request, InfoStatus $infoStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(self::FORM_CLASS, $infoStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($infoStatus);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.info_status.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_status_list');
        }

        return $this->render(
            'AppBundle:Admin/InfoStatus:edit.html.twig',
            [
                'id' => $infoStatus->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_info_status_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, InfoStatus $infoStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($infoStatus);
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
                    ->trans('success.label.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_info_status_list');
    }
}
