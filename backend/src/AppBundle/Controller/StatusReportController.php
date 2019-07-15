<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatusReport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * @Route("/status-report")
 */
class StatusReportController extends Controller
{
    /**
     * @Route("/{id}.pdf", name="app_status_report_pdf", options={"expose"=true})
     */
    public function pdfAction(StatusReport $statusReport)
    {
        $pdf = $this
            ->get('app.service.pdf')
            ->getStatusReportPDF($statusReport)
        ;

        if (!$pdf) {
            throw new ServiceUnavailableHttpException();
        }

        $date = $statusReport->getCreatedAt();
        $translator = $this->get('translator');

        return new Response(file_get_contents($pdf), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf(
                'attachment; filename="CW%s - %s - %s.pdf"',
                $date->format('W'),
                $translator->trans('label.status_report', [], ''),
                $statusReport->getProject()->getName()
            ),
        ]);
    }

    /**
     * @Route("/{id}", name="app_status_report_view", requirements={"id":"\d+"})
     */
    public function viewAction(StatusReport $statusReport)
    {
        return $this->render(
            ':status_report:pdf.html.twig',
            [
                'statusReport' => $statusReport,
            ]
        );
    }
}
