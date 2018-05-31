<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\StatusReport;
use Symfony\Component\HttpFoundation\Response;

/**
 * StatusReport admin controller.
 *
 * @Route("/admin/status-report")
 */
class StatusReportController extends BaseController
{
    /**
     * List all Status Report entities.
     *
     * @Route("/list", name="app_admin_status_report_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $reports = $this
            ->getDoctrine()
            ->getRepository(StatusReport::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/StatusReport:list.html.twig',
            [
                'reports' => $reports,
            ]
        );
    }

    /**
     * Lists all Status Report entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_status_report_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(StatusReport::class, 'id', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Status Report entity.
     *
     * @Route("/{id}/show", name="app_admin_status_report_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param StatusReport $statusReport
     *
     * @return Response
     */
    public function showAction(StatusReport $statusReport)
    {
        return $this->render(
            'AppBundle:Admin/StatusReport:show.html.twig',
            [
                'report' => $statusReport,
            ]
        );
    }

    /**
     * Deletes a specific Status entity.
     *
     * @Route("/{id}/delete", name="app_admin_status_report_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request      $request
     * @param StatusReport $statusReport
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, StatusReport $statusReport)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($statusReport);
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
                    ->trans('success.status_report.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_status_report_list');
    }
}
