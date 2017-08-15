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
import riskStatus from './modules/risk-status';
import riskStrategy from './modules/risk-strategy';
import measure from './modules/measure';
import gantt from './modules/gantt';
import meeting from './modules/meeting';
import meetingCategory from './modules/meeting-category';
import todoStatus from './modules/todo-status';
import noteStatus from './modules/note-status';
import meetingObjective from './modules/meeting-objective';
import meetingAgenda from './modules/meeting-agenda';
import decision from './modules/decision';
import decisionCategory from './modules/decision-category';
import todo from './modules/todo';
import todoCategory from './modules/todo-category';
import note from './modules/note';
import meetingParticipant from './modules/meeting-participant';
import validation from './modules/validation';
import info from './modules/info';
import infoCategory from './modules/info-category';
import infoStatus from './modules/info-status';
import dashboard from './modules/dashboard';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    actions,
    getters,
    mutations,
    modules: {
        loader,
        dashboard,
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
        riskStatus,
        riskStrategy,
        measure,
        gantt,
        meeting,
        meetingCategory,
        todoStatus,
        noteStatus,
        meetingObjective,
        meetingAgenda,
        decision,
        decisionCategory,
        todo,
        todoCategory,
        note,
        meetingParticipant,
        validation,
        info,
        infoCategory,
        infoStatus,
    },
    strict: debug,
});
