import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard/Dashboard';
import Projects from '../components/Projects/Projects';
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
    },
    {
        path: '/projects',
        name: 'projects',
        component: Projects,
        children: [
            {
                name: 'project',
                path: ':id',
            },
        ],
    },
    {
        path: '/tasks',
        name: 'tasks',
        component: Tasks,
        children: [
            {
                name: 'task',
                path: ':id',
            },
        ],
    },
];

export default new VueRouter({
    routes,
});
