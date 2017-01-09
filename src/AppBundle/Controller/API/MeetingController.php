<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Form\Meeting\CreateType;
use AppBundle\Security\ProjectVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/meeting")
 */
class MeetingController extends Controller
{
    /**
     * All meetings for a specific Project.
     *
     * @Route("/{id}/list", name="app_api_meeting_list")
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        $meetings = $this
            ->getDoctrine()
            ->getRepository(Meeting::class)
            ->findByProject($project);

        $meetingsArray = [];
        foreach ($meetings as $meeting) {
            $meetingsArray[] = $this->serialize($meeting);
        }

        return new JsonResponse($meetingsArray);
    }

    /**
     * Retrieve Meeting information.
     *
     * @Route("/{id}", name="app_api_meeting_get")
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function getAction(Meeting $meeting)
    {
        return new JsonResponse($this->serialize($meeting));
    }

    /**
     * Create a new Meeting.
     *
     * @Route("/create", name="app_api_meeting_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return new JsonResponse($this->serialize($form->getData()));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
    }

    /**
     * Edit a specific Meeting.
     *
     * @Route("/{id}/edit", name="app_api_meeting_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        if ($project = $meeting->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, $meeting, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return new JsonResponse($this->serialize($meeting));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
    }

    /**
     * Delete a specific Meeting.
     *
     * @Route("/{id}/delete", name="app_api_meeting_delete")
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function deleteAction(Meeting $meeting)
    {
        if ($project = $meeting->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($meeting);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create array with needed information from Meeting object.
     *
     * @param Meeting $meeting
     *
     * @return array
     */
    private function serialize(Meeting $meeting)
    {
        $info = [
            'id' => $meeting->getId(),
            'name' => $meeting->getName(),
            'project' => $meeting->getProject() ? $meeting->getProject()->getId() : null,
            'project_name' => $meeting->getProject() ? $meeting->getProject()->getName() : null,
            'location' => $meeting->getLocation(),
            'date' => $meeting->getDate() ? $meeting->getDate()->format('Y-m-d') : null,
            'start' => $meeting->getStart() ? $meeting->getStart()->format('H:i:s') : null,
            'end' => $meeting->getEnd() ? $meeting->getEnd()->format('H:i:s') : null,
            'objectives' => $meeting->getObjectives(),
            'meeting_participants' => [],
            'meeting_agendas' => [],
            'medias' => [],
            'decisions' => [],
            'todos' => [],
            'notes' => [],
        ];

        if (!$meeting->getMeetingParticipants()->isEmpty()) {
            foreach ($meeting->getMeetingParticipants() as $participant) {
                $info['meeting_participants'][] = [
                    'id' => $participant->getId(),
                    'user' => [
                        'id' => $participant->getUser() ? $participant->getUser()->getId() : null,
                        'name' => $participant->getUser() ? $participant->getUser()->getFullName() : null,
                    ],
                    'remark' => $participant->getRemark(),
                    'present' => $participant->getIsPresent(),
                ];
            }
        }

        if (!$meeting->getMeetingAgendas()->isEmpty()) {
            foreach ($meeting->getMeetingAgendas() as $agenda) {
                $info['meeting_agendas'][] = [
                    'id' => $agenda->getId(),
                    'topic' => $agenda->getTopic(),
                    'responsibility' => [
                        'id' => $agenda->getResponsibility() ? $agenda->getResponsibility()->getId() : null,
                        'name' => $agenda->getResponsibility() ? $agenda->getResponsibility()->getFullName() : null,
                    ],
                    'start' => $agenda->getStart() ? $agenda->getStart()->format('H:i:s') : null,
                    'end' => $agenda->getEnd() ? $agenda->getEnd()->format('H:i:s') : null,
                ];
            }
        }

        if (!$meeting->getMedias()->isEmpty()) {
            foreach ($meeting->getMedias() as $media) {
                $info['medias'][] = [
                    'id' => $media->getId(),
                    'path' => $media->getPath(),
                ];
            }
        }

        if (!$meeting->getDecisions()->isEmpty()) {
            foreach ($meeting->getDecisions() as $decision) {
                $info['decisions'][] = [
                    'id' => $decision->getId(),
                    'title' => $decision->getTitle(),
                    'description' => $decision->getDescription(),
                    'show_in_report' => $decision->getShowInStatusReport(),
                    'responsibility' => [
                        'id' => $decision->getResponsibility() ? $decision->getResponsibility()->getId() : null,
                        'name' => $decision->getResponsibility() ? $decision->getResponsibility()->getFullName() : null,
                    ],
                    'date' => $decision->getDate() ? $decision->getDate()->format('Y-m-d H:i:s') : null,
                    'due_date' => $decision->getDueDate() ? $decision->getDueDate()->format('Y-m-d H:i:s') : null,
                    'status' => $decision->getStatus() ? $decision->getStatus()->getName() : null,
                ];
            }
        }

        if (!$meeting->getTodos()->isEmpty()) {
            foreach ($meeting->getTodos() as $todo) {
                $info['todos'][] = [
                    'id' => $todo->getId(),
                    'title' => $todo->getTitle(),
                    'description' => $todo->getDescription(),
                    'show_in_report' => $todo->getShowInStatusReport(),
                    'responsibility' => [
                        'id' => $todo->getResponsibility() ? $todo->getResponsibility()->getId() : null,
                        'name' => $todo->getResponsibility() ? $todo->getResponsibility()->getFullName() : null,
                    ],
                    'date' => $todo->getDate() ? $todo->getDate()->format('Y-m-d H:i:s') : null,
                    'due_date' => $todo->getDueDate() ? $todo->getDueDate()->format('Y-m-d H:i:s') : null,
                    'status' => $todo->getStatus() ? $todo->getStatus()->getName() : null,
                ];
            }
        }

        if (!$meeting->getNotes()->isEmpty()) {
            foreach ($meeting->getNotes() as $note) {
                $info['notes'][] = [
                    'id' => $note->getId(),
                    'title' => $note->getTitle(),
                    'description' => $note->getDescription(),
                    'show_in_report' => $note->getShowInStatusReport(),
                    'responsibility' => [
                        'id' => $note->getResponsibility() ? $note->getResponsibility()->getId() : null,
                        'name' => $note->getResponsibility() ? $note->getResponsibility()->getFullName() : null,
                    ],
                    'date' => $note->getDate() ? $note->getDate()->format('Y-m-d H:i:s') : null,
                    'due_date' => $note->getDueDate() ? $note->getDueDate()->format('Y-m-d H:i:s') : null,
                    'status' => $note->getStatus() ? $note->getStatus()->getName() : null,
                ];
            }
        }

        return $info;
    }
}
