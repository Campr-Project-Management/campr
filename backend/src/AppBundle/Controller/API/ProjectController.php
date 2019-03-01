<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Contract;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Decision;
use AppBundle\Entity\DistributionList;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Label;
use AppBundle\Entity\Opportunity;
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
use AppBundle\Entity\Resource;
use AppBundle\Entity\Risk;
use AppBundle\Entity\RiskStatus;
use AppBundle\Entity\RiskStrategy;
use AppBundle\Entity\StatusReport;
use AppBundle\Entity\Subteam;
use AppBundle\Entity\Todo;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\Unit;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Event\ProjectEvent;
use AppBundle\Event\RasciEvent;
use AppBundle\Form\Label\BaseLabelType;
use AppBundle\Form\Project\ApiType;
use AppBundle\Form\Calendar\BaseCreateType as CalendarCreateType;
use AppBundle\Form\Decision\ApiCreateType as DecisionApiCreateType;
use AppBundle\Form\Contract\BaseCreateType as ContractCreateType;
use AppBundle\Form\DistributionList\BaseCreateType as DistributionCreateType;
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
use AppBundle\Form\WorkPackage\ImportType as ImportWorkPackageType;
use AppBundle\Form\Risk\CreateType as RiskCreateType;
use AppBundle\Form\Opportunity\ApiType as OpportunityCreateType;
use AppBundle\Repository\MeasureRepository;
use AppBundle\Repository\OpportunityRepository;
use AppBundle\Repository\RasciRepository;
use AppBundle\Repository\RiskRepository;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Security\ProjectVoter;
use Component\Rasci\RasciEvents;
use Component\Project\ProjectEvents;
use Doctrine\ORM\EntityManager;
use MainBundle\Controller\API\ApiController;
use MainBundle\Form\InviteUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\ProjectDepartment\CreateType as ProjectDepartmentType;
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
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
            $result = $projects = $projectRepo->findByUserAndFilters($user, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $projectRepo->countTotalByUserAndFilters($user, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            usort(
                $result,
                function ($a, $b) use ($user) {
                    return !in_array($user->getId(), $a->getUserFavoritesIds());
                }
            );
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => $projectRepo->findByUserAndFilters($user, $filters)->getQuery()->getResult(),
            ]
        );
    }

    /**
     * Create a new Project.
     *
     * @Route(name="app_api_project_create", options={"expose"=true}, methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $project = new Project();

        $this->denyAccessUnlessGranted(ProjectVoter::CREATE, $project);

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

        if (!$this->isGranted(ProjectVoter::EDIT, $project)) {
            throw $this->createAccessDeniedException();
        }

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
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function projectUsersAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $projectUserRepo = $this->get('app.repository.project_user');

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.users_per_page'
            );
            $result = $projectUserRepo->getQueryByUserFullName($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $projectUserRepo->countTotalByProjectAndFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => isset($filters['search'])
                    ? $projectUserRepo->getQueryByUserFullName($project, $filters)->getQuery()->getResult()
                    : $project->getProjectUsers(),
            ]
        );
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
            $pus = $project
                ->getProjectUsers()
                ->filter(
                    function (ProjectUser $pu) use ($form, $projectUser) {
                        return $projectUser->getProject() === $pu->getProject() &&
                            $projectUser->getUser() === $pu->getUser();
                    }
                );

            if ($pus->count()) {
                return $this->createApiResponse($pus->first(), JsonResponse::HTTP_CREATED);
            }

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
            $projectUser->setShowInRasci($form->get('showInRasci')->getData());
            $projectUser->setShowInResources($form->get('showInResources')->getData());
            $projectUser->setCompany($form->get('company')->getData());
            foreach ($form->get('roles')->getData() as $roleId) {
                $role = $em->getRepository(ProjectRole::class)->find($roleId);
                $projectUser->addProjectRole($role);
                if (ProjectRole::ROLE_SPONSOR === $role->getName()) {
                    $specialDistribution = $em->getRepository(DistributionList::class)->findOneBy(
                        [
                            'project' => $project,
                            'sequence' => -1,
                        ]
                    );
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
                ->createTeamMember($request);

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
            ->getRepository(Todo::class);

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
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
            ->findByProject($project);

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
            return $this->createApiResponse(
                [
                    'message' => $this
                        ->get('translator')
                        ->trans('not_found.general', [], 'messages'),
                ],
                Response::HTTP_NOT_FOUND
            );
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
        $form = $this->createForm(
            ProjectObjectiveCreateType::class,
            new ProjectObjective(),
            ['csrf_protection' => false]
        );
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
        $form = $this->createForm(
            ProjectLimitationCreateType::class,
            new ProjectLimitation(),
            ['csrf_protection' => false]
        );
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
        $form = $this->createForm(
            ProjectDeliverableCreateType::class,
            new ProjectDeliverable(),
            ['csrf_protection' => false]
        );
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
        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getManager()
                ->getRepository(Resource::class)
                ->findWithoutProjectUserOrWithShowInResourcesProjectUserByProject($project)
        );
    }

    /**
     * @Route("/{id}/tasks", name="app_api_project_tasks", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function tasksAction(Request $request, Project $project)
    {
        /** @var WorkPackageRepository $repo */
        $repo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(WorkPackage::class);

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
            $milestone = $repo->findOneBy(
                [
                    'id' => $filters['milestone'],
                    'type' => WorkPackage::TYPE_MILESTONE,
                ]
            );

            if (!$milestone) {
                throw $this->createNotFoundException();
            }

            return $this->createApiResponse(
                [
                    'items' => $repo->findTasksByMilestone(
                        $milestone,
                        $orderBy,
                        $limit
                    ),
                    'totalItems' => $repo->countTasksByMilestone($milestone),
                ]
            );
        }

        return $this->createApiResponse(
            [
                'items' => $repo->findTasksByProject(
                    $project,
                    $orderBy,
                    $limit
                ),
                'totalItems' => $repo->countTasksByProject($project),
            ]
        );
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
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function tasksStatusAction(Project $project)
    {
        $response = [];
        $statuses = $this->getDoctrine()->getRepository(WorkPackageStatus::class)->findAll();
        $wpRepo = $this->getDoctrine()->getRepository(WorkPackage::class);
        $response['message.total_tasks'] = $wpRepo->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK, $project);
        foreach ($statuses as $status) {
            $response[$status->getName()] = $wpRepo->countTotalByTypeProjectAndStatus(
                WorkPackage::TYPE_TASK,
                $project,
                $status
            );
        }

        return $this->createApiResponse($response);
    }

    /**
     * import task from xml file.
     *
     * @Route("/{id}/tasks/import", name="app_api_project_tasks_import", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function importTasksAction(Request $request, Project $project)
    {
        $form = $this->createForm(
            ImportWorkPackageType::class,
            null,
            ['csrf_protection' => false, 'method' => $request->getMethod()]
        );
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
                    if (ImportConstants::TASKS_TAG === $tag) {
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
            ->findByProject($project, true);

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
     * @Route("/{id}/subteams", name="app_api_project_subteams", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function subteamsAction(Request $request, Project $project)
    {
        $filters = $request->query->all();
        $subteamRepo = $this->getDoctrine()->getRepository(Subteam::class);
        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'admin.per_page'
            );
            $result = $subteamRepo
                ->getQueryFiltered($project, $filters)
                ->getQuery()
                ->getResult()
            ;
            $responseArray['totalItems'] = $subteamRepo->countTotalByFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => $subteamRepo->findBy(['project' => $project]),
            ]
        );
    }

    /**
     * @Route("/{id}/subteams", name="app_api_project_create_subteam", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createSubteamAction(Request $request, Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

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
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function risksAction(Project $project)
    {
        return $this->createApiResponse(
            $this->get('app.repository.risk')->findBy(['project' => $project])
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
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function opportunitiesAction(Project $project)
    {
        return $this->createApiResponse(
            $this->get('app.repository.opportunity')->findBy(['project' => $project])
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
        /** @var RiskRepository $riskRepo */
        $riskRepo = $this->get('app.repository.risk');

        /** @var OpportunityRepository $opportunityRepo */
        $opportunityRepo = $this->get('app.repository.opportunity');

        /** @var MeasureRepository $measureRepo */
        $measureRepo = $this->get('app.repository.measure');

        return $this->createApiResponse(
            [
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
            ]
        );
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
        $workPackages = $this
            ->get('app.repository.work_package')
            ->getGantt($project);

        return $this->createApiResponse($workPackages);
    }

    /**
     * @Route("/{id}/external-costs-graph-data", name="app_api_project_external_costs_graph_data", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function externalCostsGraphDataAction(Project $project)
    {
        $byPhase = $this
            ->get('app.graph.generator.project_cost_by_phase')
            ->generate($project, Cost::TYPE_EXTERNAL);

        $byDepartment = $this
            ->get('app.graph.generator.project_cost_by_department')
            ->generate($project, Cost::TYPE_EXTERNAL);

        return $this->createApiResponse(
            [
                'byPhase' => $byPhase,
                'byDepartment' => $byDepartment,
            ]
        );
    }

    /**
     * @Route("/{id}/internal-costs-graph-data", name="app_api_project_internal_costs_graph_data", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function internalCostsGraphDataAction(Project $project)
    {
        $byPhase = $this
            ->get('app.graph.generator.project_cost_by_phase')
            ->generate($project, Cost::TYPE_INTERNAL);

        $byDepartment = $this
            ->get('app.graph.generator.project_cost_by_department')
            ->generate($project, Cost::TYPE_INTERNAL);

        return $this->createApiResponse(
            [
                'byPhase' => $byPhase,
                'byDepartment' => $byDepartment,
            ]
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
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
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
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function createDecisionAction(Request $request, Project $project)
    {
        $decision = new Decision();
        $form = $this->createForm(
            DecisionApiCreateType::class,
            $decision,
            [
                'entity_manager' => $this->getDoctrine()->getManager(),
            ]
        );
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $fileSystem = $project
            ->getFileSystems()
            ->filter(
                function (FileSystem $fs) {
                    return FileSystem::LOCAL_ADAPTER === $fs->getDriver();
                }
            )
            ->first();

        if (!$fileSystem) {
            $fileSystem = $em
                ->getRepository(FileSystem::class)
                ->findOneBy(
                    [
                        'driver' => FileSystem::LOCAL_ADAPTER,
                    ]
                );
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
            foreach ($decision->getMedias() as $media) {
                $media->setFileSystem($fileSystem);
            }

            try {
                $decision->setProject($project);
                $this->persistAndFlush($decision);

                return $this->createApiResponse($decision, Response::HTTP_CREATED);
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
            ->findAllByProjectNullable($project);

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
            ->findAll();

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
        $riskStrategies = $this
            ->getEntityManager()
            ->getRepository(RiskStrategy::class)
            ->findAll();

        return $this->createApiResponse($riskStrategies);
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
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'admin.per_page'
            );
            $result = $departmentRepo->getQueryFiltered($project, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $departmentRepo->countTotalByFilters($project, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'items' => $departmentRepo->findAll(),
            ]
        );
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
     * @Route("/{id}/rasci", name="app_api_project_rasci_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function getRasciAction(Project $project)
    {
        $rasciData = $this
            ->get('app.service.rasci_matrix')
            ->getDataForProject($project);

        return $this->createApiResponse($rasciData);
    }

    /**
     * @Route("/{id}/rasci/{workPackage}/user/{user}", name="app_api_project_rasci_put", options={"expose"=true})
     * @ParamConverter("id", class="AppBundle\Entity\Project")
     * @ParamConverter("workPackage", class="AppBundle\Entity\WorkPackage", options={"id"="workPackage"})
     * @ParamConverter("user", class="AppBundle\Entity\User", options={"id"="user"})
     * @Method({"PUT"})
     *
     * @param Request     $request
     * @param Project     $project
     * @param WorkPackage $workPackage
     * @param User        $user
     *
     * @return JsonResponse
     */
    public function putRasciAction(Request $request, Project $project, WorkPackage $workPackage, User $user)
    {
        if ($workPackage->getProject() !== $project) {
            $this->createdTranslatedAccessDeniedException('exception.workpackage_must_belong_to_project');
        }

        $projectUser = $user->getProjectUser($project);
        if (!$projectUser) {
            $this->createdTranslatedAccessDeniedException('exception.user_must_be_part_of_the_project');
        }

        /** @var RasciRepository $rasciRepo */
        $rasciRepo = $this->get('app.repository.rasci');
        $rasci = $rasciRepo->findOneBy(['workPackage' => $workPackage, 'user' => $user]);
        $isNew = false;
        if (!$rasci) {
            $isNew = true;
            $rasci = new Rasci();
            $rasci->setWorkPackage($workPackage);
            $rasci->setUser($user);
        }

        $form = $this->createForm(
            RasciDataType::class,
            $rasci,
            ['method' => Request::METHOD_PUT, 'csrf_protection' => false]
        );

        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $preEventName = RasciEvents::PRE_UPDATE;
            $postEventName = RasciEvents::POST_UPDATE;
            $event = new RasciEvent($rasci);
            if ($isNew) {
                $preEventName = RasciEvents::PRE_CREATE;
                $postEventName = RasciEvents::POST_CREATE;
            }

            $this->dispatchEvent($preEventName, $event);
            $rasciRepo->add($rasci);
            $this->dispatchEvent($postEventName, $event);

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
     * @Route("/{id}/rasci/{workPackage}/user/{user}", name="app_api_project_rasci_delete", options={"expose"=true})
     * @ParamConverter("id", class="AppBundle\Entity\Project")
     * @ParamConverter("workPackage", class="AppBundle\Entity\WorkPackage", options={"id"="workPackage"})
     * @ParamConverter("user", class="AppBundle\Entity\User", options={"id"="user"})
     * @Method({"DELETE"})
     *
     * @param Request     $request
     * @param Project     $project
     * @param WorkPackage $workPackage
     * @param User        $user
     *
     * @return JsonResponse
     */
    public function deleteRasciAction(Request $request, Project $project, WorkPackage $workPackage, User $user)
    {
        if ($workPackage->getProject() !== $project) {
            $this->createdTranslatedAccessDeniedException('exception.workpackage_must_belong_to_project');
        }

        $projectUser = $user->getProjectUser($project);
        if (!$projectUser) {
            $this->createdTranslatedAccessDeniedException('exception.user_must_be_part_of_the_project');
        }

        /** @var RasciRepository $rasciRepo */
        $rasciRepo = $this->get('app.repository.rasci');
        $rasci = $rasciRepo->findOneBy(['workPackage' => $workPackage, 'user' => $user]);
        if ($rasci) {
            $em = $this->getDoctrine()->getManager();
            $postEventName = RasciEvents::POST_REMOVE;
            $event = new RasciEvent($rasci);
            $rasciRepo->remove($rasci);
            $this->dispatchEvent($postEventName, $event);
            $em->flush();
        }

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
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
        $form = $this->createForm(
            ProjectCloseDownCreateType::class,
            new ProjectCloseDown(),
            ['csrf_protection' => false]
        );
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
     *
     * @param Project $project
     *
     * @return JsonResponse
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
        $roles = $this
            ->getDoctrine()
            ->getRepository(ProjectRole::class)
            ->findBy(['project' => null]);

        return $this->createApiResponse($roles);
    }

    /**
     * @Route("/{id}/invite", name="app_api_project_invite", options={"expose"=true}, methods={"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function inviteAction(Request $request, Project $project)
    {
        $form = $this->createForm(
            InviteUserType::class,
            null,
            [
                'user' => $this->getUser(),
                'csrf_protection' => false,
            ]
        );
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            return $this->createApiResponse(
                [
                    'errors' => $this->getFormErrors($form),
                ]
            );
        }
        $email = $form->get('email')->getData();

        $this
            ->get('app.team_invite.inviter')
            ->invite($email, $this->getParameter('kernel.team_slug'), $project);

        return $this->createApiResponse(
            [
                'messages' => [
                    $this->get('translator')->trans('message.user_invited_to_project', [], 'messages'),
                ],
            ]
        );
    }

    /**
     * @Route("/{id}/organization-tree", name="app_api_project_organization_tree", options={"expose"=true})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function organizationTreeAction(Project $project)
    {
        $tree = $this->get('app.service.project_organization_tree')->buildTree($project);

        return $this->createApiResponse($tree);
    }

    /**
     * Get all sponsor users.
     *
     * @Route("/{id}/sponsor-users", name="app_api_project_sponsor_users", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function projectSponsorUsersAction(Project $project)
    {
        return $this->createApiResponse(
            [
                'items' => $project->getProjectSponsors(),
            ]
        );
    }

    /**
     * Clone a specific Project.
     *
     * @Route("/{id}", name="app_api_project_clone", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function cloneAction(Request $request, Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        $name = $request->request->get('name');

        if (null === $name) {
            $errors = [
                'messages' => [
                    'number' => $this->get('translator')->trans('not_blank.number', [], 'validators'),
                ],
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->get('event_dispatcher')->dispatch(ProjectEvents::ON_CLONE, new ProjectEvent($project, $this->getUser(), $name));

        return $this->createApiResponse($project, Response::HTTP_CREATED);
    }
}
