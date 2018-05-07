<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\StatusReportConfig;
use AppBundle\Form\StatusReport\CreateType;
use AppBundle\Entity\Project;
use AppBundle\Entity\StatusReport;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/projects/{id}")
 */
class StatusReportController extends ApiController
{
    /**
     * Get all project status reports.
     *
     * @Route("/status-reports", name="app_api_project_status_reports", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $statusReportRepo = $this->getDoctrine()->getRepository(StatusReport::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'admin.per_page'
            );
            $result = $statusReportRepo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult(
            );
            $responseArray['totalItems'] = $statusReportRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        } elseif (isset($filters['trend'])) {
            return $this->createApiResponse(
                [
                    'items' => $statusReportRepo->findTrendReports($project),
                ]
            );
        }

        return $this->createApiResponse(
            [
                'items' => $statusReportRepo->findAll(),
            ]
        );
    }

    /**
     * @Route("/status-reports/generate", name="app_api_project_status_reports_generate", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function generateAction(Project $project): JsonResponse
    {
        $statusReport = $this->createStatusReport($project);

        return $this->createApiResponse($statusReport);
    }

    /**
     * @Route("/status-reports/trend-graph", name="app_api_project_status_reports_trend_graph", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function trendGraphAction(Project $project): JsonResponse
    {
        $reports = $this
            ->get('app.repository.status_report')
            ->findBy(['project' => $project])
        ;

        $report = new StatusReport();
        $report->setCreatedAt(new \DateTime());
        $report->setProjectTrafficLight($project->getTrafficLight());

        $reports[] = $report;

        $data = $this
            ->get('app.graph.generator.status_report_project_traffic_light')
            ->generate($reports)
        ;

        return $this->createApiResponse($data);
    }

    /**
     * Create a new Status Report.
     *
     * @Route("/status-reports", name="app_api_project_status_reports_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, Project $project): JsonResponse
    {
        $form = $this->createForm(CreateType::class, $this->createStatusReport($project), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $statusReport = $form->getData();
            $this->persistAndFlush($statusReport);

            return $this->createApiResponse($statusReport, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Checks if the user is able to create a new status report.
     *
     * @Route("/check-status-report-availability", name="app_api_project_status_reports_availability", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function availabilityAction(Project $project)
    {
        $statusReportRepo = $this->getDoctrine()->getRepository(StatusReport::class);
        $statusReportConfigRepo = $this->getDoctrine()->getRepository(StatusReportConfig::class);

        /** @var StatusReportConfig $config */
        $config = $statusReportConfigRepo->findOneBy(['project' => $project, 'isDefault' => true]);

        if ($config) {
            $today = new \DateTime();
            $lastReport = $statusReportRepo->findLastByProject($project);
            $todayReports = $statusReportRepo->countTotalByProjectAndFilters($project, ['date' => $today->format('Y-m-d')]);
            $perDay = $config->getPerDay();
            $minutesInterval = $config->getMinutesInterval();
            if ($perDay && $todayReports === $perDay) {
                return $this->createApiResponse(['error' => 'message.per_day_reports_exceeded']);
            } elseif ($minutesInterval) {
                $lastReportCreatedAt = $lastReport->getCreatedAt();
                $now = new \DateTime();
                $datetime1 = strtotime($lastReportCreatedAt->format('d-m-Y H:i:s'));
                $datetime2 = strtotime($now->format('d-m-Y H:i:s'));
                $interval = abs($datetime2 - $datetime1);
                $minutes = intval($interval / 60);
                if ($minutes < $minutesInterval) {
                    return $this->createApiResponse(['error' => 'message.minutes_under_interval']);
                }
            }
        }

        return $this->createApiResponse(null);
    }

    /**
     * @param Project $project
     *
     * @return StatusReport
     */
    private function createStatusReport(Project $project): StatusReport
    {
        $snapshot = $this->get('app.snapshot.factory.project')->create($project);
        $statusReport = new StatusReport();
        $statusReport->setSnapshot($snapshot);
        $statusReport->setProject($project);
        $statusReport->setProjectTrafficLight($project->getTrafficLight());
        $statusReport->setCreatedBy($this->getUser());

        return $statusReport;
    }
}
