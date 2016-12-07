import Vue from 'vue'
import VueRouter from 'vue-router'
import 'expose?$!expose?jQuery!jquery'
import 'normalise.scss'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min.js'
import App from './App'
import Dashboard from './components/Dashboard'
import Projects from './components/Projects'
import Tasks from './components/Tasks'

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

const router = new VueRouter({
  routes // short for routes: routes
})

new Vue({
  router,
  template: '<App/>',
  components: { App }
}).$mount('#app')
