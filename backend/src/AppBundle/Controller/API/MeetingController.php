<?php

namespace AppBundle\Controller\API;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\Decision;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Media;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAgenda;
use AppBundle\Entity\MeetingObjective;
use AppBundle\Entity\MeetingParticipant;
use AppBundle\Entity\MeetingReport;
use AppBundle\Entity\Project;
use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Form\Meeting\ApiCreateType;
use AppBundle\Form\MeetingReport\CreateType as ReportCreateType;
use AppBundle\Security\MeetingVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Component\MeetingAgenda\MeetingAgendaEvent;
use Component\MeetingAgenda\MeetingAgendaEvents;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\MeetingObjective\BaseType as MeetingObjectiveType;
use AppBundle\Form\MeetingAgenda\CreateType as MeetingAgendaType;
use AppBundle\Form\Decision\ApiCreateType as DecisionType;
use AppBundle\Form\Todo\CreateType as TodoType;

/**
 * @Route("/api/meetings")
 */
class MeetingController extends ApiController
{
    /**
     * Retrieve Meeting information.
     *
     * @Route("/{id}", name="app_api_meeting_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function getAction(Request $request, Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::VIEW, $meeting);

        $meeting->setJitsiLink($request->getHost());

        return $this->createApiResponse($meeting);
    }

    /**
     * Edit a specific Meeting.
     *
     * @Route("/{id}", name="app_api_meeting_edit", options={"expose"=true}, methods={"POST", "PUT", "PATCH"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::EDIT, $meeting);
        $form = $this->createForm(
            ApiCreateType::class,
            $meeting,
            [
                'csrf_protection' => false,
                'method' => $request->getMethod(),
            ]
        );

        $originalMedias = new ArrayCollection();
        foreach ($meeting->getMedias() as $media) {
            $originalMedias->add($media);
        }

        $this->processForm($request, $form, !$request->isMethod('PATCH'));

        if (!$form->isValid()) {
            $errors = $this->getFormErrors($form);
            $errors = [
                'messages' => $errors,
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        foreach ($meeting->getMedias() as $media) {
            $media->makeAsPermanent();
            $media->addMeeting($meeting);
        }

        /** @var Media $media */
        foreach ($originalMedias as $media) {
            if (!$meeting->getMedias()->contains($media)) {
                $media->makeAsTemporary(0);
                $media->removeMeeting($meeting);
                $em->persist($media);
            }
        }

        $this->get('app.repository.meeting')->add($meeting);

