<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\InfoCategory;
use AppBundle\Form\InfoCategory\CreateType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/info-category")
 */
class InfoCategoryController extends ApiController
{
    const ENTITY_CLASS = InfoCategory::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_admin_info_category_list")
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
            'AppBundle:Admin/InfoCategory:list.html.twig',
            [
                'infoCategories' => $infoCategories,
            ]
        );
    }

    /**
     * @Route("/filtered", name="app_admin_info_category_filtered", options={"expose"=true})
     */
    public function filteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(self::ENTITY_CLASS, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * @Route("/create", name="app_admin_info_category_create")
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
                        ->trans('success.info_category.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_category_list');
        }

        return $this->render(
            'AppBundle:Admin/InfoCategory:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/show", name="app_admin_info_category_show", options={"expose"=true})
     */
    public function showAction(InfoCategory $infoCategory)
    {
        return $this->render(
            'AppBundle:Admin/InfoCategory:show.html.twig',
            [
                'info_category' => $infoCategory,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_info_category_edit", options={"expose"=true})
     */
    public function editAction(Request $request, InfoCategory $infoCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(self::FORM_CLASS, $infoCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($infoCategory);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.info_category.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_info_category_list');
        }

        return $this->render(
            'AppBundle:Admin/InfoCategory:edit.html.twig',
            [
                'id' => $infoCategory->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_info_category_delete", options={"expose"=true})
     */
    public function deleteAction(Request $request, InfoCategory $infoCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($infoCategory);
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

        return $this->redirectToRoute('app_admin_info_category_list');
    }
}
