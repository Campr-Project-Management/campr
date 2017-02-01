<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-3">
            <h1>Project Creation Wizard</h1>
            <h2>Step 3: Project Builder</h2>
            <div class="hr"></div>
            <h3>Project Size: <span>Medium</span></h3>

            <p>based on your estimations we recommend the following modules</p>

            <project-module title="Project Contract" id="project-contract"></project-module>
            <project-module title="Project Orgnization" id="project-organization"></project-module>
            <project-module title="Plan" id="plan"></project-module>
            <project-module title="Task Management" id="task-management"></project-module>
            <project-module title="Phases and Milestones" id="phases-milestones"></project-module>
            <project-module title="Costs" id="costs"></project-module>
            <project-module title="Resources" id="resources"></project-module>
            <project-module title="Risks and Opportunities" id="risks-opportunities"></project-module>
            <project-module title="Communication" id="communication"></project-module>
            <project-module title="Control Measures" id="control-measures"></project-module>
            <project-module title="Status Report" id="status-report"></project-module>
            <project-module title="Meetings" id="meetings"></project-module>
            <project-module title="Todos" id="todos"></project-module>
            <project-module title="Notes" id="notes"></project-module>
            <project-module title="Close Down Project" id="close-down-project"></project-module>

            <p>we don’t recommend using the remaining modules but you can still activate them</p>

            <project-module title="Raci Matrix" id="raci-matrix" inactive="1"></project-module>
            <project-module title="Task Chart" id="task-chart" inactive="1"></project-module>
            <project-module title="Gantt Chart" id="gantt-chart" inactive="1"></project-module>
            <project-module title="Context" id="context" inactive="1"></project-module>
            <project-module title="Decisions" id="decisions" inactive="1"></project-module>

            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'projects-create-2'}" title="< Previous Step" class="btn-rounded btn-right">
                    < Previous Step
                </router-link>
                <router-link :to="{name: 'projects-create-3'}" title="Start Project" class="btn-rounded btn-right second-bg">
                    Analyse
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
