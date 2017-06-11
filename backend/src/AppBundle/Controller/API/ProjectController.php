<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Contract;
use AppBundle\Entity\Cost;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Label;
use AppBundle\Entity\Measure;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Note;
use AppBundle\Entity\Opportunity;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectDeliverable;
use AppBundle\Entity\ProjectLimitation;
use AppBundle\Entity\ProjectObjective;
use AppBundle\Entity\ProjectStatus;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Risk;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\Todo;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\Unit;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Form\Label\BaseLabelType;
use AppBundle\Form\Project\CreateType;
use AppBundle\Form\Calendar\BaseCreateType as CalendarCreateType;
use AppBundle\Form\Contract\BaseCreateType as ContractCreateType;
use AppBundle\Form\DistributionList\BaseCreateType as DistributionCreateType;
use AppBundle\Form\Meeting\BaseCreateType as MeetingCreateType;
use AppBundle\Form\Note\BaseCreateType as NoteCreateType;
use AppBundle\Form\ProjectTeam\BaseCreateType as ProjectTeamCreateType;
use AppBundle\Form\ProjectUser\BaseCreateType as ProjectUserCreateType;
use AppBundle\Form\Subteam\CreateType as SubteamCreateType;
use AppBundle\Form\Todo\BaseCreateType as TodoCreateType;
use AppBundle\Form\ProjectObjective\CreateType as ProjectObjectiveCreateType;
use AppBundle\Form\ProjectDeliverable\CreateType as ProjectDeliverableCreateType;
use AppBundle\Form\ProjectLimitation\CreateType as ProjectLimitationCreateType;
use AppBundle\Form\Unit\CreateType as UnitCreateType;
use AppBundle\Form\User\ApiCreateType as UserApiCreateType;
use AppBundle\Form\WorkPackage\ApiCreateType;
use AppBundle\Form\WorkPackage\MilestoneType;
use AppBundle\Form\WorkPackage\PhaseType;
use AppBundle\Form\Risk\CreateType as RiskCreateType;
use AppBundle\Form\Opportunity\BaseType as OpportunityCreateType;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Security\ProjectVoter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

/**
 * @Route("/api/projects")
 */
