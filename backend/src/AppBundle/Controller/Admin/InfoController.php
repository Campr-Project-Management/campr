<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Info;
use AppBundle\Form\Info\CreateType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/info")
 */
class InfoController extends ApiController
{
    const ENTITY_CLASS = Info::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_admin_info_list")
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
            'AppBundle:Admin/Info:list.html.twig',
            [
                'infoCategories' => $infoCategories,
            ]
        );
    }

    /**
     * @Route("/filtered", name="app_admin_info_filtered", options={"expose"=true})
     */
    public function filteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(self::ENTITY_CLASS, 'topic', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * @Route("/create", name="app_admin_info_create")
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
                        ->trans('success.info.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_list');
        }

        return $this->render(
            'AppBundle:Admin/Info:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/show", name="app_admin_info_show", options={"expose"=true})
     */
    public function showAction(Info $info)
    {
        return $this->render(
            'AppBundle:Admin/Info:show.html.twig',
            [
                'info' => $info,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_info_edit", options={"expose"=true})
     */
    public function editAction(Request $request, Info $info)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(self::FORM_CLASS, $info);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($info);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.info.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_list');
        }

        return $this->render(
            'AppBundle:Admin/Info:edit.html.twig',
            [
                'id' => $info->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_info_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, Info $info)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($info);
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

        return $this->redirectToRoute('app_admin_info_list');
    }
}
