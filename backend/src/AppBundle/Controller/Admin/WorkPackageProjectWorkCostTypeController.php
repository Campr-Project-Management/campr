<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Form\WorkPackageProjectWorkCostType\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * WorkPackageProjectWorkCostType admin controller.
 *
 * @Route("/admin/workpackage-projectworkcost")
 */
class WorkPackageProjectWorkCostTypeController extends BaseController
{
    /**
     * List all WorkPackageProjectWorkCostType entities.
     *
     * @Route("/list", name="app_admin_wppcwct_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $workProjectTypes = $this
            ->getDoctrine()
            ->getRepository(WorkPackageProjectWorkCostType::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/WorkPackageProjectWorkCostType:list.html.twig',
            [
                'workProjectTypes' => $workProjectTypes,
            ]
        );
    }

    /**
     * Lists all WorkPackageProjectWorkCostType entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_wppcwct_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(WorkPackageProjectWorkCostType::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays WorkPackageProjectCostWorkType entity.
     *
     * @Route("/{id}/show", name="app_admin_wppcwct_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackageProjectWorkCostType $workProjectType
     *
     * @return Response
     */
    public function showAction(WorkPackageProjectWorkCostType $workProjectType)
    {
        return $this->render(
            'AppBundle:Admin/WorkPackageProjectWorkCostType:show.html.twig',
            [
                'workProjectType' => $workProjectType,
            ]
        );
    }

    /**
     * Create a new WorkPackageProjectWorkCostType entity.
     *
     * @Route("/create", name="app_admin_wppcwct_create")
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
                        ->trans('success.wppcwct.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_wppcwct_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageProjectWorkCostType:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing WorkPackageProjectWorkCostType entity.
     *
     * @Route("/{id}/edit", name="app_admin_wppcwct_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request                        $request
     * @param WorkPackageProjectWorkCostType $workProjectType
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, WorkPackageProjectWorkCostType $workProjectType)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $workProjectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workProjectType->setUpdatedAt(new \DateTime());
            $em->persist($workProjectType);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.wppcwct.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_wppcwct_list');
        }

        return $this->render(
            'AppBundle:Admin/WorkPackageProjectWorkCostType:edit.html.twig',
            [
                'id' => $workProjectType->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific WorkPackageProjectWorkCostType entity.
     *
     * @Route("/{id}/delete", name="app_admin_wppcwct_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request                        $request
     * @param WorkPackageProjectWorkCostType $workProjectType
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, WorkPackageProjectWorkCostType $workProjectType)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workProjectType);
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
                    ->trans('success.wppcwct.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_wppcwct_list');
    }
}
