<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatusReport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $html = $this->renderView(':status_report:pdf.html.twig', [
            'statusReport' => $statusReport,
        ]);

        $pdf = $this
            ->get('app.service.pdf')
            ->loadHTML($html)
            ->pageSize('A4')
            ->get()
        ;

        return new Response($pdf, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="status-report-%010d.pdf"', $statusReport->getCreatedAt()->format('DD-MM-YYYY')),
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
