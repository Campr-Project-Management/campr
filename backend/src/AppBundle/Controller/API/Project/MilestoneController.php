<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\MilestoneType;
use AppBundle\Repository\WorkPackageRepository;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/projects/{id}")
 */
class MilestoneController extends ApiController
{
    /**
     * @Route("/milestones", name="app_api_project_milestones", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function indexAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        /** @var WorkPackageRepository $repo */
        $repo = $this->get('app.repository.work_package');

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize'])
                ? $filters['pageSize']
                : $this->getParameter('front.per_page');
            $filters['type'] = WorkPackage::TYPE_MILESTONE;

            $result = $repo->getQueryByProjectAndFilters($project, $filters)->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => $repo->findMilestonesByProject($project),
                'totalItems' => $repo->countMilestonesByProject($project),
            ]
        );
    }

    /**
     * @Route("/milestones", name="app_api_project_milestones_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createMilestoneAction(Request $request, Project $project)
    {
        $wp = new WorkPackage();
        $wp->setProject($project);

        $form = $this->createForm(MilestoneType::class, $wp, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $milestone = $form->getData();
            $milestone->setProject($project);
            $this->persistAndFlush($milestone);

            return $this->createApiResponse($milestone, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/milestones/{milestone}", name="app_api_workpackage_milestone_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request     $request
     * @param WorkPackage $milestone
     *
     * @return JsonResponse
     */
    public function editMilestoneAction(Request $request, WorkPackage $milestone)
    {
        $form = $this->createForm(MilestoneType::class, $milestone, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($milestone);

            return $this->createApiResponse($milestone, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
