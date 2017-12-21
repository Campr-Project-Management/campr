<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\ColorStatus;
use AppBundle\Entity\Contract;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Decision;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Info;
use AppBundle\Entity\Label;
use AppBundle\Entity\Measure;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Note;
use AppBundle\Entity\Opportunity;
use AppBundle\Entity\OpportunityStatus;
use AppBundle\Entity\OpportunityStrategy;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCloseDown;
use AppBundle\Entity\ProjectDeliverable;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectLimitation;
use AppBundle\Entity\ProjectObjective;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectStatus;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\Risk;
use AppBundle\Entity\RiskStatus;
use AppBundle\Entity\StatusReport;
use AppBundle\Entity\StatusReportConfig;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\Todo;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\Unit;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Form\Info\ApiCreateType as InfoType;
use AppBundle\Form\Label\BaseLabelType;
use AppBundle\Form\Project\ApiType;
use AppBundle\Form\Calendar\BaseCreateType as CalendarCreateType;
use AppBundle\Form\Contract\BaseCreateType as ContractCreateType;
use AppBundle\Form\DistributionList\BaseCreateType as DistributionCreateType;
use AppBundle\Form\Note\BaseCreateType as NoteCreateType;
use AppBundle\Form\ProjectTeam\BaseCreateType as ProjectTeamCreateType;
use AppBundle\Form\ProjectUser\BaseCreateType as ProjectUserCreateType;
use AppBundle\Form\Rasci\DataType as RasciDataType;
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
use AppBundle\Form\WorkPackage\ImportType as ImportWorkPackageType;
use AppBundle\Form\Risk\CreateType as RiskCreateType;
use AppBundle\Form\Opportunity\ApiType as OpportunityCreateType;
use AppBundle\Repository\MeetingRepository;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Security\ProjectVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use MainBundle\Controller\API\ApiController;
use AppBundle\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\Meeting\ApiCreateType as MeetingApiCreateType;
use AppBundle\Form\Decision\CreateType as DecisionType;
use AppBundle\Form\ProjectDepartment\CreateType as ProjectDepartmentType;
use AppBundle\Form\StatusReport\CreateType as StatusReportCreateType;
use AppBundle\Form\ProjectCloseDown\CreateType as ProjectCloseDownCreateType;
use AppBundle\Utils\ImportConstants;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Gaufrette\Exception\FileAlreadyExists;

/**
 * @Route("/api/projects")
 */
