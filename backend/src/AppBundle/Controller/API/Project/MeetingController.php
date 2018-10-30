<?php

namespace AppBundle\Controller\API\Project;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Form\Meeting\ApiCreateType;
use AppBundle\Repository\MeetingRepository;
use Component\Meeting\MeetingEvent;
use Component\Meeting\MeetingEvents;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/projects/{id}")
 */
class MeetingController extends ApiController
{
    /**
     * All meetings for a specific Project.
     *
     * @Route("/meetings", name="app_api_project_meetings", options={"expose"=true})
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
        /** @var MeetingRepository $repo */
        $repo = $this->get('app.repository.meeting');

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
            $result = $repo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        $items = $repo->findBy(['project' => $project]);

        return $this->createApiResponse(
            [
                'items' => $items,
                'totalItems' => count($items),
            ]
        );
    }

    /**
     * Create a new Meeting.
     *
     * @Route("/meetings", name="app_api_project_meeting_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, Project $project)
    {
        $meeting = new Meeting();
        $meeting->setProject($project);
        $form = $this->createForm(
            ApiCreateType::class,
            $meeting,
            [
                'csrf_protection' => false,
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

        foreach ($meeting->getMedias() as $media) {
            $media->makeAsPermanent();
            $media->addMeeting($meeting);
        }

        $this->dispatchEvent(MeetingEvents::CALCULATE_MEETING_AGENDA_START_DATES, new MeetingEvent($meeting));
        $this->get('app.repository.meeting')->add($meeting);

        return $this->createApiResponse($meeting, Response::HTTP_CREATED);
    }
}
