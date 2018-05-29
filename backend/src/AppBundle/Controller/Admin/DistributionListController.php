<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\DistributionList;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\DistributionList\CreateType as DistributionListCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Distribution list admin controller.
 *
 * @Route("/admin/distribution-list")
 */
class DistributionListController extends BaseController
{
    /**
     * Lists all DistributionList entities.
     *
     * @Route("/list", name="app_admin_distribution_list_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $distributionLists = $em
            ->getRepository(DistributionList::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/DistributionList:list.html.twig',
            [
                'distribution_lists' => $distributionLists,
            ]
        );
    }

    /**
     * Lists all DistributionList entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_distribution_list_list_filtered")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(DistributionList::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new DistributionList entity.
     *
     * @Route("/create", name="app_admin_distribution_list_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $distributionList = new DistributionList();
        $form = $this->createForm(DistributionListCreateType::class, $distributionList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $distributionList->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($distributionList);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.distribution_list.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_distribution_list_list');
        }

        return $this->render(
            'AppBundle:Admin/DistributionList:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing DistributionList entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_distribution_list_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param DistributionList $distributionList
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, DistributionList $distributionList)
    {
        $form = $this->createForm(DistributionListCreateType::class, $distributionList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($distributionList);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.distribution_list.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_distribution_list_show',
                [
                    'id' => $distributionList->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Admin/DistributionList:edit.html.twig',
            [
                'distribution_list' => $distributionList,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a DistributionList entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_distribution_list_show")
     * @Method({"GET"})
     *
     * @param DistributionList $distributionList
     *
     * @return Response
     */
    public function showAction(DistributionList $distributionList)
    {
        return $this->render(
            'AppBundle:Admin/DistributionList:show.html.twig',
            [
                'distribution_list' => $distributionList,
            ]
        );
    }

    /**
     * Deletes a DistributionList entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_distribution_list_delete")
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param DistributionList $distributionList
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, DistributionList $distributionList)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($distributionList);
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
                    ->trans('success.distribution_list.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_distribution_list_list');
    }
}