class ProjectController extends ApiController
{
    /**
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
        $filters = $request->query->all();
        $user = $this->getUser();
        $projectRepo = $this->getDoctrine()->getManager()->getRepository(Project::class);

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $result = $projects = $projectRepo->findByUserAndFilters($user, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $projectRepo->countTotalByUserAndFilters($user, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            usort($result, function ($a, $b) use ($user) {
                return !in_array($user->getId(), $a->getUserFavoritesIds());
            });
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $projectRepo->findByUserAndFilters($user, $filters)->getQuery()->getResult(),
        ]);
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
        $form = $this->createForm(ApiType::class, $project, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            if (!$project->getStatus()) {
                $projectStatus = $em->getReference(ProjectStatus::class, ProjectStatus::NOT_STARTED);
                $project->setStatus($projectStatus);
            }

            $this->persistAndFlush($project);

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

        $form = $this->createForm(ApiType::class, $project, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($project);

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
     * @Route("/{id}/distribution-lists", name="app_api_project_distribution_lists", options={"expose"=true})
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
            $distributionList = $form->getData();
            $distributionList->setCreatedBy($this->getUser());
            $distributionList->setProject($project);
            $this->persistAndFlush($distributionList);

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
            $this->persistAndFlush($label);

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
     * @Route("/{id}/meetings", name="app_api_project_meetings", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Meeting $meeting
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function meetingsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        /** @var MeetingRepository $repo */
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Meeting::class)
        ;

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $result = $repo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult();

            $responseArray['totalItems'] = $repo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        $items = $repo->findBy(['project' => $project]);

        return $this->createApiResponse([
            'items' => $items,
            'totalItems' => count($items),
        ]);
    }

    /**
     * Create a new Meeting.
     *
     * @Route("/{id}/meetings", name="app_api_project_meeting_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createMeetingAction(Request $request, Project $project)
    {
        $meeting = new Meeting();
        $form = $this->createForm(MeetingApiCreateType::class, $meeting, ['csrf_protection' => false]);
        $this->processForm($request, $form);

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
            foreach ($meeting->getMedias() as $media) {
                $media->setFileSystem($fileSystem);
            }

            $meeting->setCreatedBy($this->getUser());
            $meeting->setProject($project);
            $this->persistAndFlush($meeting);

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
        $projectUserRepo = $this->getDoctrine()->getRepository(ProjectUser::class);

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.users_per_page');
            $result = $projectUserRepo->getQueryByUserFullName($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $projectUserRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => isset($filters['search'])
                ? $projectUserRepo->getQueryByUserFullName($project, $filters)->getQuery()->getResult()
                : $project->getProjectUsers(),
        ]);
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            // @TODO: refactor to use a form and model
            $projectUser = new ProjectUser();
            $projectUser->setProject($project);
            $projectUser->setShowInOrg($form->get('showInOrg')->getData());
            $projectUser->setShowInRasci($form->get('showInRasci')->getData());
            $projectUser->setShowInResources($form->get('showInResources')->getData());
            $projectUser->setCompany($form->get('company')->getData());
            foreach ($form->get('roles')->getData() as $roleId) {
                $role = $em->getRepository(ProjectRole::class)->find($roleId);
                $projectUser->addProjectRole($role);
                if ($role->getName() === ProjectRole::ROLE_SPONSOR) {
                    $specialDistribution = $em->getRepository(DistributionList::class)->findOneBy([
                        'project' => $project,
                        'sequence' => -1,
                    ]);
                    if ($specialDistribution) {
                        $specialDistribution->addUser($user);
                        $em->persist($specialDistribution);
                    }
                }
            }

            $request->request->set(
                'roles',
                null
            );

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
                $this->persistAndFlush($user);
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
     * Update a Team Member.
     *
     * @Route("/{id}/team-members", name="app_api_project_team_member_update", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request     $request
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function updateTeamMemberAction(Request $request, ProjectUser $projectUser)
    {
        $user = $projectUser->getUser();
        $form = $this->createForm(UserApiCreateType::class, $user, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            $projectUser->setShowInOrg($form->get('showInOrg')->getData());
            $projectUser->setShowInRasci($form->get('showInRasci')->getData());
            $projectUser->setShowInResources($form->get('showInResources')->getData());
            $projectUser->setCompany($form->get('company')->getData());
            foreach ($projectUser->getProjectRoles() as $role) {
                $projectUser->removeProjectRole($role);
            }
            foreach ($form->get('roles')->getData() as $roleId) {
                $role = $em->getRepository(ProjectRole::class)->find($roleId);
                $projectUser->addProjectRole($role);
            }
            $departments = $form->get('departments')->getData();
            foreach ($projectUser->getProjectDepartments() as $projectDepartment) {
                $projectUser->removeProjectDepartment($projectDepartment);
            }
            foreach ($departments as $department) {
                $projectUser->addProjectDepartment($department);
            }

            $user->addProjectUser($projectUser);
            $this->persistAndFlush($user);

            return $this->createApiResponse($projectUser, Response::HTTP_ACCEPTED);
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
     * @Route("/{id}/todos", name="app_api_projects_todos", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function todosAction(Request $request, Project $project)
    {
        $filters = $request->query->all();

        $todoRepo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Todo::class)
        ;

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $result = $todoRepo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult();

            $responseArray['totalItems'] = $todoRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse($project->getTodos());
    }

    /**
     * Create a new Todo.
     *
     * @Route("/{id}/todos", name="app_api_projects_todo_create",  options={"expose"=true})
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
            $this->persistAndFlush($projectObjective);

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
     * Overall progress of the project/tasks/costs.
     *
     * @Route("/{id}/progress", name="app_api_project_progress", options={"expose"=true})
     * @Method({"GET"})
     */
    public function progressAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $class = '';
        $baseCost = $em->getRepository(Cost::class)->getTotalBaseCost($project);
        $actualForecastCosts = $em->getRepository(WorkPackage::class)->getTotalExternalInternalCosts($project, Cost::TYPE_EXTERNAL);
        $costClass = '';
        if ($project->getOverallStatus() === 0) {
            $class = 'danger';
        } elseif ($project->getOverallStatus() === 1) {
            $class = 'warning';
        }
        if ($actualForecastCosts['forecast'] > $baseCost) {
            $costClass = 'warning';
        } elseif ($actualForecastCosts['actual'] > $baseCost) {
            $costClass = 'danger';
        }

        return $this->createApiResponse([
            'project_progress' => [
                'value' => 0,
                'class' => $class,
            ],
            'task_progress' => [
                'value' => $em->getRepository(WorkPackage::class)->averageProgressByProjectAndFilters($project),
                'class' => $class,
            ],
            'cost_progress' => [
                'value' => $baseCost > 0 ? ($actualForecastCosts['actual'] * 100) / $baseCost : 0,
                'class' => $costClass,
            ],
        ]);
    }

    /**
     * @Route("/{id}/tasks-status", name="app_api_project_tasks_status", options={"expose"=true})
     * @Method({"GET"})
     */
    public function tasksStatusAction(Project $project)
    {
        $response = [];
        $statuses = $this->getDoctrine()->getRepository(WorkPackageStatus::class)->findAll();
        $colorStatuses = $this->getDoctrine()->getRepository(ColorStatus::class)->findAll();
        $wpRepo = $this->getDoctrine()->getRepository(WorkPackage::class);
        $response['message.total_tasks'] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project);
        foreach ($statuses as $status) {
            $response[$status->getName()] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project, $status);
        }

        $response['conditions']['total'] = 0;
        foreach ($colorStatuses as $status) {
            $response['conditions'][$status->getName()]['count'] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project, null, $status);
            $response['conditions'][$status->getName()]['color'] = $status->getColor();
            $response['conditions']['total'] += $response['conditions'][$status->getName()]['count'];
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
        $form = $this->createForm(ApiCreateType::class, $wp, [
            'entity_manager' => $this->getDoctrine()->getManager(),
        ]);
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

        $entitySaveErrors = [];
        if ($form->isValid()) {
            foreach ($wp->getMedias() as $media) {
                $media->setFileSystem($fileSystem);
            }

            try {
                $wp->setProject($project);
                $this->persistAndFlush($wp);
                $this->refreshEntity($wp);

                return $this->createApiResponse($wp, Response::HTTP_CREATED);
            } catch (FileAlreadyExists $exception) {
                $entitySaveErrors['medias'][] = $exception->getMessage();
            }
        }

        $errors = $this->getFormErrors($form);
        $errors = array_merge($errors, $entitySaveErrors);

        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * import task from xml file.
     *
     * @Route("/{id}/tasks/import", name="app_api_project_tasks_import", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     */
    public function importTasksAction(Request $request, Project $project)
    {
        $form = $this->createForm(ImportWorkPackageType::class, null, ['csrf_protection' => false, 'method' => $request->getMethod()]);
        $this->processForm(
            $request,
            $form,
            in_array($request->getMethod(), [Request::METHOD_PUT, Request::METHOD_POST])
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $fileContent = file_get_contents($file->getPathname());

            try {
                $xml = new \SimpleXMLElement($fileContent);
                foreach ($xml->children() as $tag => $element) {
                    if ($tag === ImportConstants::TASKS_TAG) {
                        $this->get('app.service.import')->importWorkPackages($project, (array) $element);
                    }
                }
            } catch (UniqueConstraintViolationException $e) {
                return $this->createApiResponse(
                   [
                       'messages' => [
                           'file' => [
                                $this
                                    ->get('translator')
                                    ->trans('exception.unique_contraint_exception'),
                           ],
                       ],
                   ],
                   Response::HTTP_BAD_REQUEST
               );
            } catch (\Exception $e) {
                return $this->createApiResponse(
                   [
                       'messages' => [
                           'file' => [$e->getMessage()],
                       ],
                   ],
                   Response::HTTP_BAD_REQUEST
               );
            }

            return $this->createApiResponse(null, Response::HTTP_OK);
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
     * @Route("/{id}/subteams", name="app_api_project_subteams", options={"expose"=true})
     * @Method({"GET"})
     */
    public function subteamsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $subteamRepo = $this->getDoctrine()->getRepository(Subteam::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('admin.per_page');
            $result = $subteamRepo->getQueryFiltered($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $subteamRepo->countTotalByFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $subteamRepo->findBy(['project' => $project]),
        ]);
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

            return $this->createApiResponse($risk, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
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

            return $this->createApiResponse($opportunity, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
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
        $riskRepo = $this->getDoctrine()->getRepository(Risk::class);
        $opportunityRepo = $this->getDoctrine()->getRepository(Opportunity::class);
        $measureRepo = $this->getDoctrine()->getRepository(Measure::class);

        return $this->createApiResponse([
            'risks' => [
                'risk_data' => $riskRepo->getStatsByProject($project),
                'measure_data' => $measureRepo->getStatsForRisk($project),
                'top_risk' => $riskRepo->findTopByProject($project),
             ],
            'opportunities' => [
                'opportunity_data' => $opportunityRepo->getStatsByProject($project),
                'measure_data' => $measureRepo->getStatsForOpportunity($project),
                'top_opportunity' => $opportunityRepo->findTopByProject($project),
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
     * @Route("/{id}/costs-graph-data", name="app_api_project_costs_graph_data", options={"expose"=true})
     * @Method({"GET"})
     */
    public function costsGraphDataAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $costRepo = $em->getRepository(Cost::class);
        $wpRepo = $em->getRepository(WorkPackage::class);

        $baseCosts = array_merge(
            $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_EXTERNAL),
            $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_INTERNAL)
        );
        $actualForecastCosts = array_merge(
            $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_EXTERNAL),
            $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_INTERNAL)
        );

        $dataByPhase = [];
        foreach (array_merge($baseCosts, $actualForecastCosts) as $cost) {
            foreach ($cost as $key => $value) {
                if ($key !== 'phaseName') {
                    if (isset($dataByPhase[$cost['phaseName']][$key])) {
                        $dataByPhase[$cost['phaseName']][$key] = $dataByPhase[$cost['phaseName']][$key] + $value;
                    } else {
                        $dataByPhase[$cost['phaseName']][$key] = $value;
                    }
                }
            }
        }
        $trafficLight = Project::STATUS_GREEN;
        foreach ($dataByPhase as $phase) {
            if (isset($phase['forecast']) && isset($phase['base']) && (float) $phase['forecast'] > (float) $phase['base']) {
                $trafficLight = Project::STATUS_YELLOW;
            }
            if (isset($phase['actual']) && isset($phase['forecast']) && (float) $phase['actual'] > (float) $phase['forecast']) {
                $trafficLight = Project::STATUS_RED;
                break;
            }
        }

        $userDepartments = $em->getRepository(ProjectUser::class)->getUserAndDepartment($project);
        $dataByDepartment = [];
        foreach ($userDepartments as $userDepartment) {
            $dataByDepartment[$userDepartment['department']]['userIds'][] = $userDepartment['uid'];
        }
        foreach ($dataByDepartment as $key => $value) {
            $baseExternalCostArr = $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_EXTERNAL, $value['userIds']);
            $baseInternalCostArr = $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_INTERNAL, $value['userIds']);
            $baseExternalCost = !empty($baseExternalCostArr) ? $baseExternalCostArr[0]['base'] : 0;
            $baseInternalCost = !empty($baseInternalCostArr) ? $baseInternalCostArr[0]['base'] : 0;

            $actualForecastExternalArr = $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_EXTERNAL, $value['userIds']);
            $externalActualCost = !empty($actualForecastExternalArr) ? $actualForecastExternalArr[0]['actual'] : 0;
            $externalForecastCost = !empty($actualForecastExternalArr) ? $actualForecastExternalArr[0]['forecast'] : 0;

            $actualForecastInternalArr = $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_INTERNAL, $value['userIds']);
            $internalActualCost = !empty($actualForecastInternalArr) ? $actualForecastInternalArr[0]['actual'] : 0;
            $internalForecastCost = !empty($actualForecastInternalArr) ? $actualForecastInternalArr[0]['forecast'] : 0;

            $dataByDepartment[$key]['base'] = $baseExternalCost + $baseInternalCost;
            $dataByDepartment[$key]['actual'] = $externalActualCost + $internalActualCost;
            $dataByDepartment[$key]['forecast'] = $externalForecastCost + $internalForecastCost;
        }

        return $this->createApiResponse([
            'byPhase' => $dataByPhase,
            'byPhaseTraffic' => $trafficLight,
            'byDepartment' => $dataByDepartment,
        ]);
    }

    /**
     * @Route("/{id}/resources-graph-data", name="app_api_project_resources_graph_data", options={"expose"=true})
     * @Method({"GET"})
     */
    public function resourcesGraphDataAction(Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $costRepo = $em->getRepository(Cost::class);
        $wpRepo = $em->getRepository(WorkPackage::class);

        $baseCosts = $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_INTERNAL);
        $actualForecastCosts = $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_INTERNAL);
        $dataByPhase = [];
        foreach (array_merge($baseCosts, $actualForecastCosts) as $cost) {
            foreach ($cost as $key => $value) {
                if ($key !== 'phaseName') {
                    $dataByPhase[$cost['phaseName']][$key] = $value;
                }
            }
        }
        $trafficLight = Project::STATUS_GREEN;
        foreach ($dataByPhase as $phase) {
            if (isset($phase['forecast']) && isset($phase['base']) && (float) $phase['forecast'] > (float) $phase['base']) {
                $trafficLight = Project::STATUS_YELLOW;
            }
            if (isset($phase['actual']) && isset($phase['forecast']) && (float) $phase['actual'] > (float) $phase['forecast']) {
                $trafficLight = Project::STATUS_RED;
                break;
            }
        }

        $userDepartments = $em->getRepository(ProjectUser::class)->getUserAndDepartment($project);
        $dataByDepartment = [];
        foreach ($userDepartments as $userDepartment) {
            $dataByDepartment[$userDepartment['department']]['userIds'][] = $userDepartment['uid'];
        }
        foreach ($dataByDepartment as $key => $value) {
            $base = $costRepo->getTotalBaseCostByPhase($project, Cost::TYPE_INTERNAL, $value['userIds']);
            $actualForecast = $wpRepo->getTotalExternalInternalCostsByPhase($project, Cost::TYPE_INTERNAL, $value['userIds']);
            $dataByDepartment[$key]['base'] = !empty($base) ? $base[0]['base'] : 0;
            $dataByDepartment[$key]['actual'] = !empty($actualForecast) ? $actualForecast[0]['actual'] : 0;
            $dataByDepartment[$key]['forecast'] = !empty($actualForecast) ? $actualForecast[0]['forecast'] : 0;
        }

        return $this->createApiResponse([
            'byPhase' => $dataByPhase,
            'byPhaseTraffic' => $trafficLight,
            'byDepartment' => $dataByDepartment,
        ]);
    }

    /**
     * @Route("/{id}/infos", name="app_api_project_infos", options={"expose"=true})
     * @Method({"GET"})
     */
    public function infosAction(Request $request, Project $project)
    {
        $queryBuilder = $this
            ->getDoctrine()
            ->getRepository(Info::class)
            ->getQueryBuilderByProjectAndFilters($project, $request->query)
        ;

        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per_page', 10);

        /** @var SlidingPagination $paginator */
        $paginator = $this->get('knp_paginator')->paginate($queryBuilder, $page, $perPage);

        $out = [
            'items' => $paginator->getItems(),
            'currentPage' => (int) $paginator->getPage(),
            'numberOfPages' => $paginator->getPageCount(),
            'numberOfItems' => $paginator->getTotalItemCount(),
            'itemsPerPage' => $paginator->getItemNumberPerPage(),
        ];

        return $this->createApiResponse($out);
    }

    /**
     * @Route("/{id}/infos", name="app_api_project_create_info", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createInfoAction(Request $request, Project $project)
    {
        $info = new Info();
        $info->setProject($project);
        $form = $this->createForm(InfoType::class, $info, ['method' => Request::METHOD_POST, 'csrf_protection' => false]);

        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($info);
            $em->flush();

            return $this->createApiResponse($info, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * All project Decisions.
     *
     * @Route("/{id}/decisions", name="app_api_projects_decisions", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function decisionsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $decisionsRepo = $em->getRepository(Decision::class);

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            if (isset($filters['statusReport'])) {
                $report = $em->getRepository(StatusReport::class)->findLastByProject($project);
                $filters['createdAt'] = $report ? $report->getCreatedAt() : null;
            }

            $result = $decisionsRepo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $decisionsRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse($project->getDecisions());
    }

    /**
     * Create new decision.
     *
     * @Route("/{id}/decisions", name="app_api_project_decisions_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function createDecisionAction(Request $request, Project $project)
    {
        $form = $this->createForm(DecisionType::class, new Decision(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $decision = $form->getData();
            $decision->setProject($project);
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
     * Get all opportunity statuses.
     *
     * @Route("/{id}/opportunity-statuses", name="app_api_project_opportunity_statuses", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function opportunityStatusesAction(Project $project)
    {
        $opportunityStatuses = $this
            ->getEntityManager()
            ->getRepository(OpportunityStatus::class)
            ->findAllByProjectNullable($project)
        ;

        return $this->createApiResponse($opportunityStatuses);
    }

    /**
     * Get all opportunity strategies.
     *
     * @Route("/{id}/opportunity-strategies", name="app_api_project_opportunity_strategies", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function opportunityStrategiesAction(Project $project)
    {
        $opportunityStrategies = $this
            ->getEntityManager()
            ->getRepository(OpportunityStrategy::class)
            ->findAllByProjectNullable($project)
        ;

        return $this->createApiResponse($opportunityStrategies);
    }

    /**
     * Get all risk statuses.
     *
     * @Route("/{id}/risk-statuses", name="app_api_project_risk_statuses", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function riskStatusesAction(Project $project)
    {
        $statuses = $this
            ->getEntityManager()
            ->getRepository(RiskStatus::class)
            ->findAll()
        ;

        return $this->createApiResponse($statuses);
    }

    /**
     * Get all opporrisktunity strategies.
     *
     * @Route("/{id}/risk-strategies", name="app_api_project_risk_strategies", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function riskStrategiesAction(Project $project)
    {
        return $this->createApiResponse($project->getRiskStrategies());
    }

    /**
     * Get all project departments.
     *
     * @Route("/{id}/project-departments", name="app_api_project_departments", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function departmentsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $departmentRepo = $this->getDoctrine()->getRepository(ProjectDepartment::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('admin.per_page');
            $result = $departmentRepo->getQueryFiltered($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $departmentRepo->countTotalByFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'items' => $departmentRepo->findAll(),
        ]);
    }

    /**
     * Create a new Project Department.
     *
     * @Route("/{id}/project-departments", name="app_api_project_departments_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createDepartmentAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectDepartmentType::class, new ProjectDepartment(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $projectDepartment = $form->getData();
            $projectDepartment->setProject($project);
            $this->persistAndFlush($projectDepartment);

            return $this->createApiResponse($projectDepartment, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get all project status reports.
     *
     * @Route("/{id}/status-reports", name="app_api_project_status_reports", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function statusReportsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $statusReportRepo = $this->getDoctrine()->getRepository(StatusReport::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('admin.per_page');
            $result = $statusReportRepo->getQueryBuilderByProjectAndFilters($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $statusReportRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        } elseif (isset($filters['trend'])) {
            return $this->createApiResponse([
                'items' => $statusReportRepo->findTrendReports($project),
            ]);
        }

        return $this->createApiResponse([
            'items' => $statusReportRepo->findAll(),
        ]);
    }

    /**
     * Checks if the user is able to create a new status report.
     *
     * @Route("/{id}/check-status-report-availability", name="app_api_project_status_reports_availability", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function statusReportsAvailabilityAction(Project $project)
    {
        $statusReportRepo = $this->getDoctrine()->getRepository(StatusReport::class);
        $statusReportConfigRepo = $this->getDoctrine()->getRepository(StatusReportConfig::class);
        /** @var StatusReportConfig $config */
        $config = $statusReportConfigRepo->findOneBy(['project' => $project, 'isDefault' => true]);

        if ($config) {
            $today = new \DateTime();
            $lastReport = $statusReportRepo->findLastByProject($project);
            $todayReports = $statusReportRepo->countTotalByProjectAndFilters($project, ['date' => $today->format('Y-m-d')]);
            $perDay = $config->getPerDay();
            $minutesInterval = $config->getMinutesInterval();
            if ($perDay && $todayReports === $perDay) {
                return $this->createApiResponse(['error' => 'message.per_day_reports_exceeded']);
            } elseif ($minutesInterval) {
                $lastReportCreatedAt = $lastReport->getCreatedAt();
                $now = new \DateTime();
                $datetime1 = strtotime($lastReportCreatedAt->format('d-m-Y H:i:s'));
                $datetime2 = strtotime($now->format('d-m-Y H:i:s'));
                $interval = abs($datetime2 - $datetime1);
                $minutes = intval($interval / 60);
                if ($minutes < $minutesInterval) {
                    return $this->createApiResponse(['error' => 'message.minutes_under_interval']);
                }
            }
        }

        return $this->createApiResponse(null);
    }

    /**
     * Create a new Status Report.
     *
     * @Route("/{id}/status-reports", name="app_api_project_status_reports_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createStatusReportAction(Request $request, Project $project)
    {
        $form = $this->createForm(StatusReportCreateType::class, new StatusReport(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $statusReport = $form->getData();
            $statusReport->setProject($project);
            $statusReport->setCreatedBy($this->getUser());
            $this->persistAndFlush($statusReport);

            return $this->createApiResponse($statusReport, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}/rasci", name="app_api_project_rasci_get", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getRasciAction(Project $project)
    {
        $rasciData = $this
            ->get('app.service.rasci_matrix')
            ->getDataForProject($project)
        ;

        return $this->createApiResponse($rasciData);
    }

    /**
     * @Route("/{id}/rasci/{workPackage}/user/{user}", name="app_api_project_rasci_put", options={"expose"=true})
     * @ParamConverter("id", class="AppBundle\Entity\Project")
     * @ParamConverter("workPackage", class="AppBundle\Entity\WorkPackage", options={"id"="workPackage"})
     * @ParamConverter("user", class="AppBundle\Entity\User", options={"id"="user"})
     * @Method({"PUT"})
     */
    public function putRasciAction(Request $request, Project $project, WorkPackage $workPackage, User $user)
    {
        if ($workPackage->getProject() !== $project) {
            throw $this->createAccessDeniedException(
                $this
                    ->get('translator')
                    ->trans('exception.workpackage_must_belong_to_project')
            );
        }

        if (!$project->getProjectUsers()->map(function (ProjectUser $projectUser) {
            return $projectUser->getUser();
        })->contains($user)) {
            throw $this->createAccessDeniedException(
                $this
                    ->get('translator')
                    ->trans('exception.user_must_be_part_of_the_project')
            );
        }

        $rasci = $this
            ->getDoctrine()
            ->getRepository(Rasci::class)
            ->findOrCreateOneByWorkPackageAndUser($workPackage, $user)
        ;
        $isNew = !$rasci->getId();
        $form = $this->createForm(RasciDataType::class, $rasci, ['method' => Request::METHOD_PUT, 'csrf_protection' => false]);

        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($rasci);
            $em->flush();

            return $this->createApiResponse($rasci, $isNew ? Response::HTTP_CREATED : Response::HTTP_ACCEPTED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Get project close down.
     *
     * @Route("/{id}/close-downs", name="app_api_project_close_downs", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function getProjectCloseDownAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectCloseDowns());
    }

    /**
     * Create a new Project Close Down.
     *
     * @Route("/{id}/close-downs", name="app_api_project_close_down_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createProjectCloseDownAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectCloseDownCreateType::class, new ProjectCloseDown(), ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $projectCloseDown = $form->getData();
            $projectCloseDown->setProject($project);
            $this->persistAndFlush($projectCloseDown);

            return $this->createApiResponse($projectCloseDown, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}/wbs", name="app_api_project_wbs", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getWBSAction(Project $project)
    {
        return $this->createApiResponse($this->get('app.service.wbs')->getData($project));
    }

    /**
     * Get all projectRoles for a specific Project.
     *
     * @Route("/{id}/project-roles", name="app_api_project_roles", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function projectRolesAction(Project $project)
    {
        return $this->createApiResponse($project->getProjectRoles());
    }
}
