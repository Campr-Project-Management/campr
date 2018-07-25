<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\StatusReport;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/status-reports")
 */
class StatusReportController extends ApiController
{
    /**
     * Get StatusReport by id.
     *
     * @Route("/{id}", name="app_api_status_reports_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param StatusReport $statusReport
     *
     * @return JsonResponse
     */
    public function getAction(StatusReport $statusReport)
    {
        return $this->createApiResponse($statusReport);
    }

    /**
     * Email status report to special distribution list.
     *
     * @Route("/{id}/email", name="app_api_status_reports_email", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param StatusReport $statusReport
     *
     * @return JsonResponse
     */
    public function emailAction(StatusReport $statusReport)
    {
        $mailerService = $this->get('app.service.mailer');
        $em = $this->getDoctrine()->getManager();

        $pdf = $this
            ->get('app.service.pdf')
            ->getStatusReportPDF($statusReport)
        ;

        $specialDistribution = $em->getRepository(DistributionList::class)->findOneBy([
            'project' => $statusReport->getProject(),
            'name' => 'label.status_report_distribution', // @TODO: change this!
        ]);
        if ($specialDistribution) {
            $users = $specialDistribution->getUsers();
            foreach ($users as $user) {
                $mailerService->sendEmail(
                    ':status_report:email_report.html.twig',
                    'info',
                    $user->getEmail(),
                    ['statusReport' => $statusReport],
                    [
                        new \Swift_Attachment(
                            file_get_contents($pdf),
                            sprintf('status-report-%s.pdf', $statusReport->getCreatedAt()->format('Y-m-d')),
                            'application/pdf'
                        ),
                    ]
                );
            }
        }

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
