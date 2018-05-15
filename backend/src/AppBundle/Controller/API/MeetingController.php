<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingAgenda;
use AppBundle\Entity\MeetingObjective;
use AppBundle\Entity\MeetingParticipant;
use AppBundle\Entity\Note;
use AppBundle\Entity\Todo;
use AppBundle\Entity\FileSystem;
use AppBundle\Form\Meeting\ApiCreateType;
use AppBundle\Security\MeetingVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\MeetingObjective\BaseType as MeetingObjectiveType;
use AppBundle\Form\MeetingAgenda\CreateType as MeetingAgendaType;
use AppBundle\Form\Decision\CreateType as DecisionType;
use AppBundle\Form\Todo\CreateType as TodoType;
use AppBundle\Form\Note\CreateType as NoteType;
use AppBundle\Form\MeetingParticipant\CreateType as MeetingParticipantType;

/**
 * @Route("/api/meetings")
 */
class MeetingController extends ApiController
{
    const ENTITY_CLASS = MeetingParticipant::class;
    const FORM_CLASS = MeetingParticipantType::class;

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
    public function getAction(Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::VIEW, $meeting);

        return $this->createApiResponse($meeting);
    }

    /**
     * Edit a specific Meeting.
     *
     * @Route("/{id}", name="app_api_meeting_edit", options={"expose"=true})
     * @Method({"POST", "PUT", "PATCH"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $this->denyAccessUnlessGranted(MeetingVoter::EDIT, $meeting);
        $project = $meeting->getProject();
        $form = $this->createForm(ApiCreateType::class, $meeting, ['csrf_protection' => false, 'method' => $request->getMethod()]);
        $this->processForm(
            $request,
            $form,
            in_array($request->getMethod(), [Request::METHOD_PUT, Request::METHOD_POST])
        );

        $em = $this->getDoctrine()->getManager();
        if ($project) {
            $fileSystem = $project
                ->getFileSystems()
                ->filter(function (FileSystem $fs) {
                    return $fs->getDriver() === FileSystem::LOCAL_ADAPTER;
                })
                ->first();

            if (!$fileSystem) {
                $fileSystem = $em
                    ->getRepository(FileSystem::class)
                    ->findOneBy([
                        'driver' => FileSystem::LOCAL_ADAPTER,
                    ]);
                if (!$fileSystem) {
                    return $this->createApiResponse(
                        [
                            'messages' => [
                                'Filesystem is missing. Please contact us.',
                            ],
                        ],
                        Response::HTTP_INTERNAL_SERVER_ERROR
                    );
                }
            }

            if ($form->isValid()) {
                foreach ($meeting->getMedias() as $media) {
                    $media->setFileSystem($fileSystem);
                }
                $meeting->setProject($project);
                $this->persistAndFlush($meeting);

                return $this->createApiResponse(
                    $meeting,
                    $request->isMethod(Request::METHOD_POST)
                        ? Response::HTTP_OK
                        : Response::HTTP_ACCEPTED
                );
            }
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
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
            ->getRepository(MeetingAgenda::class)
        ;

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $result = $repo->getQueryBuilderByMeetingAndFilters($meeting, $filters)->getQuery()->getResult();

            $responseArray['totalItems'] = $repo->countTotalByMeetingAndFilters($meeting, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        $items = $repo->findBy(['meeting' => $meeting]);

        return $this->createApiResponse([
            'items' => $items,
            'totalItems' => count($items),
        ]);
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
        $form = $this->createForm(DecisionType::class, new Decision(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $decision = $form->getData();
            $decision->setMeeting($meeting);
            $decision->setProject($meeting->getProject());
            $this->persistAndFlush($decision);

            return $this->createApiResponse($decision, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
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
     * Create a new Note(Info).
     *
     * @Route("/{id}/notes", name="app_api_meeting_notes_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createNoteAction(Request $request, Meeting $meeting)
    {
        $form = $this->createForm(NoteType::class, new Note(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $note = $form->getData();
            $note->setMeeting($meeting);
            $this->persistAndFlush($note);

            return $this->createApiResponse($note, Response::HTTP_CREATED);
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
     * Update meeting participant.
     *
     * @Route("/{id}/participant-update", name="app_api_meeting_participant_update", options={"expose"=true})
     * @Method({"POST", "PATCH"})
     *
     * @param Request            $request
     * @param MeetingParticipant $meetingParticipant
     *
     * @return JsonResponse
     */
    public function meetingParticipantsUpdateAction(Request $request, MeetingParticipant $meetingParticipant)
    {
        $form = $this->getForm($meetingParticipant, ['method' => $request->getMethod(), 'csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $this->persistAndFlush($meetingParticipant);

            return $this->createApiResponse($meetingParticipant, Response::HTTP_ACCEPTED);
        }

        return $this->createApiResponse(null, Response::HTTP_BAD_REQUEST);
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
                    $meetingParticipant = $em->getRepository(MeetingParticipant::class)->findOneBy([
                        'meeting' => $meeting,
                        'user' => $user,
                    ]);
                    if ($meetingParticipant) {
                        $meetingParticipant->setIsPresent($participant['isPresent']);
                    } else {
                        $meetingParticipant = (new MeetingParticipant())
                            ->setMeeting($meeting)
                            ->setUser($user)
                            ->setIsPresent($participant['isPresent'])
                        ;
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
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function notificationsAction(Meeting $meeting)
    {
        $participants = $meeting->getMeetingParticipants();
        $distributionLists = $meeting->getDistributionLists();
        $mailerService = $this->get('app.service.mailer');
        foreach ($participants as $participant) {
            $user = $participant->getUser();
            $mailerService->sendEmail(
                ':meeting:notification.html.twig',
                'info',
                $user->getEmail(),
                ['meeting' => $meeting]
            );
        }
        foreach ($distributionLists as $distributionList) {
            $users = $distributionList->getUsers();
            foreach ($users as $user) {
                $mailerService->sendEmail(
                    ':meeting:notification.html.twig',
                    'info',
                    $user->getEmail(),
                    ['meeting' => $meeting]
                );
            }
        }

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
