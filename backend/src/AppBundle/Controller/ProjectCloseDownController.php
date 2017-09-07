<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ProjectCloseDown;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/project-close-down")
 */
class ProjectCloseDownController extends Controller
{
    /**
     * @Route("/{id}.pdf", name="app_project_close_down_pdf", options={"expose"=true})
     *
     * @param ProjectCloseDown $projectCloseDown
     */
    public function pdfAction(ProjectCloseDown $projectCloseDown)
    {
        $html = $this->renderView(':project_close_down:pdf.html.twig', [
            'projectCloseDown' => $projectCloseDown,
        ]);

        $pdf = $this
            ->get('app.service.pdf')
            ->loadHTML($html)
            ->pageSize('A4')
            ->get()
        ;

        return new Response($pdf, Response::HTTP_OK, [
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
