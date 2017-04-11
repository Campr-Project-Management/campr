import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard/Dashboard';
import Projects from '../components/Projects/Projects';
import ProjectCreateStep1 from '../components/Projects/ProjectCreateStep1';
import ProjectCreateStep2 from '../components/Projects/ProjectCreateStep2';
import ProjectCreateStep3 from '../components/Projects/ProjectCreateStep3';
import ViewProject from '../components/Projects/ViewProject.vue';
import ProjectDashboard from '../components/Projects/ProjectDashboard.vue';
import ProjectContract from '../components/Projects/ProjectContract.vue';
import ProjectOrganization from '../components/Projects/ProjectOrganization.vue';
import TaskManagement from '../components/Projects/TaskManagement/TaskManagement.vue';
import List from '../components/Projects/TaskManagement/List.vue';
import View from '../components/Projects/TaskManagement/View.vue';
import Create from '../components/Projects/TaskManagement/Create.vue';
import AddLabel from '../components/Projects/TaskManagement/AddLabel.vue';
import EditLabels from '../components/Projects/TaskManagement/EditLabels.vue';
import ViewTask from '../components/Tasks/ViewTask';
import Tasks from '../components/Tasks/Tasks';
import MemberCreate from '../components/Projects/Organization/MemberCreate.vue';

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
        meta: {requiresAuth: true},
    },
    {
        path: '/projects',
        name: 'projects',
        component: Projects,
    },
    {
        path: '/projects/create/step/1',
        name: 'projects-create-1',
        component: ProjectCreateStep1,
    },
    {
        path: '/projects/create/step/2',
        name: 'projects-create-2',
        component: ProjectCreateStep2,
    },
    {
        path: '/projects/create/step/3',
        name: 'projects-create-3',
        component: ProjectCreateStep3,
    },
    {
        path: '/projects/:id',
        component: ViewProject,
        children: [
            {
                path: 'dashboard',
                component: ProjectDashboard,
                name: 'project-dashboard',
            },
            {
                path: 'contract',
                component: ProjectContract,
                name: 'project-contract',
            },
            {
                path: 'organization',
                component: ProjectOrganization,
                name: 'project-organization',
            },
            {
                path: 'organization/member-create',
                component: MemberCreate,
                name: 'project-organization-create-member',
            },
            {
                path: 'task-management',
                component: TaskManagement,
                children: [
                    {
                        path: 'list',
                        component: List,
                        name: 'project-task-management-list',
                    },
                    {
                        path: 'view/:id',
                        component: View,
                        name: 'project-task-management-view',
                    },
                    {
                        path: 'add',
                        component: Create,
                        name: 'project-task-management-create',
                    },
                    {
                        path: 'add-label',
                        component: AddLabel,
                        name: 'project-task-management-add-label',
                    },
                    {
                        path: 'edit-labels',
                        component: EditLabels,
                        name: 'project-task-management-edit-labels',
                    },
                ],
            },
        ],
    },
    {
        path: '/tasks',
        name: 'tasks',
        component: Tasks,
    },
    {
        path: '/tasks/:id',
        name: 'task',
        component: ViewTask,
    },
];

export default new VueRouter({
    routes,
});
