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
import projectUnit from './modules/project-unit';
import projectMilestone from './modules/project-milestone';
import projectPhase from './modules/project-phase';
import projectResources from './modules/project-resources';
import workPackageStatus from './modules/work-package-status';
import projectUser from './modules/project-user';
import distributionList from './modules/distribution-list';
import projectRole from './modules/project-role';
import subteam from './modules/subteam';
import opportunity from './modules/opportunity';
import risk from './modules/risk';
import tasksStatus from './modules/tasks-status';
import tasksByStatus from './modules/tasks-by-status';
import opportunityStrategy from './modules/opportunity-strategy';
import opportunityStatus from './modules/opportunity-status';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    actions,
    getters,
    mutations,
    modules: {
        loader,
        user,
        distributionList,
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
        projectResources,
        workPackageStatus,
        projectRole,
        subteam,
        opportunity,
        risk,
        tasksStatus,
        tasksByStatus,
        opportunityStrategy,
        opportunityStatus,
    },
    strict: debug,
});