class ProjectController extends ApiController
{
    /**
     * TODO: Add filters.
     *
     * Get all projects.
     *
     * @Route(name="app_api_project_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $filters = $request->query->all();

        $projects = $em
            ->getRepository(Project::class)
            ->findByUserAndFilters($this->getUser(), $filters)
        ;

        $responseArray = [];
        $responseArray['totalItems'] = count($projects);
        $responseArray['items'] = $projects;

        return $this->createApiResponse($responseArray);
    }

    /**
     * Create a new Project.
     *
     * @Route(name="app_api_project_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(CreateType::class, $project, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        $project->setLogoFile($request->files->get('logoFile'));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$project->getStatus()) {
                $projectStatus = $em
                    ->getRepository(ProjectStatus::class)
                    ->find(ProjectStatus::STATUS_NOT_STARTED)
                ;

                $project->setStatus($projectStatus);
            }

            $em->persist($project);
            $em->flush();

            return $this->createApiResponse($project, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Project by id.
     *
     * @Route("/{id}", name="app_api_project_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function getAction(Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->createApiResponse($project);
    }

    /**
     * Edit a specific Project.
     *
     * @Route("/{id}", name="app_api_project_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(CreateType::class, $project, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->createApiResponse($project, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project.
     *
     * @Route("/{id}", name="app_api_project_delete")
     * @Method({"DELETE"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function deleteAction(Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * All Calendars for a specific Project.
     *
     * @Route("/{id}/calendars", name="app_api_project_calendars")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function calendarsAction(Project $project)
    {
        return $this->createApiResponse($project->getCalendars());
    }

    /**
     * Create a new Calendar.
     *
     * @Route("/{id}/calendars", name="app_api_project_create_calendar")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createCalendarAction(Request $request, Project $project)
    {
        $calendar = new Calendar();
        $calendar->setProject($project);

        $form = $this->createForm(CalendarCreateType::class, $calendar, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            return $this->createApiResponse($calendar, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All Contracts for a specific Project.
     *
     * @Route("/{id}/contracts", name="app_api_project_contracts", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function contractsAction(Project $project)
    {
        return $this->createApiResponse($project->getContracts());
    }

    /**
     * Create a new Contract.
     *
     * @Route("/{id}/contracts", name="app_api_project_create_contract", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createContractAction(Request $request, Project $project)
    {
        $contract = new Contract();
        $contract->setProject($project);

        $form = $this->createForm(ContractCreateType::class, $contract, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $contract->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->createApiResponse($contract, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get all distribution lists for a specific Project.
     *
     * @Route("/{id}/distribution-lists", name="app_api_project_distribution_lists")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function distributionListsAction(Project $project)
    {
        return $this->createApiResponse($project->getDistributionLists());
    }

    /**
     * Create a new DistributionList.
     *
     * @Route("/{id}/distribution-lists", name="app_api_project_distribution_list_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createDistributionListAction(Request $request, Project $project)
    {
        $form = $this->createForm(DistributionCreateType::class, new DistributionList(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $distributionList = $form->getData();
            $distributionList->setCreatedBy($this->getUser());
            $distributionList->setProject($project);
            $em->persist($distributionList);
            $em->flush();

            return $this->createApiResponse($distributionList, JsonResponse::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All labels for a specific Project.
     *
     * @Route("/{id}/labels", name="app_api_project_labels", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function labelsAction(Project $project)
    {
        return $this->createApiResponse($project->getLabels());
    }

    /**
     * Create a new Label.
     *
     * @Route("/{id}/labels", name="app_api_project_create_label", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createLabelAction(Request $request, Project $project)
    {
        $label = new Label();
        $label->setProject($project);

        $form = $this->createForm(BaseLabelType::class, $label, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($label);
            $em->flush();

            return $this->createApiResponse($label, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All meetings for a specific Project.
     *
     * @Route("/{id}/meetings", name="app_api_project_meetings")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function meetingsAction(Project $project)
    {
        return $this->createApiResponse($project->getMeetings());
    }

    /**
     * Create a new Meeting.
     *
     * @Route("/{id}/meetings", name="app_api_project_meeting_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createMeetingAction(Request $request, Project $project)
    {
        $meeting = new Meeting();
        $meeting->setProject($project);

        $form = $this->createForm(MeetingCreateType::class, $meeting, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return $this->createApiResponse($meeting, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All notes for the current project.
     *
     * @Route("/{id}/notes", name="app_api_project_notes")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function notesAction(Project $project)
    {
        return $this->createApiResponse($project->getNotes());
    }

    /**
     * Create a new Note.
     *
     * @Route("/{id}/notes", name="app_api_project_note_create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createNoteAction(Request $request, Project $project)
    {
        $note = new Note();
        $note->setProject($project);

        $form = $this->createForm(NoteCreateType::class, $note, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->createApiResponse($note, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get all project teams.
     *
     * @Route("/{id}/project-teams", name="app_api_project_project_teams")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function projectTeamsAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectTeams());
    }

    /**
     * Create a new Project Team.
     *
     * @Route("/{id}/project-teams", name="app_api_project_project_team_create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createProjectTeamAction(Request $request, Project $project)
    {
        $projectTeam = new ProjectTeam();
        $projectTeam->setProject($project);

        $form = $this->createForm(ProjectTeamCreateType::class, $projectTeam, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get all project users.
     *
     * @Route("/{id}/project-users", name="app_api_project_project_users", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function projectUsersAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        if (empty($filters)) {
            return $this->createApiResponse(
                ['items' => $project->getProjectUsers()]
            );
        }

        $filters['project'] = $project;
        $usersQuery = $this
            ->getDoctrine()
            ->getRepository(ProjectUser::class)
            ->getQueryByUserFullName($filters)
        ;

        $pageSize = $this->getParameter('front.per_page');
        $currentPage = isset($filters['page']) ? $filters['page'] : 1;
        $paginator = new Paginator($usersQuery);
        if (!isset($filters['search'])) {
            $paginator
                ->getQuery()
                ->setFirstResult($pageSize * ($currentPage - 1))
                ->setMaxResults($pageSize)
            ;
        }
        $responseArray['totalItems'] = count($paginator);
        $responseArray['items'] = $paginator->getIterator()->getArrayCopy();

        return $this->createApiResponse($responseArray);
    }

    /**
     * Create a new Project User.
     *
     * @Route("/{id}/project-users", name="app_api_project_project_user_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createProjectUserAction(Request $request, Project $project)
    {
        $projectUser = new ProjectUser();
        $projectUser->setProject($project);

        $form = $this->createForm(ProjectUserCreateType::class, $projectUser, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectUser);
            $em->flush();

            return $this->createApiResponse($projectUser, JsonResponse::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create a new Team Member.
     *
     * @Route("/{id}/team-members", name="app_api_project_team_member_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createTeamMemberAction(Request $request, Project $project)
    {
        $user = new User();
        $form = $this->createForm(UserApiCreateType::class, $user, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            // @TODO: refactor to use a form and model
            $projectUser = new ProjectUser();
            $projectUser->setProject($project);
            $projectUser->setShowInOrg($form->get('showInOrg')->getData());
            $projectUser->setShowInRaci($form->get('showInRaci')->getData());
            $projectUser->setShowInResources($form->get('showInResources')->getData());
            $user->addProjectUser($projectUser);

            $request->request->set(
                'subdomain',
                $this->getParameter('kernel.team_slug')
            );

            $departments = $form->get('departments')->getData();
            foreach ($projectUser->getProjectDepartments() as $projectDepartment) {
                $projectUser->removeProjectDepartment($projectDepartment);
            }
            foreach ($departments as $department) {
                $projectUser->addProjectDepartment($department);
            }

            $res = $this
                ->get('app.service.user')
                ->createTeamMember($request)
            ;

            if ($res) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                return $this->createApiResponse(
                    [
                        'messages' => [
                            $this
                                ->get('translator')
                                ->trans('exception.user.unable_to_create', [], 'messages'),
                        ],
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }

            return $this->createApiResponse($user, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * All project Todos.
     *
     * @Route("/{id}/todos", name="app_api_projects_todos")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function todosAction(Project $project)
    {
        return $this->createApiResponse($project->getTodos());
    }

    /**
     * Create a new Todo.
     *
     * @Route("/{id}/todos", name="app_api_projects_todo_create")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createTodoAction(Request $request, Project $project)
    {
        $todo = new Todo();
        $todo->setProject($project);

        $form = $this->createForm(TodoCreateType::class, $todo, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->createApiResponse($todo, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All wppwct for a specific Project.
     *
     * @Route("/{id}/wppwcts", name="app_api_projects_wppwcts")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function wppwctsAction(Project $project)
    {
        $wppwcts = $this
            ->getDoctrine()
            ->getRepository(WorkPackageProjectWorkCostType::class)
            ->findByProject($project)
        ;

        return $this->createApiResponse($wppwcts);
    }

    /**
     * Retrieve calendar files for users.
     *
     * @Route("/{id}/export-calendars", name="app_api_users_export_calendars", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function exportCalendarsAction(Request $request, Project $project)
    {
        if (!$this->getUser()) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
            ], Response::HTTP_NOT_FOUND);
        }

        $filters = $request->query->all();
        $filters['project'] = $project->getName();

        return $this->get('app.service.calendar')->exportUserCalendars($filters, $this->getUser());
    }

    /**
     * All project objectives for a specific Project.
     *
     * @Route("/{id}/objectives", name="app_api_project_objectives", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function objectivesAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectObjectives());
    }

    /**
     * Create a new ProjectObjective.
     *
     * @Route("/{id}/objectives", name="app_api_project_create_objective", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createObjectiveAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectObjectiveCreateType::class, new ProjectObjective(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $projectObjective = $form->getData();
            $projectObjective->setProject($project);
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectObjective);
            $em->flush();

            return $this->createApiResponse($projectObjective, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All project limitations for a specific Project.
     *
     * @Route("/{id}/limitations", name="app_api_project_limitations", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function limitationsAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectLimitations());
    }

    /**
     * Create a new ProjectLimitation.
     *
     * @Route("/{id}/limitations", name="app_api_project_create_limitation", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createLimitationAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectLimitationCreateType::class, new ProjectLimitation(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $projectLimitation = $form->getData();
            $projectLimitation->setProject($project);
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectLimitation);
            $em->flush();

            return $this->createApiResponse($projectLimitation, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All project deliverables for a specific Project.
     *
     * @Route("/{id}/deliverables", name="app_api_project_deliverables", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function deliverablesAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectDeliverables());
    }

    /**
     * Create a new ProjectDeliverable.
     *
     * @Route("/{id}/deliverables", name="app_api_project_create_deliverable", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createDeliverableAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectDeliverableCreateType::class, new ProjectDeliverable(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $projectDeliverable = $form->getData();
            $projectDeliverable->setProject($project);
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectDeliverable);
            $em->flush();

            return $this->createApiResponse($projectDeliverable, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}/resources", name="app_api_project_resources", options={"expose"=true})
     * @Method({"GET"})
     */
    public function resourcesAction(Project $project)
    {
        return $this->createApiResponse($project->getResources());
    }

