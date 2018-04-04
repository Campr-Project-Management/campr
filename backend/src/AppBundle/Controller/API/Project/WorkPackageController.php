<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Paginator\SerializablePagerfanta;
use AppBundle\Paginator\SerializablePaginator;
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

        $paginator = new SerializablePagerfanta($paginator);

        return $this->createApiResponse($paginator);
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

        $mainPaginator = new SerializablePaginator();

        $items = [];
        foreach ($this->getWorkPackageStatuses() as $status) {
            $paginator = $wpRepo->createBoardViewPaginator(
                $project,
                array_merge(['status' => $status->getId()], $criteria)
            );
            $paginator->setMaxPerPage($pageSize);
            $paginator->setAllowOutOfRangePages(true);
            $paginator->setCurrentPage($page);

            $mainPaginator->setNbPages(max($mainPaginator->getNbPages(), $paginator->getNbPages()));
            $mainPaginator->setNbItems($mainPaginator->getNbItems() + $paginator->getNbResults());

            $paginator = new SerializablePagerfanta($paginator);
            $items[$status->getId()] = $paginator;
        }

        $mainPaginator->setItems($items);

        return $this->createApiResponse($mainPaginator);
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
