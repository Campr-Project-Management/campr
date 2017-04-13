import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import * as mutations from './mutations';
import loader from './modules/loader';
import user from './modules/user';
import project from './modules/project';
import task from './modules/task';
import colorStatus from './modules/color_status';
import projectStatus from './modules/project-status';
import customer from './modules/customer';
import programme from './modules/programme';
import portfolio from './modules/portfolio';
import projectContract from './modules/project-contract';
import projectCategory from './modules/project-category';
import projectScope from './modules/project-scope';
import workPackage from './modules/work-package';
import projectDepartment from './modules/project-department';
import projectUser from './modules/project-user';
import projectUnit from './modules/project-unit';
import projectMilestone from './modules/project-milestone';
import projectPhase from './modules/project-phase';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    actions,
    getters,
    mutations,
    modules: {
        loader,
        user,
        project,
        task,
        colorStatus,
        projectStatus,
        customer,
        programme,
        portfolio,
        projectContract,
        projectCategory,
        projectScope,
        workPackage,
        projectDepartment,
        projectUser,
        projectUnit,
        projectMilestone,
        projectPhase,
    },
    strict: debug,
});
