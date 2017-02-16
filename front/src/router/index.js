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
import ViewTask from '../components/Tasks/ViewTask';
import Tasks from '../components/Tasks/Tasks';

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
