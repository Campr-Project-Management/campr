<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\CloseDownAction;
use AppBundle\Entity\EvaluationObjective;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\ProjectCloseDown;
use AppBundle\Form\ProjectCloseDown\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\EvaluationObjective\CreateType as EvaluationObjectiveType;
use AppBundle\Form\Lesson\CreateType as LessonType;
use AppBundle\Form\CloseDownAction\CreateType as CloseDownActionType;

/**
 * @Route("/api/project-close-downs")
 */
class ProjectCloseDownController extends ApiController
{
    /**
     * Edit a specific ProjectCloseDown.
     *
     * @Route("/{id}", name="app_api_project_close_downs_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $form = $this->createForm(CreateType::class, $projectCloseDown, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($projectCloseDown);

            return $this->createApiResponse($projectCloseDown, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All evaluation objectives for a specific project closedown.
     *
     * @Route("/{id}/evaluation-objectives", name="app_api_project_close_down_evaluation_objectives", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function evaluationObjectivesAction(ProjectCloseDown $projectCloseDown)
    {
        return $this->createApiResponse($projectCloseDown->getEvaluationObjectives());
    }

    /**
     * Create a new Evaluation Objective.
     *
     * @Route("/{id}/evaluation-objectives", name="app_api_project_close_down_evaluation_objectives_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function createEvaluationObjectiveAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $form = $this->createForm(EvaluationObjectiveType::class, new EvaluationObjective(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $evaluationObjective = $form->getData();
            $evaluationObjective->setProjectCloseDown($projectCloseDown);
            $this->persistAndFlush($evaluationObjective);

            return $this->createApiResponse($evaluationObjective, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All lessons for a specific project closedown.
     *
     * @Route("/{id}/lessons", name="app_api_project_close_down_lessons", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function lessonsAction(ProjectCloseDown $projectCloseDown)
    {
        return $this->createApiResponse($projectCloseDown->getLessons());
    }

    /**
     * Create a new Lesson.
     *
     * @Route("/{id}/lessons", name="app_api_project_close_down_lessons_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function createLessonAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $form = $this->createForm(LessonType::class, new Lesson(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $lesson = $form->getData();
            $lesson->setProjectCloseDown($projectCloseDown);
            $this->persistAndFlush($lesson);

            return $this->createApiResponse($lesson, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get all close down actions.
     *
     * @Route("/{id}/close-down-actions", name="app_api_project_close_down_actions", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function closeDownActionsAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $filters = $request->query->all();
        $closeDownActionRepo = $this->getDoctrine()->getRepository(CloseDownAction::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('admin.per_page');
            $result = $closeDownActionRepo->getQueryBuilderByProjectCloseDownAndFilters($projectCloseDown, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $closeDownActionRepo->countTotalByProjectCloseDownAndFilters($projectCloseDown, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $closeDownActionRepo->findByProjectCloseDown($projectCloseDown),
        ]);
    }

    /**
     * Create a new CloseDownAction.
     *
     * @Route("/{id}/close-down-actions", name="app_api_project_close_down_actions_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return JsonResponse
     */
    public function createCloseDownActionAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $form = $this->createForm(CloseDownActionType::class, new CloseDownAction(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $closeDownAction = $form->getData();
            $closeDownAction->setProjectCloseDown($projectCloseDown);
            $this->persistAndFlush($closeDownAction);

            return $this->createApiResponse($closeDownAction, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