    /**
     * All project resources costs.
     *
     * @Route("/{id}/resources-graph", name="app_api_project_resources_graph", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function resourcesGraphAction(Project $project)
    {
        // @TODO: replace with \AppBundle\Entity\Resource AND \AppBundle\Entity\Cost
        return $this->createApiResponse(
            [
                'internal' => $this
                    ->getDoctrine()
                    ->getRepository(WorkPackageProjectWorkCostType::class)
                    ->costsByProject($project),
                'external' => $this
                    ->getDoctrine()
                    ->getRepository(WorkPackageProjectWorkCostType::class)
                    ->costsByProject($project, false),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/{id}/phases", name="app_api_project_phases", options={"expose"=true})
     * @Method({"GET"})
     */
    public function phasesAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        /** @var WorkPackageRepository $repo */
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(WorkPackage::class)
        ;
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $filters['type'] = WorkPackage::TYPE_PHASE;

            $result = $repo->getQueryByProjectAndFilters($project, $filters)->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $repo->findPhasesByProject($project),
            'totalItems' => $repo->countPhasesByProject($project),
        ]);
    }

    /**
     * @Route("/{id}/phases", name="app_api_project_phases_create", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createPhaseAction(Request $request, Project $project)
    {
        $form = $this->createForm(PhaseType::class, new WorkPackage(), ['csrf_protection' => false]);
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
     * @Route("/{id}/milestones", name="app_api_project_milestones", options={"expose"=true})
     * @Method({"GET"})
     */
    public function milestonesAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        /** @var WorkPackageRepository $repo */
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(WorkPackage::class)
        ;
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $filters['type'] = WorkPackage::TYPE_MILESTONE;

            $result = $repo->getQueryByProjectAndFilters($project, $filters)->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $repo->findMilestonesByProject($project),
            'totalItems' => $repo->countMilestonesByProject($project),
        ]);
    }

    /**
     * @Route("/{id}/milestones", name="app_api_project_milestones_create", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createMilestoneAction(Request $request, Project $project)
    {
        $form = $this->createForm(MilestoneType::class, new WorkPackage(), ['csrf_protection' => false]);
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
     * @Route("/{id}/tasks", name="app_api_project_tasks", options={"expose"=true})
     * @Method({"GET"})
     */
    public function tasksAction(Request $request, Project $project)
    {
        /** @var WorkPackageRepository $repo */
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(WorkPackage::class)
        ;

        $filters = $request->query->get('filters', []);

        $orderBy = [];
        if ($request->query->get('order')) {
            $key = $request->get('sort', 'createdAt');
            $orderBy[$key] = $request->query->get('order');
        }

        $limit = null;
        if ($request->query->get('limit')) {
            $limit = intval($request->query->get('limit'));
            if ($limit < 1) {
                $limit = 12;
            }
        }

        if (isset($filters['milestone'])) {
            /** @var WorkPackage $milestone */
            $milestone = $repo->findOneBy([
                'id' => $filters['milestone'],
                'type' => WorkPackage::TYPE_MILESTONE,
            ]);

            if (!$milestone) {
                throw $this->createNotFoundException();
            }

            return $this->createApiResponse([
                'items' => $repo->findTasksByMilestone(
                    $milestone,
                    $orderBy,
                    $limit
                ),
                'totalItems' => $repo->countTasksByMilestone($milestone),
            ]);
        }

        return $this->createApiResponse([
            'items' => $repo->findTasksByProject(
                $project,
                $orderBy,
                $limit
            ),
            'totalItems' => $repo->countTasksByProject($project),
        ]);
    }

    /**
     * @Route("/{id}/schedule", name="app_api_project_schedule", options={"expose"=true})
     * @Method({"GET"})
     */
    public function scheduleAction(Project $project)
    {
        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getRepository(WorkPackage::class)
                ->getScheduleForProjectSchedule($project)
        );
    }

    /**
     * @Route("/{id}/tasks-status", name="app_api_project_tasks_status", options={"expose"=true})
     * @Method({"GET"})
     */
    public function tasksStatusAction(Project $project)
    {
        $response = [];
        $statuses = $this->getDoctrine()->getRepository(WorkPackageStatus::class)->findAll();
        $wpRepo = $this->getDoctrine()->getRepository(WorkPackage::class);
        $response['message.total_tasks'] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project);
        foreach ($statuses as $status) {
            $response[$status->getName()] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project, $status);
        }

        return $this->createApiResponse($response);
    }

    /**
     * Create a new WorkPackage.
     *
     * @Route("/{id}/tasks", name="app_api_project_tasks_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createTaskAction(Request $request, Project $project)
    {
        $wp = new WorkPackage();
        $form = $this->createForm(ApiCreateType::class, $wp);
        $this->processForm($request, $form);

        // @TODO: Make filesystem selection dynamic
        $em = $this->getDoctrine()->getManager();
        $fileSystem = $project
            ->getFileSystems()
            ->filter(function (FileSystem $fs) {
                return $fs->getDriver() === FileSystem::LOCAL_ADAPTER;
            })
            ->first()
        ;

        if (!$fileSystem) {
            $fileSystem = $em
                ->getRepository(FileSystem::class)
                ->findOneBy([
                    'driver' => FileSystem::LOCAL_ADAPTER,
                ])
            ;
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
            foreach ($wp->getMedias() as $media) {
                $media->setFileSystem($fileSystem);
            }
            $wp->setProject($project);
            $wp->setPuid(microtime(true) * 1000000); // remove when listener is fixed
            $this->persistAndFlush($wp);

            // VueJS sucks at interpreting 201 as a success
            return $this->createApiResponse($wp, Response::HTTP_OK);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All project units.
     *
     * @Route("/{id}/units", name="app_api_project_units", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function unitsAction(Project $project)
    {
        $units = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Unit::class)
            ->findByProject($project, true)
        ;

        return $this->createApiResponse($units);
    }

    /**
     * Create a new Unit.
     *
     * @Route("/{id}/units", name="app_api_project_create_unit", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createUnitAction(Request $request, Project $project)
    {
        $form = $this->createForm(UnitCreateType::class, new Unit());
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $unit = $form->getData();
            $unit->setProject($project);
            $this->persistAndFlush($unit);

            return $this->createApiResponse($unit, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * All project work packages.
     *
     * @Route("/{id}/work-packages", name="app_api_projects_workpackages", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function workPackagesAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $pageSize = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');

        if (isset($filters['status']) || isset($filters['isGrid'])) {
            $wpRepo = $this
                ->getDoctrine()
                ->getRepository(WorkPackage::class)
            ;

            $paginator = new Paginator($wpRepo->getQueryByProjectAndFiltersSortedByStatus($project, $filters));

            $responseArray['totalItems'] = $paginator->count();
            $responseArray['items'] = $wpRepo->getQueryByProjectAndFiltersSortedByStatus($project, $filters)->getResult();
        } else {
            $workPackageStatuses = $this
                ->getDoctrine()
                ->getRepository(WorkPackageStatus::class)
                ->findBy([
                    'project' => null,
                ])
            ;

            foreach ($workPackageStatuses as $workPackageStatus) {
                $filters['status'] = $workPackageStatus;
                $wpQuery = $this
                    ->getDoctrine()
                    ->getRepository(WorkPackage::class)
                    ->getQueryByProjectAndFilters($project, $filters)
                ;

                $currentPage = 1;
                $paginator = new Paginator($wpQuery);
                $paginator->getQuery()
                    ->setFirstResult($pageSize * ($currentPage - 1))
                    ->setMaxResults($pageSize)
                ;
                $responseArray[$workPackageStatus->getId()] = [
                    'totalItems' => count($paginator),
                    'items' => $paginator->getIterator()->getArrayCopy(),
                ];
            }
        }

        return $this->createApiResponse($responseArray);
    }

    /**
     * @Route("/{id}/subteams", name="app_api_project_create_subteam", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createSubteamAction(Request $request, Project $project)
    {
        $subteam = new Subteam();
        $form = $this->createForm(SubteamCreateType::class, $subteam, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $subteam->setProject($project);
            $this->persistAndFlush($subteam);

            return $this->createApiResponse($subteam, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * All project risks.
     *
     * @Route("/{id}/risks", name="app_api_project_risks", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function risksAction(Request $request, Project $project)
    {
        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getManager()
                ->getRepository(Risk::class)
                ->findFiltered($project, $request->query->all())
        );
    }

    /**
     * Create new project Risk.
     *
     * @Route("/{id}/risks", name="app_api_project_create_risk", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createRiskAction(Request $request, Project $project)
    {
        $risk = new Risk();
        $form = $this->createForm(RiskCreateType::class, $risk, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $risk->setProject($project);
            $risk->setCreatedBy($this->getUser());
            $this->persistAndFlush($risk);

            return $this->createApiResponse($risk, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * All project opportunities.
     *
     * @Route("/{id}/opportunities", name="app_api_project_opportunities", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function opportunitiesAction(Request $request, Project $project)
    {
        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getManager()
                ->getRepository(Opportunity::class)
                ->findFiltered($project, $request->query->all())
        );
    }

    /**
     * Create new project opportunity.
     *
     * @Route("/{id}/opportunities", name="app_api_project_create_opportunity", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createOpportunityAction(Request $request, Project $project)
    {
        $opportunity = new Opportunity();
        $form = $this->createForm(OpportunityCreateType::class, $opportunity, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $opportunity->setProject($project);
            $opportunity->setCreatedBy($this->getUser());
            $this->persistAndFlush($opportunity);

            return $this->createApiResponse($opportunity, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * All risk & opportunities statistics overivew.
     *
     * @Route("/{id}/risks-opportunities-stats", name="app_api_project_risks_opportunities_stats", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function risksOpportunitiesStatsAction(Project $project)
    {
        $riskData = $this->getDoctrine()->getRepository(Risk::class)->getStatsByProject($project);
        $opportunityData = $this->getDoctrine()->getRepository(Opportunity::class)->getStatsByProject($project);
        $measureRepo = $this->getDoctrine()->getRepository(Measure::class);

        return $this->createApiResponse([
            'risks' => [
                'risk_data' => $riskData,
                'measure_data' => $measureRepo->getStatsForRisk(),
             ],
            'opportunities' => [
                'opportunity_data' => $opportunityData,
                'measure_data' => $measureRepo->getStatsForOpportunity(),
            ],
        ]);
    }

    /**
     * Get data for Gantt charts.
     *
     * @Route("/{id}/gantt", name="app_api_project_gantt", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function ganttAction(Project $project)
    {
        $data = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(WorkPackage::class)
            ->findBy(
                [
                    'project' => $project,
                ],
                [
                    'puid' => 'ASC',
                ]
            );

        return $this->createApiResponse($data);
    }

    /**
     * @Route("/{id}/costs-resources", name="app_api_project_costs_resources", options={"expose"=true})
     * @Method({"GET"})
     */
    public function costsResourcesAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        if (isset($filters['type'])) {
            $em = $this->getDoctrine()->getManager();
            $costRepo = $em->getRepository(Cost::class);
            $wpRepo = $em->getRepository(WorkPackage::class);

            $baseCosts = $costRepo->getTotalBaseCostByPhase($project, $filters['type']);
            $actualForecastCosts = $wpRepo->getTotalExternalInternalCostsByPhase($project, $filters['type']);
            $dataByPhase = [];
            foreach (array_merge($baseCosts, $actualForecastCosts) as $cost) {
                foreach ($cost as $key => $value) {
                    if ($key !== 'phaseName') {
                        $dataByPhase[$cost['phaseName']][$key] = $value;
                    }
                }
            }

            $userDepartments = $em->getRepository(ProjectUser::class)->getUserAndDepartment($project);
            $dataByDepartment = [];
            foreach ($userDepartments as $userDepartment) {
                $dataByDepartment[$userDepartment['department']]['userIds'][] = $userDepartment['uid'];
            }
            foreach ($dataByDepartment as $key => $value) {
                $base = $costRepo->getTotalBaseCostByPhase($project, $filters['type'], $value['userIds']);
                $actualForecast = $wpRepo->getTotalExternalInternalCostsByPhase($project, $filters['type'], $value['userIds']);
                $dataByDepartment[$key]['base'] = !empty($base) ? $base[0]['base'] : 0;
                $dataByDepartment[$key]['actual'] = !empty($actualForecast) ? $actualForecast[0]['actual'] : 0;
                $dataByDepartment[$key]['forecast'] = !empty($actualForecast) ? $actualForecast[0]['forecast'] : 0;
            }

            return $this->createApiResponse([
                'byPhase' => $dataByPhase,
                'byDepartment' => $dataByDepartment
            ]);
        }

        return $this->createApiResponse(null, Response::HTTP_BAD_REQUEST);
    }
}
