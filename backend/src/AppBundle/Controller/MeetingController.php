<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * @Route("/meeting")
 */
class MeetingController extends Controller
{
    /**
     * @Route("/{id}.pdf", name="app_meeting_pdf", options={"expose"=true})
     */
    public function pdfAction(Meeting $meeting)
    {
        $pdf = $this
            ->get('app.service.pdf')
            ->getMeetingPDF($meeting->getId())
        ;

        if (!$pdf) {
            throw new ServiceUnavailableHttpException();
        }

        return new Response(file_get_contents($pdf), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="meeting-%010d.pdf"', $meeting->getId()),
        ]);
    }

    /**
     * @Route("/{id}", name="app_meeting_view", requirements={"id":"\d+"})
     */
    public function viewAction(Meeting $meeting)
    {
        return $this->render(
            ':meeting:pdf.html.twig',
            [
                'meeting' => $meeting,
            ]
        );
    }
}
