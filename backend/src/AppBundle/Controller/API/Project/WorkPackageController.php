<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Form\WorkPackage\ApiCreateType;
use AppBundle\Paginator\SerializablePagerfanta;
use AppBundle\Repository\WorkPackageRepository;
use Component\WorkPackage\WorkPackageEvents;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $sorting = $request->query->get('sorting', []);
        $pageSize = $request->query->get('pageSize', $this->getParameter('front.per_page'));
        $page = $request->query->get('page', 1);

        /** @var WorkPackageRepository $wpRepo */
        $wpRepo = $this->get('app.repository.work_package');

        $paginator = $wpRepo->createTasksPaginator($project, $criteria, $sorting);
        $paginator->setMaxPerPage($pageSize);
        $paginator->setCurrentPage($page);

        $paginator = new SerializablePagerfanta($paginator);

        return $this->createApiResponse($paginator);
    }

    /**
     * All project work packages.
     *
     * @Route("/work-packages-by-status", name="app_api_projects_workpackages_by_status", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function gridAction(Request $request, Project $project)
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
     * Create a new WorkPackage.
     *
     * @Route("/tasks", name="app_api_project_tasks_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, Project $project): JsonResponse
    {
        $wp = new WorkPackage();
        $wp->setProject($project);

        $form = $this->createForm(
            ApiCreateType::class,
            $wp,
            [
                'entity_manager' => $this->getDoctrine()->getManager(),
            ]
        );
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $errors = $this->getFormErrors($form);
            $errors = [
                'messages' => $errors,
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var WorkPackage $wp */
        $wp = $form->getData();
        $wp->setProject($project);
        $wp->setCreatedBy($this->getUser());

        foreach ($wp->getMedias() as $media) {
            $media->makeAsPermanent();
        }

        $wp->setForecastStartAt($wp->getScheduledStartAt());
        $wp->setForecastFinishAt($wp->getScheduledFinishAt());

        $this->dispatchEvent(WorkPackageEvents::PRE_CREATE, new WorkPackageEvent($wp));
        $this->get('app.repository.work_package')->add($wp);
        $this->dispatchEvent(WorkPackageEvents::POST_CREATE, new WorkPackageEvent($wp));

        return $this->createApiResponse($wp, Response::HTTP_CREATED);
    }
}
