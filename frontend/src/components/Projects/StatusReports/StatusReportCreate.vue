<template>
    <div class="project-status-report page-section">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="header">
                    <h1>
                        {{ project.name }}
                        <span>{{ translate('message.week') }} {{ getDuration(project.createdAt, null, 'weeks') }}</span>
                    </h1>
                </div>

                <div class="hero-text">
                    {{ translate('message.status_report') }}
                </div>

                <div class="row large-half-columns">
                    <div class="col-md-6">
                        <div class="widget same-height" v-resize="onResizeSameHeightDiv">
                            <h3>{{ translate('message.overall_status') }}</h3>
                            <div class="flex flex-center">
                                <div class="status-boxes big-status-boxes flex flex-v-center">
                                    <div class="status-box" :style="{background: project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                                    <div class="status-box" :style="{background: project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                                    <div class="status-box" :style="{background: project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                                </div>
                            </div>

                            <h4>{{ translate('message.tasks_status') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar middle-bg flex-v-center status-bar" :style="{width: (projectTasksStatus['label.open'] * 100) / (projectTasksStatus['label.open'] + projectTasksStatus['label.closed']) + '%'}">
                                    {{ translate('label.open') }}: {{ projectTasksStatus['label.open'] }}
                                </div>
                                <div class="bar main-bg flex-v-center status-bar" :style="{width: (projectTasksStatus['label.closed'] * 100) / (projectTasksStatus['label.open'] + projectTasksStatus['label.closed']) + '%'}">
                                    {{ translate('label.closed') }}: {{ projectTasksStatus['label.closed'] }}
                                </div>
                            </div>

                            <h4>{{ translate('message.tasks_condition') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div
                                    v-for="condition in projectTasksStatus.conditions"
                                    class="bar flex-v-center"
                                    :style="{width: computeWidth(condition), background: condition.color}">
                                    {{ condition.count }}
                                </div>
                            </div>

                            <div class="checkbox-input clearfix">
                                <input v-model="actionNeeded" id="action_needed" type="checkbox" name="" value="">
                                <label class="no-margin-bottom" for="action_needed">{{ translate('message.action_needed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="widget same-height" v-resize="onResizeSameHeightDiv">
                            <h3>{{ translate('message.project_trend') }}</h3>
                            <h4>{{ translate('message.current_date') }}: {{ today | moment('DD.MM.YYYY') }}</h4>
                            <vue-chart
                                :columns="trendColumns"
                                :rows="trendRows"
                                :options="trendOptions">
                            </vue-chart>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <!-- /// Project Status Comment /// -->
                            <div class="form-group last-form-group">
                                <editor
                                    v-model="comment"
                                    :label="'placeholder.comment'" />
                            </div>
                            <!-- /// End Project Staus Comment /// -->
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.schedule') }}</h3>
                    </div>
                    <div class="col-md-8">
                        <scrollbar class="table-wrapper customScrollbar">
                            <table class="table table-small">
                                <thead>
                                <tr>
                                    <th>{{ translate('table_header_cell.schedule') }}</th>
                                    <th>{{ translate('table_header_cell.start') }}</th>
                                    <th>{{ translate('table_header_cell.finish') }}</th>
                                    <th>{{ translate('table_header_cell.duration') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ translate('table_header_cell.base') }}</td>
                                    <td v-if="tasksForSchedule.base_start && tasksForSchedule.base_start.scheduledStartAt">
                                        {{ tasksForSchedule.base_start.scheduledStartAt | moment('DD.MM.YYYY') }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.base_finish && tasksForSchedule.base_finish.scheduledFinishAt">
                                        {{ tasksForSchedule.base_finish.scheduledFinishAt | moment('DD.MM.YYYY')  }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.base_start && tasksForSchedule.base_finish">
                                        {{ getDuration(tasksForSchedule.base_start.scheduledStartAt, tasksForSchedule.base_finish.scheduledFinishAt, 'days') }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                                <tr :class="forecastColorClass">
                                    <td>{{ translate('table_header_cell.forecast') }}</td>
                                    <td v-if="tasksForSchedule.forecast_start && tasksForSchedule.forecast_start.forecastStartAt">
                                        {{ tasksForSchedule.forecast_start.forecastStartAt | moment('DD.MM.YYYY')  }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.forecast_finish && tasksForSchedule.forecast_finish.forecastFinishAt">
                                        {{ tasksForSchedule.forecast_finish.forecastFinishAt | moment('DD.MM.YYYY')  }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.forecast_start && tasksForSchedule.forecast_finish">
                                        {{ getDuration(tasksForSchedule.forecast_start.forecastStartAt, tasksForSchedule.forecast_finish.forecastFinishAt) }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                                <tr :class="actualColorClass">
                                    <td>{{ translate('table_header_cell.actual') }}</td>
                                    <td v-if="tasksForSchedule.actual_start && tasksForSchedule.actual_start.actualStartAt">
                                        {{ tasksForSchedule.actual_start.actualStartAt | moment('DD.MM.YYYY')  }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.actual_finish && tasksForSchedule.actual_finish.actualFinishAt">
                                        {{ tasksForSchedule.actual_finish.actualFinishAt | moment('DD.MM.YYYY')  }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.actual_start && tasksForSchedule.actual_finish">
                                        {{ getDuration(tasksForSchedule.actual_start.actualStartAt, tasksForSchedule.actual_finish.actualFinishAt) }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </scrollbar>
                    </div>
                    <div class="col-md-4">
                        <div class="range-slider-legend">
                            <div class="legend-item">
                                <span>{{ translate('message.base_schedule') }}</span>
                                <div class="legend-bar darker-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translate('message.forecast_schedule') }}</span>
                                <div class="legend-bar middle-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translate('message.actual_schedule') }}</span>
                                <div class="legend-bar second-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translate('message.warning') }}</span>
                                <div class="legend-bar warning-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translate('message.danger') }}</span>
                                <div class="legend-bar danger-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="task-range-slider big-range-slider">
                            <!--TODO: determine the values for min and max for the bars-->
                            <!--<task-range-slider v-if="tasksForSchedule.base_start && tasksForSchedule.base_finish"-->
                                               <!--class="base dark-range-slider"-->
                                               <!--id="scheduleBase"-->
                                               <!--:message="translate('table_header_cell.base')"-->
                                               <!--min="2017-01-01"-->
                                               <!--max="2018-01-01"-->
                                               <!--:from="tasksForSchedule.base_start.scheduledStartAt"-->
                                               <!--:to="tasksForSchedule.base_finish.scheduledFinishAt"-->
                                               <!--type="double">-->
                            <!--</task-range-slider>-->
                            <!--<task-range-slider v-if="tasksForSchedule.forecast_start && tasksForSchedule.forecast_finish"-->
                                               <!--class="forecast warning"-->
                                               <!--id="translate('table_header_cell.forecast')"-->
                                               <!--message="Forecast"-->
                                               <!--min="2017-01-01"-->
                                               <!--max="2018-01-01"-->
                                               <!--:from="tasksForSchedule.forecast_start.forecastStartAt"-->
                                               <!--:to="tasksForSchedule.forecast_finish.forecastFinishAt "-->
                                               <!--type="double">-->
                            <!--</task-range-slider>-->
                            <!--<task-range-slider v-if="tasksForSchedule.actual_start && tasksForSchedule.actual_finish"-->
                                               <!--class="actual"-->
                                               <!--id="translate('table_header_cell.actual')"-->
                                               <!--message="Actual"-->
                                               <!--min="2017-01-01"-->
                                               <!--max="2018-01-01"-->
                                               <!--:from="tasksForSchedule.actual_start.actualStartAt"-->
                                               <!--:to="tasksForSchedule.actual_finish.actualFinishAt"-->
                                               <!--type="double">-->
                            <!--</task-range-slider>-->
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row statuses min-status">
                    <div class="col-md-4">
                        <div class="status" v-if="progresses.project_progress">
                            <circle-chart :bgStrokeColor="options.backgroundColor" :percentage="progresses.project_progress.value" :title="translate('message.overall_progress')" class="left dark-chart medium-chart center-content" :class="progresses.project_progress.class"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="progresses.task_progress">
                            <circle-chart :bgStrokeColor="options.backgroundColor" :percentage="progresses.task_progress.value" :title="translate('message.task_progress')" class="left center-content"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="progresses.cost_progress">
                            <circle-chart :bgStrokeColor="options.backgroundColor" :percentage="progresses.cost_progress.value" :title="translate('message.costs_progress')" class="left center-content"></circle-chart>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.phases_and_milestones') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" :style="{background: project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                        </div>

                        <vis-timeline :items="pmData" :withPhases="false" />
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.internal_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" :style="{background: internalCostsGraphData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: internalCostsGraphData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: internalCostsGraphData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                        </div>

                        <chart :data="internalCostsGraphData.byPhase"/>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.external_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" :style="{background: externalCostsGraphData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: externalCostsGraphData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: externalCostsGraphData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                        </div>

                        <chart :data="externalCostsGraphData.byPhase"/>
                    </div>
                </div>

                <hr class="double">

                <div class="row ro-columns large-half-columns">
                    <div class="col-md-6 dark-border-right">
                        <opportunities-grid
                                :value="opportunitiesGrid"
                                :currency="currency"/>
                    </div>

                    <div class="col-md-6">

                        <risks-grid
                                :value="risksGrid"
                                :currency="currency"/>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.todos') }}</h3>
                        <table class="table table-striped table-responsive table-fixed table-small" v-if="todos.items && todos.items.length > 0">
                            <thead>
                            <tr>
                                <th style="width:11%">{{ translate('table_header_cell.status') }}</th>
                                <th style="width:14%">{{ translate('table_header_cell.due_date') }}</th>
                                <th style="width:25%">{{ translate('table_header_cell.topic') }}</th>
                                <th style="width:36%">{{ translate('table_header_cell.description') }}</th>
                                <th style="width:14%">{{ translate('table_header_cell.responsible') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="todo in todos.items">
                                <td>{{ translate(todo.statusName) }}</td>
                                <td>{{ todo.dueDate | moment('DD.MM.YYYY') }}</td>
                                <td class="cell-wrap">{{ todo.title }}</td>
                                <td class="cell-wrap">{{ todo.description }}</td>
                                <td>
                                    <div class="avatar" v-tooltip.top-center="todo.responsibilityFullName" :style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <span v-else>{{ translate('label.no_data') }}</span>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.decisions') }}</h3>
                        <table class="table table-striped table-responsive table-fixed table-small" v-if="decisions.items && decisions.items.length > 0">
                            <thead>
                            <tr>
                                <th style="width:14%">{{ translate('table_header_cell.due_date') }}</th>
                                <th style="width:25%">{{ translate('table_header_cell.topic') }}</th>
                                <th style="width:36%">{{ translate('table_header_cell.description') }}</th>
                                <th style="width:14%">{{ translate('table_header_cell.responsible') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="decision in decisions.items">
                                <td>{{ decision.dueDate | moment('DD.MM.YYYY') }}</td>
                                <td class="cell-wrap">{{ decision.title }}</td>
                                <td class="cell-wrap cell-large" v-html="decision.description"></td>
                                <td>
                                    <div class="avatar" v-tooltip.top-center="decision.responsibilityFullName" :style="{ backgroundImage: 'url(' + decision.responsibilityAvatar + ')' }"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <span v-else>{{ translate('label.no_data') }}</span>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <div class="flex flex-space-between">
                            <a @click="saveReport()" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.save') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import VisTimeline from '../../_common/_phases-and-milestones-components/VisTimeline';
import Vue from 'vue';
import moment from 'moment';
import $ from 'jquery';
import 'jquery-match-height/jquery.matchHeight.js';
// import TaskRangeSlider from '../../_common/_task-components/TaskRangeSlider';
import CircleChart from '../../_common/_charts/CircleChart';
import Chart from './../Charts/CostsChart.vue';
import RiskGrid from '../Risks/RiskGrid';
import RiskList from '../Risks/RiskList';
import OpportunityList from '../Opportunities/OpportunityList';
import RiskSummary from '../Risks/RiskSummary';
import OpportunitySummary from '../Opportunities/OpportunitySummary';
import DownloadIcon from '../../_common/_icons/DownloadIcon';
import AtIcon from '../../_common/_icons/AtIcon';
import Editor from '../../_common/Editor';
import resize from 'vue-resize-directive';
import OpportunitiesGrid from './Create/OpportunitiesGrid';
import RisksGrid from './Create/RisksGrid';

export default {
    components: {
        VisTimeline,
        // TaskRangeSlider,
        CircleChart,
        RiskGrid,
        RiskList,
        RiskSummary,
        OpportunitySummary,
        OpportunityList,
        DownloadIcon,
        AtIcon,
        Chart,
        Editor,
        OpportunitiesGrid,
        RisksGrid,
    },
    directives: {
        resize,
    },
    created() {
        this.getProjectStatusReports({
            projectId: this.$route.params.id,
            queryParams: {
                trend: true,
            },
        });
        this.getProjectById(this.$route.params.id);
        this.getTasksStatus(this.$route.params.id);
        this.getTasksForSchedule(this.$route.params.id);
        this.getProgress(this.$route.params.id);
        this.getProjectInternalCostsGraphData({id: this.$route.params.id});
        this.getProjectExternalCostsGraphData({id: this.$route.params.id});
        this.setMilestonesFilters(
            {
                isKeyMilestone: true,
                startDate: moment().subtract(14, 'd').format('YYYY-MM-DD'),
                endDate: moment().add(14, 'd').format('YYYY-MM-DD'),
            }
        );
        this.getProjectMilestones({
            projectId: this.$route.params.id,
            apiParams: {
                page: 1,
            },
        });
        this.getProjectOpportunities(
            {
                projectId: this.$route.params.id,
                probability: 1,
                impact: 1,
            }
        );
        this.getProjectRisks(
            {
                projectId: this.$route.params.id,
                probability: 1,
                impact: 1,
            }
        );
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        this.setTodosFilters({statusReport: [1, 2]});
        this.getProjectTodos({
            projectId: this.$route.params.id,
            queryParams: {
                page: this.activePage,
            },
        });
        this.setDecisionsFilters({statusReport: true});
        this.getProjectDecisions({
            projectId: this.$route.params.id,
            queryParams: {
                page: this.activePage,
            },
        });
    },
    methods: {
        ...mapActions([
            'getProjectById', 'getTasksStatus', 'getTasksForSchedule', 'getProgress',
            'setMilestonesFilters', 'getProjectMilestones', 'getProjectExternalCostsGraphData',
            'getProjectOpportunities', 'getProjectRisks', 'getProjectRiskAndOpportunitiesStats',
            'setTodosFilters', 'getProjectTodos', 'getProjectInternalCostsGraphData',
            'setDecisionsFilters', 'getProjectDecisions', 'createStatusReport', 'getProjectStatusReports',
        ]),
        getDuration: function(startDate, endDate, unit) {
            let end = endDate ? moment(endDate) : moment();
            let start = moment(startDate);
            let diff = end.diff(start, unit);

            return !isNaN(diff) ? diff : '-';
        },
        saveReport: function() {
            let data = {
                information: {
                    week: this.getDuration(this.project.createdAt, null, 'weeks'),
                    project: {
                        id: this.$route.params.id,
                        name: this.project.name,
                        overallStatus: this.project.overallStatus,
                    },
                    projectTasksStatus: this.projectTasksStatus,
                    actionNeeded: this.actionNeeded,
                    projectTrend: this.trendRows,
                    comment: this.comment,
                    tasksForSchedule: this.tasksForSchedule,
                    progresses: this.progresses,
                    projectMilestones: this.projectMilestones,
                    externalCostsData: Object.assign({}, this.externalCostsGraphData),
                    internalCostsData: Object.assign({}, this.internalCostsGraphData),
                    opportunities: this.opportunities,
                    risks: this.risks,
                    risksOpportunitiesStats: this.risksOpportunitiesStats,
                    todos: this.todos,
                    decisions: this.decisions,
                },
            };
            // this needs to be fixed
            this.createStatusReport(data);
        },
        onResizeSameHeightDiv: function() {
            $('.same-height').matchHeight();
        },
        computeWidth: function(condition) {
            let width = (condition.count * 100) / (this.projectTasksStatus.conditions.total);
            if (!width) {
                width = 2;
            }
            return width + '%';
        },
    },
    computed: {
        ...mapGetters({
            project: 'project',
            projectTasksStatus: 'projectTasksStatus',
            tasksForSchedule: 'tasksForSchedule',
            progresses: 'progresses',
            projectMilestones: 'projectMilestones',
            externalCostsGraphData: 'externalCostsGraphData',
            internalCostsGraphData: 'internalCostsGraphData',
            opportunities: 'opportunities',
            risks: 'risks',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            todos: 'todos',
            decisions: 'decisions',
            statusReports: 'statusReports',
            projectCurrencySymbol: 'projectCurrencySymbol',
        }),
        pmData: function() {
            let items = [];
            if (this.projectMilestones && this.projectMilestones.items) {
                items = items.concat(this.projectMilestones.items.map((item) => {
                    return {
                        id: item.id,
                        group: 0,
                        content: item.name,
                        start: new Date(item.scheduledFinishAt),
                        value: item.workPackageStatus,
                        title: renderTooltip(item),
                    };
                }));
            }
            return items;
        },
        opportunitiesGrid() {
            if (!this.risksOpportunitiesStats || !this.risksOpportunitiesStats.opportunities) {
                return {};
            }

            return Object.assign({}, this.risksOpportunitiesStats.opportunities, {grid: this.opportunityGridData});
        },
        risksGrid() {
            if (!this.risksOpportunitiesStats || !this.risksOpportunitiesStats.risks) {
                return {};
            }

            return Object.assign({}, this.risksOpportunitiesStats.risks, {grid: this.riskGridData});
        },
        currency() {
            if (this.projectCurrencySymbol) {
                return this.projectCurrencySymbol;
            }

            return '';
        },
    },
    watch: {
        risksOpportunitiesStats(value) {
            let opportunityGridValues = this.risksOpportunitiesStats.opportunities.opportunity_data.gridValues;
            let riskGridValues = this.risksOpportunitiesStats.risks.risk_data.gridValues;
            const opportunityTypes = [
                ['very-high', 'very-high', 'high', 'medium'],
                ['very-high', 'high', 'medium', 'low'],
                ['high', 'medium', 'low', 'very-low'],
                ['medium', 'low', 'very-low', 'very-low'],
            ];
            const riskTypes = [
                ['very-low', 'very-low', 'low', 'medium'],
                ['very-low', 'low', 'medium', 'high'],
                ['low', 'medium', 'high', 'very-high'],
                ['medium', 'high', 'very-high', 'very-high'],
            ];
            for (let i = 4; i >= 1; i--) {
                for (let j = 1; j <= 4; j++) {
                    this.opportunityGridData.push(
                        {probability: j, impact: i, number: opportunityGridValues[j+'-'+i], type: opportunityTypes[i-1][j-1], isActive: false},
                    );
                    this.riskGridData.push(
                        {probability: j, impact: i, number: riskGridValues[j+'-'+i], type: riskTypes[i-1][j-1], isActive: false},
                    );
                }
            }
        },
        tasksForSchedule(value) {
            this.forecastColorClass = this.tasksForSchedule.forecast_finish.forecastFinishAt > this.tasksForSchedule.base_finish.scheduledFinishAt
                ? 'column-warning'
                : 'column'
            ;
            this.actualColorClass = this.tasksForSchedule.actual_finish.actualFinishAt > this.tasksForSchedule.forecast_finish.forecastFinishAt
                ? 'column-alert'
                : 'column'
            ;
        },
        projectMilestones(value) {
            for (let i = 0; i < this.projectMilestones.length; i++) {
                if (this.projectMilestones[i].colorStatusColor === '#ccba54') {
                    this.milestoneColorClass = '#ccba54';
                } else if (this.projectMilestones[i].colorStatusColor === '#c87369') {
                    this.milestoneColorClass = '#c87369';
                    break;
                }
            }
        },
        statusReports(value) {
            let trend = null;
            this.trendOptions.hAxis.maxValue = this.statusReports.items.length + 1;
            for (let i = 0; i < this.statusReports.items.length; i++) {
                if (this.statusReports.items[i].information) {
                    if (i === 0) {
                        trend = this.statusReports.items[i].information.project.overallStatus - 1;
                    } else {
                        if (
                            this.statusReports.items[i].information.project.overallStatus >
                            this.statusReports.items[i-1].information.project.overallStatus
                        ) {
                            trend += this.statusReports.items[i].information.project.overallStatus -
                                this.statusReports.items[i-1].information.project.overallStatus
                            ;
                        } else if (
                            this.statusReports.items[i].information.project.overallStatus <
                            this.statusReports.items[i-1].information.project.overallStatus
                        ) {
                            trend -= this.statusReports.items[i-1].information.project.overallStatus -
                                this.statusReports.items[i].information.project.overallStatus
                            ;
                        }
                    }
                    this.trendRows.push([i, trend]);
                }
            }
        },
    },
    data() {
        return {
            projectId: this.$route.params.id,
            actionNeeded: null,
            today: new Date(),
            comment: '',
            forecastColorClass: null,
            actualColorClass: null,
            milestoneColorClass: '#5FC3A5',
            opportunityGridData: [],
            riskGridData: [],
            activePage: 1,
            trendColumns: [{
                'type': 'number',
                'label': Translator.trans('label.week'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.trend'),
            }],
            trendRows: [],
            trendOptions: {
                title: Translator.trans('message.project_trend'),
                pointSize: 8,
                hAxis: {
                    gridlines: {
                        count: 5,
                        zeroLineColor: '#CCCCCC',
                        color: '#CCCCCC',
                    },
                    minValue: 0,
                    maxValue: 4,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                    baselineColor: '#CCCCCC',
                },
                vAxis: {
                    gridlines: {
                        count: 5,
                        zeroLineColor: '#CCCCCC',
                        color: '#CCCCCC',
                        drawBorder: false,
                    },
                    minValue: -2,
                    maxValue: 2,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                    baselineColor: '#CCCCCC',
                },
                width: '100%',
                height: 350,
                colors: ['#5FC3A5', '#A05555', '#646EA0', '#2E3D60', '#D8DAE5'],
                backgroundColor: '#191E37',
                titleTextStyle: {
                    color: '#D8DAE5',
                },
                legend: {
                    position: 'bottom',
                    maxLines: 1,
                },
                legendTextStyle: {
                    color: '#D8DAE5',
                },
            },
            options: {
                backgroundColor: '#191E37',
            },
        };
    },
};

/**
 * Render tooltip based of arguments
 * @param {Object} item
 * @return {string}
 */
function renderTooltip(item) {
    let forecastColorClass = 'column';
    if (moment(item.forecastFinishAt).diff(moment(item.scheduledFinishAt), 'days') > 0) {
        forecastColorClass = 'column-warning';
    } else if (moment(item.actualFinishAt).diff(moment(item.forecastFinishAt), 'days') > 0) {
        forecastColorClass = 'column-alert';
    }
    return `<div>
        <div class="task-box box">
            <div class="box-header">
                <div class="user-info flex flex-v-center">
                    <div class="user-avatar" v-bind:style="{ backgroundImage: 'url('` + item.responsibilityAvatar + `')' }"
                    v-tooltip.top-center="` + Vue.translate('table_header_cell.responsible') + item.responsibilityFullName +`"></div>
                    <p>` + item.responsibilityFullName + `</p>
                </div>
                <h2><router-link to="" class="simple-link">` + item.name + `</router-link></h2>
            </div>
            <div class="content">
                <table class="table table-small">
                    <thead>
                        <tr>
                            <th>` + Vue.translate('table_header_cell.schedule') + `</th>` +
                            `<th>` + Vue.translate('table_header_cell.date') + `</th>` +
                        `</tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>`+ Vue.translate('table_header_cell.base') +`</td>` +
                            `<td>` + (item.scheduledFinishAt ? item.scheduledFinishAt : '-') + `</td>` +
                        `</tr>
                        <tr class="` + forecastColorClass +`">
                            <td>` + Vue.translate('table_header_cell.forecast') +`</td>` +
                            `<td>` + (item.forecastFinishAt ? item.forecastFinishAt: '-') + `</td>` +
                        `</tr>` +
                    `</tbody>
                </table>
            </div>
            <div class="status">
                <p><span>` + Vue.translate('table_header_cell.status') + `:</span> ` + Vue.translate(item.workPackageStatusName) +`</p>
                <bar-chart position="right" :percentage="85" :color="Green" :title-right="green"></bar-chart>
            </div>
        </div>
    </div>`;
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 20px;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 10px;
                }
            }
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 30px;
    }

    .large-half-columns {
        @media (max-width: 1600px) {
            .col-md-6 {
                width: 100%;
                margin-bottom: 20px;

                &.dark-border-right {
                    border-right: none;
                }

                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }

    .widget {
        background-color: $darkColor;
        padding: 25px 20px;

        h3, h4 {
            margin: 0 0 10px;
            text-align: center;
        }
    }

    hr.double {
        margin: 40px 0;
    }

    .status-boxes {
        &.big-status-boxes{
            margin-bottom: 30px;

            .status-box {
                width: 56px;
                height: 56px;
                margin-right: 5px;
                background-color:$fadeColor;
            }
        }

        .status-box {
            background: $darkerColor;
            margin-right: 5px;
            width: 30px;
            height: 30px;
        }
    }

    .status-bar {
        margin-bottom: 30px;

        .bar {
            height: 30px;
            line-height: 30px;
            text-align: center;
            color: $whiteColor;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    }

    .range-slider-legend {
        text-align: right;
        font-size: 10px;
        text-transform: uppercase;

        .legend-item {
            margin-bottom: 10px;
            line-height: 1em;

            span {
                display: block;
                text-align: right;
            }

            .legend-bar {
                display: inline-block;
                width: 50%;
                height: 5px;
            }
        }
    }

    .task-range-slider {
        margin-bottom: -9px;
    }

    .statuses {
        .status {
            max-width: 400px;
            margin:20px auto 0;
            padding: 25px 20px;

            .chart {
                .text {
                    .title {
                        font-size: 12px;
                    }
                }

                &.center-content {
                    display: block;
                }
            }

            &:last-child {
                margin-bottom: 20px;
            }
        }
    }

    .dark-border-right {
        border-right: 1px solid $darkerColor;
    }

    .ro-columns {
        @media(min-width:1601px) {
            > .col-md-6 {
                &:first-child {
                    padding-right: 30px;
                }

                &:last-child {
                    padding-left: 30px;
                }
            }
        }
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;
        margin: 0 10px 0 0;
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        @include border-radius(50%);
    }

    .entry-responsible {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        line-height: 1.5em;
        margin: 20px 0;

        b {
            display: block;
            font-size: 12px;
        }
    }

    .cell-wrap {
        white-space: normal;
    }

    .cell-large {
        text-transform: none;
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    }

    .table-small > thead > tr > th {
        height: 60px;
        padding: 0 15px;
    }

    .status-bar {
        min-width: 10%;
        max-width: 90%;
        display: inline-table;
    }

    .center-content {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .min-status {
        min-width: 716px;
    }
</style>
