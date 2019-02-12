<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\PhaseType;
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
class PhaseController extends ApiController
{
    /**
     * @Route("/phases", name="app_api_project_phases", options={"expose"=true})
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
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
            $filters['type'] = WorkPackage::TYPE_PHASE;

            $result = $repo->getQueryByProjectAndFilters($project, $filters)->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => $repo->findPhasesByProject($project),
                'totalItems' => $repo->countPhasesByProject($project),
            ]
        );
    }

    /**
     * @Route("/phases", name="app_api_project_phases_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, Project $project)
    {
        $wp = new WorkPackage();
        $wp->setProject($project);

        $form = $this->createForm(PhaseType::class, $wp, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $phase = $form->getData();
            $phase->setProject($project);
            $this->persistAndFlush($phase);

            return $this->createApiResponse($phase, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/phases/{phase}", name="app_api_workpackage_phase_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request     $request
     * @param WorkPackage $phase
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackage $phase)
    {
        $form = $this->createForm(PhaseType::class, $phase, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($phase);

            return $this->createApiResponse($phase, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
