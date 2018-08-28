import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard/Dashboard.vue';
import Projects from '../components/Projects/Projects.vue';
import ProjectCreateStep1 from '../components/Projects/ProjectCreateStep1.vue';
import ProjectCreateStep2 from '../components/Projects/ProjectCreateStep2.vue';
import ProjectCreateStep3 from '../components/Projects/ProjectCreateStep3.vue';
import ViewProject from '../components/Projects/ViewProject.vue';
import ProjectDashboard from '../components/Projects/ProjectDashboard.vue';
import ProjectContract from '../components/Projects/ProjectContract.vue';
import ProjectOrganization from '../components/Projects/ProjectOrganization.vue';
import TaskManagement from '../components/Projects/TaskManagement/TaskManagement.vue';
import List from '../components/Projects/TaskManagement/List.vue';
import ListGrid from '../components/Projects/TaskManagement/ListGrid.vue';
import View from '../components/Projects/TaskManagement/View.vue';
import Create from '../components/Projects/TaskManagement/Create.vue';
import AddLabel from '../components/Projects/TaskManagement/AddLabel.vue';
import EditLabels from '../components/Projects/TaskManagement/EditLabels.vue';
import ViewTask from '../components/Tasks/ViewTask.vue';
import Tasks from '../components/Tasks/Tasks.vue';
import ViewMember from '../components/Projects/Organization/ViewMember.vue';
import EditOrganization from '../components/Projects/Organization/EditOrganization.vue';
import ProjectPhasesMilestones from '../components/Projects/ProjectPhasesMilestones.vue';
import PhaseCreate from '../components/Projects/Phases/PhaseCreate.vue';
import PhaseView from '../components/Projects/Phases/PhaseView.vue';
import MilestoneCreate from '../components/Projects/Milestones/MilestoneCreate.vue';
import MilestoneView from '../components/Projects/Milestones/MilestoneView.vue';
import ProjectRisksOpportunities from '../components/Projects/ProjectRisksOpportunities.vue';
import RiskCreate from '../components/Projects/Risks/RiskCreate.vue';
import OpportunityCreate from '../components/Projects/Opportunities/OpportunityCreate.vue';
import RiskView from '../components/Projects/Risks/RiskView.vue';
import OpportunityView from '../components/Projects/Opportunities/OpportunityView.vue';
import ProjectMeetings from '../components/Projects/ProjectMeetings.vue';
import MeetingCreate from '../components/Projects/Meetings/MeetingCreate.vue';
import EditMeeting from '../components/Projects/Meetings/EditMeeting.vue';
import ViewMeeting from '../components/Projects/Meetings/ViewMeeting.vue';
import Gantt from '../components/Projects/Gantt.vue';
import ProjectTodos from '../components/Projects/ProjectTodos.vue';
import TodoCreate from '../components/Projects/Todos/TodoCreate.vue';
import ViewTodo from '../components/Projects/Todos/ViewTodo.vue';
import ExternalCosts from '../components/Projects/ExternalCosts.vue';
import InternalCosts from '../components/Projects/InternalCosts.vue';
import ProjectInfos from '../components/Projects/ProjectInfos.vue';
import InfoCreate from '../components/Projects/Infos/InfoCreate.vue';
import ViewInfo from '../components/Projects/Infos/ViewInfo.vue';
import ProjectDecisions from '../components/Projects/ProjectDecisions.vue';
import DecisionCreate from '../components/Projects/Decisions/DecisionCreate.vue';
import ViewDecision from '../components/Projects/Decisions/ViewDecision.vue';
import ProjectStatusReports from '../components/Projects/ProjectStatusReports.vue';
import StatusReportCreate from '../components/Projects/StatusReports/StatusReportCreate.vue';
import StatusReportView from '../components/Projects/StatusReports/StatusReportView.vue';
import StatusReportPrint from '../components/Projects/StatusReports/StatusReportPrint.vue';
import RASCIMatrix from '../components/Projects/RASCIMatrix.vue';
import CloseDownReport from '../components/Projects/ProjectCloseDownReport.vue';
import RemainingActionView from '../components/Projects/CloseDownReport/ViewRemainingAction.vue';
import RemainingActionEdit from '../components/Projects/CloseDownReport/EditRemainingAction.vue';
import WBS from '../components/Projects/WBS.vue';
import Guard from '../helpers/security/guard';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'main',
        redirect: {name: 'dashboard'},
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {requiresAuth: true, title: 'Dashboard'},
    },
    {
        path: '/projects',
        name: 'projects',
        component: Projects,
        meta: {title: 'Projects'},
    },
    {
        path: '/projects/create/step/1',
        name: 'projects-create-1',
        component: ProjectCreateStep1,
        meta: {title: 'Project Create Step 1'},
    },
    {
        path: '/projects/create/step/2',
        name: 'projects-create-2',
        component: ProjectCreateStep2,
        meta: {title: 'Project Create Step 2'},
    },
    {
        path: '/projects/create/step/3',
        name: 'projects-create-3',
        component: ProjectCreateStep3,
        meta: {title: 'Project Create Step 3'},
    },
    {
        path: '/projects/:id',
        component: ViewProject,
        meta: {title: 'View Project'},
        children: [
            {
                path: 'dashboard',
                component: ProjectDashboard,
                name: 'project-dashboard',
                meta: {title: 'Project Dashboard'},
            },
            {
                path: 'contract',
                component: ProjectContract,
                name: 'project-contract',
                meta: {
                    title: 'Project Contract',
                    guard: {
                        projectModule: ['contract'],
                    },
                },
            },
            {
                path: 'organization',
                component: ProjectOrganization,
                name: 'project-organization',
                meta: {
                    title: 'Project Organization',
                    guard: {
                        projectModule: ['organization'],
                    },
                },
            },
            {
                path: 'organization/view-member/:userId',
                component: ViewMember,
                name: 'project-organization-view-member',
                meta: {
                    title: 'Project View Member',
                    guard: {
                        projectModule: ['organization'],
                    },
                },
            },
            {
                path: 'organization/edit',
                component: EditOrganization,
                name: 'project-organization-edit',
                meta: {
                    title: 'Project Edit Organization',
                    guard: {
                        projectModule: ['organization'],
                    },
                },
            },
            {
                path: 'task-management',
                component: TaskManagement,
                meta: {
                    title: 'Project Task Management',
                    guard: {
                        projectModule: ['task_management'],
                    },
                },
                children: [
                    {
                        path: 'list',
                        component: List,
                        name: 'project-task-management-list',
                        meta: {title: 'Project Task Management List'},
                    },
                    {
                        path: 'list/grid',
                        component: ListGrid,
                        name: 'project-task-management-list-grid',
                        meta: {title: 'Project Task Management Grid'},
                    },
                    {
                        path: 'view/:taskId',
                        component: View,
                        name: 'project-task-management-view',
                        meta: {title: 'Project Task Management View Task'},
                    },
                    {
                        path: 'add',
                        component: Create,
                        name: 'project-task-management-create',
                        meta: {title: 'Project Task Management Create'},
                    },
                    {
                        path: 'edit/:taskId',
                        component: Create,
                        name: 'project-task-management-edit',
                        meta: {
                            title: 'Project Task Management Edit',
                        },
                    },
                    {
                        path: 'add-label',
                        component: AddLabel,
                        name: 'project-task-management-add-label',
                        meta: {
                            title: 'Project Task Management Add Label',
                        },
                    },
                    {
                        path: 'edit-label/:labelId',
                        component: AddLabel,
                        name: 'project-task-management-edit-label',
                        meta: {
                            title: 'Project Task Management Edit Label',
                        },
                    },
                    {
                        path: 'edit-labels',
                        component: EditLabels,
                        name: 'project-task-management-edit-labels',
                        meta: {
                            title: 'Project Task Management Edit Labels',
                        },
                    },
                ],
            },
            {
                path: 'phases-and-milestones',
                component: ProjectPhasesMilestones,
                name: 'project-phases-and-milestones',
                meta: {
                    title: 'Phases and Milestones',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/create-phase',
                component: PhaseCreate,
                name: 'project-phases-create-phase',
                meta: {
                    title: 'Create Phase',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/edit-phase/:phaseId',
                component: PhaseCreate,
                name: 'project-phases-edit-phase',
                meta: {
                    title: 'Edit Phase',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/phase/:phaseId',
                component: PhaseView,
                name: 'project-phases-view-phase',
                meta: {
                    title: 'View Phase',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/create-milestone',
                component: MilestoneCreate,
                name: 'project-milestones-create-milestone',
                meta: {
                    title: 'Create Milestone',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/edit-milestone/:milestoneId',
                component: MilestoneCreate,
                name: 'project-milestones-edit-milestone',
                meta: {
                    title: 'Edit Milestone',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'phases-and-milestones/milestone/:milestoneId',
                component: MilestoneView,
                name: 'project-phases-view-milestone',
                meta: {
                    title: 'View Milestone',
                    guard: {
                        projectModule: ['phases_and_milestones'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities',
                component: ProjectRisksOpportunities,
                name: 'project-risks-and-opportunities',
                meta: {
                    title: 'Risk & Opportunities',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/create-risk',
                component: RiskCreate,
                name: 'project-risks-create-risk',
                meta: {
                    title: 'Create Risk',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/risk/:riskId',
                component: RiskView,
                name: 'project-risks-view-risk',
                meta: {
                    title: 'View Risk',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/edit-risk/:riskId',
                component: RiskCreate,
                name: 'project-risks-edit-risk',
                meta: {
                    title: 'Edit Risk',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/create-opportunity',
                component: OpportunityCreate,
                name: 'project-opportunities-create-opportunity',
                meta: {
                    title: 'Create Opportunity',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/opportunity/:opportunityId',
                component: OpportunityView,
                name: 'project-opportunities-view-opportunity',
                meta: {
                    title: 'View Opportunity',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'risks-and-opportunities/edit-opportunity/:opportunityId',
                component: OpportunityCreate,
                name: 'project-opportunities-edit-opportunity',
                meta: {
                    title: 'Edit Opportunity',
                    guard: {
                        projectModule: ['risks_and_opportunities'],
                    },
                },
            },
            {
                path: 'meetings',
                component: ProjectMeetings,
                name: 'project-meetings',
                meta: {
                    title: 'Project Meetings',
                    guard: {
                        projectModule: ['meetings'],
                    },
                },
            },
            {
                path: 'meetings/create-meeting',
                component: MeetingCreate,
                name: 'project-meetings-create-meeting',
                meta: {
                    title: 'Create Meeting',
                    guard: {
                        projectModule: ['meetings'],
                    },
                },
            },
            {
                path: 'meetings/edit-meeting/:meetingId',
                component: EditMeeting,
                name: 'project-meetings-edit-meeting',
                meta: {
                    title: 'Edit Meeting',
                    guard: {
                        projectModule: ['meetings'],
                    },
                },
            },
            {
                path: 'meetings/view-meeting/:meetingId',
                component: ViewMeeting,
                name: 'project-meetings-view-meeting',
                meta: {
                    title: 'View Meeting',
                    guard: {
                        projectModule: ['meetings'],
                    },
                },
            },
            {
                path: 'gantt-chart',
                component: Gantt,
                name: 'project-gantt-chart',
                meta: {
                    title: 'Gantt Chart',
                    guard: {
                        projectModule: ['gantt_chart'],
                    },
                },
            },
            {
                path: 'todos',
                component: ProjectTodos,
                name: 'project-todos',
                meta: {
                    title: 'Todos',
                    guard: {
                        projectModule: ['todos'],
                    },
                },
            },
            {
                path: 'todos/create-todo',
                component: TodoCreate,
                name: 'project-todos-create-todo',
                meta: {
                    title: 'Create Todo',
                    guard: {
                        projectModule: ['todos'],
                    },
                },
            },
            {
                path: 'todos/edit-todo/:todoId',
                component: TodoCreate,
                name: 'project-todos-edit-todo',
                meta: {
                    title: 'Edit Todo',
                    guard: {
                        projectModule: ['todos'],
                    },
                },
            },
            {
                path: 'todos/view-todo/:todoId',
                component: ViewTodo,
                name: 'project-todos-view-todo',
                meta: {
                    title: 'View Todo',
                    guard: {
                        projectModule: ['todos'],
                    },
                },
            },
            {
                path: 'external-costs',
                component: ExternalCosts,
                name: 'project-external-costs',
                meta: {
                    title: 'External Costs',
                    guard: {
                        projectModule: ['external_costs'],
                    },
                },
            },
            {
                path: 'internal-costs',
                component: InternalCosts,
                name: 'project-internal-costs',
                meta: {
                    title: 'Internal Costs',
                    guard: {
                        projectModule: ['internal_costs'],
                    },
                },
            },
            {
                path: 'infos',
                component: ProjectInfos,
                name: 'project-infos',
                meta: {
                    title: 'Project Infos',
                    guard: {
                        projectModule: ['infos'],
                    },
                },
            },
            {
                path: 'infos/new',
                component: InfoCreate,
                name: 'project-infos-new',
                meta: {
                    title: 'Create Info',
                    guard: {
                        projectModule: ['infos'],
                    },
                },
            },
            {
                path: 'infos/edit/:infoId',
                component: InfoCreate,
                name: 'project-infos-edit',
                meta: {
                    title: 'Edit Info',
                    guard: {
                        projectModule: ['infos'],
                    },
                },
            },
            {
                path: 'infos/view/:infoId',
                component: ViewInfo,
                name: 'project-infos-view',
                meta: {
                    title: 'View Info',
                    guard: {
                        projectModule: ['infos'],
                    },
                },
            },
            {
                path: 'decisions',
                component: ProjectDecisions,
                name: 'project-decisions',
                meta: {
                    title: 'Project Decisions',
                    guard: {
                        projectModule: ['decisions'],
                    },
                },
            },
            {
                path: 'decisions/create-decision',
                component: DecisionCreate,
                name: 'project-decisions-create-decision',
                meta: {
                    title: 'Create Decision',
                    guard: {
                        projectModule: ['decisions'],
                    },
                },
            },
            {
                path: 'decisions/edit-decision/:decisionId',
                component: DecisionCreate,
                name: 'project-decisions-edit-decision',
                meta: {
                    title: 'Edit Decision',
                    guard: {
                        projectModule: ['decisions'],
                    },
                },
            },
            {
                path: 'decisions/view-decision/:decisionId',
                component: ViewDecision,
                name: 'project-decisions-view-decision',
                meta: {
                    title: 'View Decision',
                    guard: {
                        projectModule: ['decisions'],
                    },
                },
            },
            {
                path: 'status-reports',
                component: ProjectStatusReports,
                name: 'project-status-reports',
                meta: {
                    title: 'Project Status Reports',
                    guard: {
                        projectModule: ['status_report'],
                    },
                },
            },
            {
                path: 'status-reports/create-status-report',
                component: StatusReportCreate,
                name: 'project-status-reports-create-status-report',
                meta: {
                    title: 'Create Status Report',
                    guard: {
                        projectModule: ['status_report'],
                    },
                },
            },
            {
                path: 'status-reports/view-status-report/:reportId',
                component: StatusReportView,
                name: 'project-status-reports-view-status-report',
                meta: {
                    title: 'View Status Report',
                    guard: {projectModule: ['status_report']},
                },
            },
            {
                path: 'status-reports/print-status-report/:reportId',
                component: StatusReportPrint,
                name: 'project-status-reports-print-status-report',
                meta: {
                    title: 'Print Status Report',
                    guard: {projectModule: ['status_report']},
                },
            },
            {
                path: 'rasci-matrix',
                component: RASCIMatrix,
                name: 'project-rasci-matrix',
                meta: {
                    title: 'RASCI Matrix',
                    guard: {
                        projectModule: ['rasci_matrix'],
                    },
                },
            },
            {
                path: 'close-down-report',
                component: CloseDownReport,
                name: 'project-close-down-report',
                meta: {
                    title: 'Close Down Report',
                    guard: {
                        projectModule: ['close_down_project'],
                    },
                },
            },
            {
                path: 'close-down-report/view-remaining-action/:actionId',
                component: RemainingActionView,
                name: 'project-close-down-report-view-remaining-action',
                meta: {
                    title: 'Close Down Report - View Remaining Action',
                    guard: {
                        projectModule: ['close_down_project'],
                    },
                },
            },
            {
                path: 'close-down-report/edit-remaining-action/:actionId',
                component: RemainingActionEdit,
                name: 'project-close-down-report-edit-remaining-action',
                meta: {
                    title: 'Close Down Report - Edit Remaining Action',
                    guard: {
                        projectModule: ['close_down_project'],
                    },
                },
            },
            {
                path: 'wbs',
                component: WBS,
                name: 'project-wbs',
                meta: {
                    title: 'WBS',
                    guard: {
                        projectModule: ['wbs'],
                    },
                },
            },
        ],
    },
    {
        path: '/tasks',
        name: 'tasks',
        component: Tasks,
        meta: {title: 'Tasks'},
    },
    {
        path: '/tasks/:id',
        name: 'task',
        meta: {title: 'View Task'},
        component: ViewTask,
    },
];

const router = new VueRouter({
    routes,
});

router.beforeEach((to, from, next) => {
    // force scroll to top on each navigation
    setTimeout(() => {
        window.scrollTo(0, 0);
    }, 100);

    Guard.beforeEach(to, from, (nArg) => {
        if (to.meta.title && nArg !== false && !(nArg instanceof Error)) {
            document.title = to.meta.title;
        }
        next(nArg);
    });
});

export default router;
