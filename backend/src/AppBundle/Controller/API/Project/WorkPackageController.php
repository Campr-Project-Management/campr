<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Repository\WorkPackageStatusRepository;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/projects/{id}")
 */
class WorkPackageController extends ApiController
{
    /**
     * All project work packages.
     *
     * @Route("/work-packages", name="app_api_projects_workpackages", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request, Project $project)
    {
        $criteria = $request->query->get('criteria', []);
        $pageSize = $request->query->get('pageSize', $this->getParameter('front.per_page'));
        $page = $request->query->get('page', 1);

        /** @var WorkPackageRepository $wpRepo */
        $wpRepo = $this->get('app.repository.work_package');

        $paginator = $wpRepo->createGridViewPaginator($project, $criteria);
        $paginator->setMaxPerPage($pageSize);
        $paginator->setCurrentPage($page);

        return $this->createApiResponse(
            [
                'totalItems' => $paginator->getNbResults(),
                'items' => iterator_to_array($paginator->getCurrentPageResults()),
            ]
        );
    }

    /**
     * @Route("/work-packages/board", name="app_api_projects_workpackages_board", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function boardAction(Request $request, Project $project)
    {
        $criteria = $request->query->get('criteria', []);
        $pageSize = $request->query->get('pageSize', $this->getParameter('front.per_page'));
        $page = $request->query->get('page', 1);

        /** @var WorkPackageRepository $wpRepo */
        $wpRepo = $this->get('app.repository.work_package');

        $data = [];
        foreach ($this->getWorkPackageStatuses() as $status) {
            $paginator = $wpRepo->createBoardViewPaginator(
                $project,
                array_merge(['status' => $status->getId()], $criteria)
            );
            $paginator->setMaxPerPage($pageSize);
            $paginator->setCurrentPage($page);

            $data[$status->getId()] = [
                'totalItems' => $paginator->getNbResults(),
                'items' => iterator_to_array($paginator->getCurrentPageResults()),
            ];
        }

        return $this->createApiResponse($data);
    }

    /**
     * @return WorkPackageStatus[]
     */
    private function getWorkPackageStatuses(): array
    {
        /** @var WorkPackageStatusRepository $repo */
        $repo = $this->get('app.repository.work_package_status');

        return $repo->findAll();
    }
}
