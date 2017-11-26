<?php

namespace MainBundle\Controller;

use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/projects")
 */
class ProjectController extends Controller
{

    /**
     * @Route("/{id}/xml", name="main_project_xml", options={"expose"=true})
     * @param Project $project
     * @return Response
     */
    public function getXMLAction(Project $project)
    {
        $exportService = $this->get('app.service.export');

        $xml = $exportService->exportProject($project);
        $data = $exportService->xmlToString($xml);

        return new Response($data, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => sprintf('attachement; filename="%s.xml"', $project->getName()),
        ]);
    }
}
