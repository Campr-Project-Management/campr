<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-3">
            <h1>{{ message.project_create_wizard }}</h1>
            <h2>{{ message.project_create_step3 }}</h2>
            <div class="hr"></div>
            <h3>{{ message.project_size }}: <span>{{ message.medium }}</span></h3>

            <p>{{ message.recommended_modules }}</p>

            <project-module v-bind:title="message.project_contract" id="project-contract"></project-module>
            <project-module v-bind:title="message.project_organization" id="project-organization"></project-module>
            <project-module v-bind:title="message.plan" id="plan"></project-module>
            <project-module v-bind:title="message.task_management" id="task-management"></project-module>
            <project-module v-bind:title="message.phases_milestones" id="phases-milestones"></project-module>
            <project-module v-bind:title="message.costs" id="costs"></project-module>
            <project-module v-bind:title="message.resources" id="resources"></project-module>
            <project-module v-bind:title="message.risk_oportunities" id="risks-opportunities"></project-module>
            <project-module v-bind:title="message.communication" id="communication"></project-module>
            <project-module v-bind:title="message.control_measures" id="control-measures"></project-module>
            <project-module v-bind:title="message.status_report" id="status-report"></project-module>
            <project-module v-bind:title="message.meetings" id="meetings"></project-module>
            <project-module v-bind:title="message.todos" id="todos"></project-module>
            <project-module v-bind:title="message.notes" id="notes"></project-module>
            <project-module v-bind:title="message.close_down_project" id="close-down-project"></project-module>

            <p>{{ message.not_recommended_modules }}</p>

            <project-module v-bind:title="message.raci_matrix" id="raci-matrix" inactive="1"></project-module>
            <project-module v-bind:title="message.task_chart" id="task-chart" inactive="1"></project-module>
            <project-module v-bind:title="message.gantt_chart" id="gantt-chart" inactive="1"></project-module>
            <project-module v-bind:title="message.context" id="context" inactive="1"></project-module>
            <project-module v-bind:title="message.decisions" id="decisions" inactive="1"></project-module>

            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'projects-create-2'}" v-bind:title="button.previous_step" class="btn-rounded btn-right">
                    < {{ button.previous_step }}
                </router-link>
                <router-link :to="{name: 'projects-create-3'}" v-bind:title="button.start_project" class="btn-rounded btn-right second-bg">
                    {{ button.start_project }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import ProjectModule from './ProjectModule';

export default {
    components: {
        ProjectModule,
    },
    data: function() {
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
                risk_oportunities: Translator.trans('message.risk_oportunities'),
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