        return $this->createApiResponse(
            $meeting,
            $request->isMethod(Request::METHOD_POST) ? Response::HTTP_OK : Response::HTTP_ACCEPTED
        );
    }

    /**
     * Delete a specific Meeting.
     *
     * @Route("/{id}", name="app_api_meeting_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function deleteAction(Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::DELETE, $meeting);

        $em = $this->getDoctrine()->getManager();
        $em->remove($meeting);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Retrieve Meeting Agendas.
     *
     * @Route("/{id}/agendas", name="app_api_meeting_agendas_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingAgendasAction(Request $request, Meeting $meeting)
    {
        $filters = $request->query->all();
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(MeetingAgenda::class);

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
            $result = $repo->getQueryBuilderByMeetingAndFilters($meeting, $filters)->getQuery()->getResult();

            $responseArray['totalItems'] = $repo->countTotalByMeetingAndFilters($meeting, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        $items = $repo->findBy(['meeting' => $meeting]);

        return $this->createApiResponse(
            [
                'items' => $items,
                'totalItems' => count($items),
            ]
        );
    }

    /**
     * Create new meeting agenda.
     *
     * @Route("/{id}/agendas", name="app_api_meeting_agendas_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createMeetingAgendaAction(Request $request, Meeting $meeting)
    {
        $form = $this->createForm(MeetingAgendaType::class, new MeetingAgenda(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $meetingAgenda = $form->getData();
            $meetingAgenda->setMeeting($meeting);

            $this->dispatchEvent(MeetingAgendaEvents::CALCULATE_START_DATE, new MeetingAgendaEvent($meetingAgenda));

            $this->persistAndFlush($meetingAgenda);

            return $this->createApiResponse($meetingAgenda, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create new meeting objective.
     *
     * @Route("/{id}/objectives", name="app_api_meeting_objectives_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createMeetingObjectiveAction(Request $request, Meeting $meeting)
    {
        $form = $this->createForm(MeetingObjectiveType::class, new MeetingObjective(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $meetingObjective = $form->getData();
            $meetingObjective->setMeeting($meeting);
            $this->persistAndFlush($meetingObjective);

            return $this->createApiResponse($meetingObjective, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create new decision.
     *
     * @Route("/{id}/decisions", name="app_api_meeting_decisions_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createDecisionAction(Request $request, Meeting $meeting)
    {
        $decision = new Decision();
        $form = $this->createForm(
            DecisionType::class,
            $decision,
            [
                'allow_extra_fields' => true,
                'entity_manager' => $this->getDoctrine()->getManager(),
            ]
        );

        $this->processForm($request, $form);

        $decision->setMeeting($meeting);
        if (!$form->isValid()) {
            $errors = $this->getFormErrors($form);
            $errors = [
                'messages' => $errors,
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        foreach ($decision->getMedias() as $media) {
            $media->makeAsPermanent();
        }

        $this->get('app.repository.decisions')->add($decision);

        return $this->createApiResponse($decision, Response::HTTP_CREATED);
    }

    /**
     * Create a new Todo.
     *
     * @Route("/{id}/todos", name="app_api_meeting_todos_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createTodoAction(Request $request, Meeting $meeting)
    {
        $form = $this->createForm(TodoType::class, new Todo(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $todo = $form->getData();
            $todo->setMeeting($meeting);
            $this->persistAndFlush($todo);

            return $this->createApiResponse($todo, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Retrieve Meeting Participants.
     *
     * @Route("/{id}/participants", name="app_api_meeting_participants", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function participantsAction(Meeting $meeting)
    {
        return $this->createApiResponse($meeting->getMeetingParticipants(), Response::HTTP_OK);
    }

    /**
     * Bulk Update meeting participants.
     *
     * @Route("/{id}/participants", name="app_api_meeting_participants_update", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function participantsBulkUpdateAction(Request $request, Meeting $meeting)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        if (isset($data['participants'])) {
            foreach ($data['participants'] as $participant) {
                if (isset($participant['user']) && isset($participant['isPresent'])) {
                    $user = $em->getRepository(User::class)->find($participant['user']);
                    $meetingParticipant = $em->getRepository(MeetingParticipant::class)->findOneBy(
                        [
                            'meeting' => $meeting,
                            'user' => $user,
                        ]
                    );
                    if ($meetingParticipant) {
                        $meetingParticipant->setIsPresent($participant['isPresent']);
                    } else {
                        $meetingParticipant = (new MeetingParticipant())
                            ->setMeeting($meeting)
                            ->setUser($user)
                            ->setIsPresent($participant['isPresent']);
                    }
                    $this->persistAndFlush($meetingParticipant);
                } else {
                    return $this->createApiResponse(null, Response::HTTP_BAD_REQUEST);
                }
            }

            return $this->createApiResponse(null, Response::HTTP_ACCEPTED);
        }

        return $this->createApiResponse(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send notification to participants.
     *
     * @Route("/{id}/notifications", name="app_api_meeting_notifications", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Meeting $meeting
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function notificationsAction(Meeting $meeting, Request $request)
    {
        $host = $request->getHttpHost();

        /** @var User $user */
        $user = $this->getUser();

        $command = strtr(
            '--env=%env% app:meeting:send-notification %meetingId% %userId% %host%',
            [
                '%env%' => $this->getParameter('kernel.environment'),
                '%userId%' => $user->getId(),
                '%meetingId%' => $meeting->getId(),
                '%host%' => $host,
            ]
        );

        $this
            ->get('redis.client')
            ->rpush(RedisQueueManagerCommand::DEFAULT, [$command]);

        return $this->createApiResponse(
            [
                'message' => $this->get('translator')->trans('message.email_successfully_sent'),
            ],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param Project $project
     *
     * @return FileSystem
     *
     * @throws \Exception
     */
    private function getFileSystem(Project $project): FileSystem
    {
        /** @var FileSystemResolver $fs */
        $fsResolver = $this->get('app.fs.resolver');
        $fs = $fsResolver->resolve($project);

        if ($fs) {
            return $fs;
        }

        throw new \Exception('Filesystem is missing. Please contact us.');
    }

    /**
     * Send meeting report to participants.
     *
     * @Route("/{id}/report", name="app_api_meeting_report", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Meeting $meeting
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function reportAction(Meeting $meeting, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ReportCreateType::class, new MeetingReport(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $meetingReport = $form->getData();
            $meetingReport->setMeeting($meeting);
            $meetingReport->setCreatedBy($user);

            $this->persistAndFlush($meetingReport);

            $host = $request->getHttpHost();

            $command = strtr(
                '--env=%env% app:meeting:send-report %meetingId% %userId% %meetingReportId% %host%',
                [
                    '%env%' => $this->getParameter('kernel.environment'),
                    '%userId%' => $user->getId(),
                    '%meetingId%' => $meeting->getId(),
                    '%meetingReportId%' => $meetingReport->getId(),
                    '%host%' => $host,
                ]
            );

            $this
                ->get('redis.client')
                ->rpush(RedisQueueManagerCommand::DEFAULT, [$command])
            ;

            return $this->createApiResponse(
                [
                    'message' => $this->get('translator')->trans('message.email_successfully_sent'),
                ],
                Response::HTTP_NO_CONTENT
            );
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Retrieve Last Meeting Report.
     *
     * @Route("/{id}/reports/last", name="app_api_meeting_reports_last", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingReportsLastAction(Meeting $meeting)
    {
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(MeetingReport::class)
        ;

        $report = $repo->findLastByMeeting($meeting);

        return $this->createApiResponse($report);
    }
}
