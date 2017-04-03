<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-3">
            <h1>{{ message.project_create_wizard }}</h1>
            <h2>{{ message.project_create_step3 }}</h2>
            <div class="hr"></div>
            <h3>{{ message.project_size }}: <span>{{ message.medium }}</span></h3>

            <p>{{ message.recommended_modules }}</p>

            <project-module v-bind:title="message.project_contract" id="project-contract" v-model="projectContract" v-bind:inactive="!projectContract" />
            <project-module v-bind:title="message.project_organization" id="project-organization" v-model="projectOrganization" v-bind:inactive="!projectOrganization" />
            <project-module v-bind:title="message.plan" id="plan" v-model="plan" v-bind:inactive="!plan" />
            <project-module v-bind:title="message.task_management" id="task-management" v-model="taskManagement" v-bind:inactive="!taskManagement" />
            <project-module v-bind:title="message.phases_milestones" id="phases-milestones" v-model="phasesMilestones" v-bind:inactive="!phasesMilestones" />
            <project-module v-bind:title="message.costs" id="costs" v-model="costs" v-bind:inactive="!costs" />
            <project-module v-bind:title="message.resources" id="resources" v-model="resources" v-bind:inactive="!resources" />
            <project-module v-bind:title="message.risks_oportunities" id="risks-opportunities" v-model="risksOportunities" v-bind:inactive="!risksOportunities" />
            <project-module v-bind:title="message.communication" id="communication" v-model="communication" v-bind:inactive="!communication" />
            <project-module v-bind:title="message.control_measures" id="control-measures" v-model="controlMeasures" v-bind:inactive="!controlMeasures" />
            <project-module v-bind:title="message.status_report" id="status-report" v-model="statusReport" v-bind:inactive="!statusReport" />
            <project-module v-bind:title="message.meetings" id="meetings" v-model="meetings" v-bind:inactive="!meetings" />
            <project-module v-bind:title="message.todos" id="todos" v-model="todos" v-bind:inactive="!todos" />
            <project-module v-bind:title="message.notes" id="notes" v-model="notes" v-bind:inactive="!notes" />
            <project-module v-bind:title="message.close_down_project" id="close-down-project" v-model="closeDownProject" v-bind:inactive="!closeDownProject" />

            <p>{{ message.not_recommended_modules }}</p>

            <project-module v-bind:title="message.raci_matrix" id="raci-matrix" v-model="raciMatrix" v-bind:inactive="!raciMatrix" />
            <project-module v-bind:title="message.task_chart" id="task-chart" v-model="taskChart" v-bind:inactive="!taskChart" />
            <project-module v-bind:title="message.gantt_chart" id="gantt-chart" v-model="ganttChart" v-bind:inactive="!ganttChart" />
            <project-module v-bind:title="message.context" id="context" v-model="context" v-bind:inactive="!context" />
            <project-module v-bind:title="message.decisions" id="decisions" v-model="decisions" v-bind:inactive="!decisions" />

            <div class="flex flex-space-between actions">
                <a href="#" v-on:click="previousStep" class="btn-rounded btn-right" v-bind:title="button.previous_step">
                    < {{ button.previous_step }}
                </a>
                <a v-if="!projectLoading" href="#" v-on:click="submitProject" class="btn-rounded btn-right second-bg" v-bind:title="button.start_project">
                    < {{ button.start_project }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import ProjectModule from './ProjectModule';
import {mapActions, mapGetters} from 'vuex';
import {
    processProjectModules,
    processProjectConfiguration,
    convertImageToBlog,
    FIRST_STEP_LOCALSTORAGE_KEY,
    SECOND_STEP_LOCALSTORAGE_KEY,
    THIRD_STEP_LOCALSTORAGE_KEY,
} from '../../helpers/project';

export default {
    components: {
        ProjectModule,
    },
    computed: mapGetters({
        project: 'project',
        projectLoading: 'projectLoading',
    }),
    watch: {
        project(value) {
            localStorage.removeItem(FIRST_STEP_LOCALSTORAGE_KEY);
            localStorage.removeItem(SECOND_STEP_LOCALSTORAGE_KEY);
            localStorage.removeItem(THIRD_STEP_LOCALSTORAGE_KEY);
            this.$router.push({name: 'project-dashboard', params: {'id': value.id}});
        },
    },
    methods: {
        ...mapActions(['createProject']),
        submitProject: function(e) {
            e.preventDefault();
            this.saveStepState();

            this.startProject();
        },
        startProject: function() {
            const firstStepData = JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY));
            const secondStepData = JSON.parse(localStorage.getItem(SECOND_STEP_LOCALSTORAGE_KEY));
            const thirdStepData = JSON.parse(localStorage.getItem(THIRD_STEP_LOCALSTORAGE_KEY));

            let projectModules = processProjectModules(thirdStepData);
            let formData = new FormData();
            let configuration = processProjectConfiguration(secondStepData);

            formData.append('name', firstStepData.projectName);
            formData.append('number', firstStepData.projectNumber);
            if (firstStepData.projectLogo) {
                formData.append('logoFile', convertImageToBlog(firstStepData.projectLogo));
            }
            if (firstStepData.visiblePortfolio) {
                formData.append('portfolio', firstStepData.selectedPortfolio.key);
            }
            if (firstStepData.visibleProgramme) {
                formData.append('programme', firstStepData.selectedProgramme.key);
            }
            formData.append('projectCategory', secondStepData.selectedCategory.key);
            formData.append('projectScope', secondStepData.selectedScope.key);

            for (let key in configuration) {
                if (configuration.hasOwnProperty(key)) {
                    formData.append('configuration[' + key +']', configuration[key]);
                }
            }

            for (let i = 0; i < projectModules.length; i++) {
                formData.append('projectModules[' + i + '][module]', projectModules[i]['module']);
                if (projectModules[i]['isEnabled']) {
                    formData.append('projectModules[' + i + '][isEnabled]', projectModules[i]['isEnabled']);
                }
            }

            this.createProject(formData);
        },
        previousStep: function(e) {
            e.preventDefault();
            this.saveStepState();
            this.$router.push({name: 'projects-create-2'});
        },
        saveStepState: function() {
            const stepData = {
                projectContract: this.projectContract,
                projectOrganization: this.projectOrganization,
                plan: this.plan,
                taskManagement: this.taskManagement,
                phasesMilestones: this.phasesMilestones,
                costs: this.costs,
                resources: this.resources,
                risksOportunities: this.risksOportunities,
                communication: this.communication,
                controlMeasures: this.controlMeasures,
                statusReport: this.statusReport,
                meetings: this.meetings,
                todos: this.todos,
                notes: this.notes,
                closeDownProject: this.closeDownProject,
                raciMatrix: this.raciMatrix,
                taskChart: this.taskChart,
                ganttChart: this.ganttChart,
                context: this.context,
                decisions: this.decisions,
            };
            localStorage.setItem(THIRD_STEP_LOCALSTORAGE_KEY, JSON.stringify(stepData));
        },
    },
    data: function() {
        const stepData = JSON.parse(localStorage.getItem(THIRD_STEP_LOCALSTORAGE_KEY));

        return {
            message: {
                project_create_wizard: Translator.trans('message.project_create_wizard'),
                project_create_step3: Translator.trans('message.project_create_step3'),
                project_size: Translator.trans('message.project_size'),
                medium: Translator.trans('message.medium'),
                recommended_modules: Translator.trans('message.recommended_modules'),
                project_contract: Translator.trans('message.project_contract'),
                project_organization: Translator.trans('message.project_organization'),
                plan: Translator.trans('message.plan'),
                task_management: Translator.trans('message.task_management'),
                phases_milestones: Translator.trans('message.phases_milestones'),
                costs: Translator.trans('message.costs'),
                resources: Translator.trans('message.resources'),
                risks_oportunities: Translator.trans('message.risks_oportunities'),
                communication: Translator.trans('message.communication'),
                control_measures: Translator.trans('message.control_measures'),
                status_report: Translator.trans('message.status_report'),
                meetings: Translator.trans('message.meetings'),
                todos: Translator.trans('message.todos'),
                notes: Translator.trans('message.notes'),
                close_down_project: Translator.trans('message.close_down_project'),
                not_recommended_modules: Translator.trans('message.not_recommended_modules'),
                raci_matrix: Translator.trans('message.raci_matrix'),
                task_chart: Translator.trans('message.task_chart'),
                gantt_chart: Translator.trans('message.gantt_chart'),
                context: Translator.trans('message.context'),
                decisions: Translator.trans('message.decisions'),
            },
            button: {
                previous_step: Translator.trans('button.previous_step'),
                start_project: Translator.trans('button.start_project'),
            },
            projectContract: stepData ? stepData.projectContract : true,
            projectOrganization: stepData ? stepData.projectOrganization : true,
            plan: stepData ? stepData.plan : true,
            taskManagement: stepData ? stepData.taskManagement : true,
            phasesMilestones: stepData ? stepData.phasesMilestones : true,
            costs: stepData ? stepData.costs : true,
            resources: stepData ? stepData.resources : true,
            risksOportunities: stepData ? stepData.risksOportunities : true,
            communication: stepData ? stepData.communication : true,
            controlMeasures: stepData ? stepData.controlMeasures : true,
            statusReport: stepData ? stepData.statusReport : true,
            meetings: stepData ? stepData.meetings : true,
            todos: stepData ? stepData.todos : true,
            notes: stepData ? stepData.notes : true,
            closeDownProject: stepData ? stepData.closeDownProject : true,
            raciMatrix: stepData ? stepData.raciMatrix : false,
            taskChart: stepData ? stepData.taskChart : false,
            ganttChart: stepData ? stepData.ganttChart : false,
            context: stepData ? stepData.context : false,
            decisions: stepData ? stepData.decisions : false,
        };
    },
};
</script>

<style lang="scss">
    @import '../../css/project-create';
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';

    .st0 {
        fill:none;
        stroke:#65BEA3;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
    }

    .hr {
        background: $secondColor;
        width: 90px;
        margin: 0 auto;
    }

    .module .hr {
        margin: 0;
        width: 90px;
    }

    h2 {
        margin-bottom: 16px;
    }

    h3 {
        text-align: center;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 2.2px;
        margin-bottom: 54px;

        span {
            color: $secondColor;
        }
    }

    p {
        font-size: 10px;
        letter-spacing: 1.5px;
        margin-left: 38px;
        color: $lighterColor;
        margin-bottom: 18px;
    }

    .toggle-content {
        cursor: pointer;
    }
</style>
