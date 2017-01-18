import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard/Dashboard';
import Projects from '../components/Projects/Projects';
import ViewProject from '../components/Projects/ViewProject.vue';
import ProjectDashboard from '../components/Projects/ProjectDashboard.vue';
import ProjectContract from '../components/Projects/ProjectContract.vue';
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
