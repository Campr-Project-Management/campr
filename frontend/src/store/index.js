import Vue from 'vue';
import Vuex from 'vuex';
import actions from './actions';
import getters from './getters';
import mutations from './mutations';
import closeDownAction from './modules/close-down-action';
import colorStatus from './modules/color_status';
import projectColorStatus from './modules/project_color_status';
import cost from './modules/cost';
import customer from './modules/customer';
import dashboard from './modules/dashboard';
import decisionCategory from './modules/decision-category';
import decision from './modules/decision';
import distributionList from './modules/distribution-list';
import evaluationObjective from './modules/evaluation-objective';
import gantt from './modules/gantt';
import infoCategory from './modules/info-category';
import info from './modules/info';
import lesson from './modules/lesson';
import loader from './modules/loader';
import measure from './modules/measure';
import meetingAgenda from './modules/meeting-agenda';
import meetingCategory from './modules/meeting-category';
import meeting from './modules/meeting';
import meetingObjective from './modules/meeting-objective';
import meetingParticipant from './modules/meeting-participant';
import module from './modules/module';
import note from './modules/note';
import noteStatus from './modules/note-status';
import opportunity from './modules/opportunity';
import opportunityStatus from './modules/opportunity-status';
import opportunityStrategy from './modules/opportunity-strategy';
import portfolio from './modules/portfolio';
import programme from './modules/programme';
import projectCategory from './modules/project-category';
import projectCloseDown from './modules/project-close-down';
import projectContract from './modules/project-contract';
import projectDepartment from './modules/project-department';
import project from './modules/project';
import projectMilestone from './modules/project-milestone';
import projectOrganization from './modules/project-organization';
import projectPhase from './modules/project-phase';
import projectResources from './modules/project-resources';
import projectRole from './modules/project-role';
import projectScope from './modules/project-scope';
import projectStatus from './modules/project-status';
import projectUnit from './modules/project-unit';
import projectUser from './modules/project-user';
import rasci from './modules/rasci';
import risk from './modules/risk';
import riskStatus from './modules/risk-status';
import riskStrategy from './modules/risk-strategy';
import statusReport from './modules/status-report';
import subteam from './modules/subteam';
import task from './modules/task';
import tasksByStatus from './modules/tasks-by-status';
import tasksStatus from './modules/tasks-status';
import todoCategory from './modules/todo-category';
import todo from './modules/todo';
import todoStatus from './modules/todo-status';
import user from './modules/user';
import validation from './modules/validation';
import wbs from './modules/wbs';
import workPackage from './modules/work-package';
import workPackageStatus from './modules/work-package-status';
import workspace from './modules/workspace';
import currency from './modules/currency';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    actions,
    getters,
    mutations,
    modules: {
        closeDownAction,
        colorStatus,
        projectColorStatus,
        cost,
        customer,
        dashboard,
        decision,
        decisionCategory,
        distributionList,
        evaluationObjective,
        gantt,
        info,
        infoCategory,
        lesson,
        loader,
        measure,
        meeting,
        meetingAgenda,
        meetingCategory,
        meetingObjective,
        meetingParticipant,
        module,
        note,
        noteStatus,
        opportunity,
        opportunityStatus,
        opportunityStrategy,
        portfolio,
        programme,
        project,
        projectCategory,
        projectCloseDown,
        projectContract,
        projectDepartment,
        projectMilestone,
        projectOrganization,
        projectPhase,
        projectResources,
        projectRole,
        projectScope,
        projectStatus,
        projectUnit,
        projectUser,
        rasci,
        risk,
        riskStatus,
        riskStrategy,
        statusReport,
        subteam,
        task,
        tasksByStatus,
        tasksStatus,
        todo,
        todoCategory,
        todoStatus,
        user,
        validation,
        wbs,
        workPackage,
        workPackageStatus,
        workspace,
        currency,
    },
    strict: debug,
});
