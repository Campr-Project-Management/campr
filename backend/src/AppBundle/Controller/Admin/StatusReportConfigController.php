<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\StatusReportConfig;
use AppBundle\Form\StatusReportConfig\AdminType;
use Symfony\Component\HttpFoundation\Response;

/**
 * StatusReportConfig admin controller.
 *
 * @Route("/admin/status-report-config")
 */
class StatusReportConfigController extends BaseController
{
    /**
     * List all StatusReportConfig entities.
     *
     * @Route("/list", name="app_admin_status_report_config_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $configs = $this
            ->getDoctrine()
            ->getRepository(StatusReportConfig::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/StatusReportConfig:list.html.twig',
            [
                'configs' => $configs,
            ]
        );
    }

    /**
     * Lists all StatusReportConfig entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_status_report_config_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(StatusReportConfig::class, 'id', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays StatusReportConfig entity.
     *
     * @Route("/{id}/show", name="app_admin_status_report_config_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param StatusReportConfig $statusReportConfig
     *
     * @return Response
     */
    public function showAction(StatusReportConfig $statusReportConfig)
    {
        return $this->render(
            'AppBundle:Admin/StatusReportConfig:show.html.twig',
            [
                'config' => $statusReportConfig,
            ]
        );
    }

    /**
     * Create a new StatusReportConfig entity.
     *
     * @Route("/create", name="app_admin_status_report_config_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class);
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
                        ->trans('success.status_report_config.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_status_report_config_list');
        }

        return $this->render(
            'AppBundle:Admin/StatusReportConfig:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing StatusReportConfig entity.
     *
     * @Route("/{id}/edit", name="app_admin_status_report_config_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request            $request
     * @param StatusReportConfig $statusReportConfig
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, StatusReportConfig $statusReportConfig)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, $statusReportConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($statusReportConfig);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.status_report_config.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_status_report_config_list');
        }

        return $this->render(
            'AppBundle:Admin/StatusReportConfig:edit.html.twig',
            [
                'id' => $statusReportConfig->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific StatusReportConfig entity.
     *
     * @Route("/{id}/delete", name="app_admin_status_report_config_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request            $request
     * @param StatusReportConfig $statusReportConfig
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, StatusReportConfig $statusReportConfig)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($statusReportConfig);
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
                    ->trans('success.status_report_config.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_status_report_config_list');
    }
}
