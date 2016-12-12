import Vue from 'vue'
import VueRouter from 'vue-router'
import Dashboard from '../components/Dashboard/Dashboard'
import Projects from '../components/Projects/Projects'
import Tasks from '../components/Tasks/Tasks'

Vue.use(VueRouter)

const routes = [
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard
  },
  {
    path: '/projects',
    name: 'projects',
    component: Projects
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: Tasks
  }
]

export default new VueRouter({
  routes // short for routes: routes
})
