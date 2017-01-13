import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard/Dashboard';
import Projects from '../components/Projects/Projects';
import ViewProject from '../components/Projects/ViewProject';
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
        name: 'project',
        component: ViewProject,
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
