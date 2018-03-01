<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectCloseDown;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * @Route("/project-close-down")
 */
class ProjectCloseDownController extends Controller
{
    /**
     * @Route("/{id}.pdf", name="app_project_close_down_pdf", options={"expose"=true})
     *
     * @throws ServiceUnavailableHttpException
     *
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return Response
     */
    public function pdfAction(ProjectCloseDown $projectCloseDown)
    {
        $pdf = $this
            ->get('app.service.pdf')
            ->getProjectCloseDownPDF($projectCloseDown->getId())
        ;

        if (!$pdf) {
            throw new ServiceUnavailableHttpException();
        }

        return new Response(file_get_contents($pdf), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="project-close-down-%010d.pdf"', $projectCloseDown->getId()),
        ]);
    }

    /**
     * @Route("/{id}", name="app_project_close_down_view", requirements={"id":"\d+"})
     *
     * @param ProjectCloseDown $projectCloseDown
     */
    public function viewAction(ProjectCloseDown $projectCloseDown)
    {
        return $this->render(
            ':project_close_down:pdf.html.twig',
            [
                'projectCloseDown' => $projectCloseDown,
            ]
        );
    }
}
