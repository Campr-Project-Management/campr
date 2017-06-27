<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/contract")
 */
class ContractController extends Controller
{
    /**
     * @Route("/{id}.pdf", name="app_contract_pdf", options={"expose"=true})
     */
    public function pdfAction(Contract $contract)
    {
        $html = $this->renderView(':contract:pdf.html.twig', [
            'contract' => $contract,
        ]);

        $pdf = $this
            ->get('app.service.pdf')
            ->loadHTML($html)
            ->pageSize('A4')
            ->get()
        ;

        return new Response($pdf, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="contract-%010d.pdf"', $contract->getId()),
        ]);
    }

    /**
     * @Route("/by-project/{id}", name="app_contract_by_project")
     */
    public function forProjectAction(Project $project)
    {
        if (!$project->getContracts()->count()) {
            throw $this->createNotFoundException();
        }

        return $this->redirectToRoute('app_contract_view', [
            'id' => $project->getContracts()->first()->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_contract_view", requirements={"id":"\d+"})
     */
    public function viewAction(Contract $contract)
    {
        return $this->render(
            ':contract:pdf.html.twig',
            [
                'contract' => $contract,
            ]
        );
    }
}
