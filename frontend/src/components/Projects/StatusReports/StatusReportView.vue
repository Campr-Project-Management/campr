<template>
    <div class="project-status-report page-section">
        <div class="row" v-if="currentStatusReport.information">
            <modal v-if="showEmailModal" @close="showEmailModal = false">
                <p class="modal-title">{{ translate('message.email_report') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showEmailModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translate('message.no') }}</a>
                    <a href="javascript:void(0)" @click="emailReport()" class="btn-rounded">{{ translate('message.yes') }}</a>
                </div>
            </modal>

            <div class="col-lg-8 col-lg-offset-2">
                <div class="header">
                    <h1>
                        {{ currentStatusReport.information.project.name }}
                        <span>{{ translate('message.week') }} {{ currentStatusReport.information.week }}</span>
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
                                    <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                                    <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                                    <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                                </div>
                            </div>

                            <h4>{{ translate('message.tasks_status') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar middle-bg flex-v-center" :style="{width: (currentStatusReport.information.projectTasksStatus['label.open'] * 100) / (currentStatusReport.information.projectTasksStatus['label.open'] + currentStatusReport.information.projectTasksStatus['label.closed']) + '%'}">
                                    {{ translate('label.open') }}: {{ currentStatusReport.information.projectTasksStatus['label.open'] }}
                                </div>
                                <div class="bar main-bg flex-v-center" :style="{width: (currentStatusReport.information.projectTasksStatus['label.closed'] * 100) / (currentStatusReport.information.projectTasksStatus['label.open'] + currentStatusReport.information.projectTasksStatus['label.closed']) + '%'}">
                                    {{ translate('label.closed') }}: {{ currentStatusReport.information.projectTasksStatus['label.closed'] }}
                                </div>
                            </div>

                            <h4>{{ translate('message.tasks_condition') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div
                                    v-for="condition in currentStatusReport.information.projectTasksStatus.conditions"
                                    class="bar flex-v-center"
                                    :style="{width: (condition.count * 100) / (currentStatusReport.information.projectTasksStatus.conditions.total) + '%', background: condition.color}"
                                >
                                    {{ condition.count }}
                                </div>
                            </div>

                            <div class="checkbox-input clearfix">
                                <input v-model="actionNeeded" id="action_needed" disabled type="checkbox" name="" value="">
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
                                <br />
                                <div v-html="currentStatusReport.information.comment"></div>
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
                                        <td v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt">
                                            {{ currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt | moment('DD.MM.YYYY') }}
                                        </td>
                                        <td v-else>-</td>
                                        <td v-if="currentStatusReport.information.tasksForSchedule.base_finish && currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt">
                                            {{ currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt | moment('DD.MM.YYYY')  }}
                                        </td>
                                        <td v-else>-</td>
                                        <td  v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_finish">
                                            {{ getDuration(currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt, currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt, 'days') }}
                                        </td>
                                        <td v-else>-</td>
                                    </tr>
                                    <tr :class="forecastColorClass">
                                        <td>{{ translate('table_header_cell.forecast') }}</td>
                                        <td v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt">
                                            {{ currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt | moment('DD.MM.YYYY')  }}
                                        </td>
                                        <td v-else>-</td>
                                        <td v-if="currentStatusReport.information.tasksForSchedule.forecast_finish && currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt">
                                            {{ currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt | moment('DD.MM.YYYY')  }}
                                        </td>
                                        <td v-else>-</td>
                                        <td  v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_finish">
                                            {{ getDuration(currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt, currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt) }}
                                        </td>
                                        <td v-else>-</td>
                                    </tr>
                                    <tr :class="actualColorClass">
                                        <td>{{ translate('table_header_cell.actual') }}</td>
                                        <td v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt">
                                            {{ currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt | moment('DD.MM.YYYY')  }}
                                        </td>
                                        <td v-else>-</td>
                                        <td v-if="currentStatusReport.information.tasksForSchedule.actual_finish && currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt">
                                            {{ currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt | moment('DD.MM.YYYY')  }}
                                        </td>
                                        <td v-else>-</td>
                                        <td  v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_finish">
                                            {{ getDuration(currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt, currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt) }}
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
                            <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_finish"-->
                                    <!--class="base dark-range-slider"-->
                                    <!--id="scheduleBase"-->
                                    <!--:message="translate('table_header_cell.base')"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--:from="currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt"-->
                                    <!--:to="currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt"-->
                                    <!--type="double">-->
                            <!--</task-range-slider>-->
                            <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_finish"-->
                                    <!--class="forecast warning"-->
                                    <!--id="translate('table_header_cell.forecast')"-->
                                    <!--message="Forecast"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--:from="currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt"-->
                                    <!--:to="currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt "-->
                                    <!--type="double">-->
                            <!--</task-range-slider>-->
                            <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_finish"-->
                                    <!--class="actual"-->
                                    <!--id="translate('table_header_cell.actual')"-->
                                    <!--message="Actual"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--:from="currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt"-->
                                    <!--:to="currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt"-->
                                    <!--type="double">-->
                            <!--</task-range-slider>-->
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row statuses">
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.project_progress">
                            <circle-chart :percentage="currentStatusReport.information.progresses.project_progress.value" :title="translate('message.overall_progress')" class="left dark-chart medium-chart" :class="currentStatusReport.information.progresses.project_progress.class"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.task_progress">
                            <circle-chart :percentage="currentStatusReport.information.progresses.task_progress.value" :title="translate('message.task_progress')" class="left dark-chart medium-chart" :class="currentStatusReport.information.progresses.task_progress.class"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.cost_progress">
                            <circle-chart :percentage="currentStatusReport.information.progresses.cost_progress.value" :title="translate('message.costs_progress')"  class="left dark-chart medium-chart" :class="currentStatusReport.information.progresses.cost_progress.class"></circle-chart>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.phases_and_milestones') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>
                            <div class="status-box" :style="{background: currentStatusReport.information.project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>
                        </div>

                        <vis-timeline :items="pmData" />
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.internal_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <!-- needs to be fixed -->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.costData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.costData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.costData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                        </div>

                        <vue-chart
                            chart-type="ColumnChart"
                            :columns="columns"
                            :rows="costRowsByPhase"
                            :options="options">
                        </vue-chart>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translate('message.external_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <!-- needs to be fixed -->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" :style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                        </div>

                        <vue-chart
                            chart-type="ColumnChart"
                            :columns="columns"
                            :rows="resourceRowsByPhase"
                            :options="options">
                        </vue-chart>
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
                        <table class="table table-striped table-responsive table-fixed table-small" v-if="currentStatusReport.information.todos.items && currentStatusReport.information.todos.items.length > 0">
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
                            <tr v-for="todo in currentStatusReport.information.todos.items">
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
                        <table class="table table-striped table-responsive table-fixed table-small" v-if="currentStatusReport.information.decisions.items && currentStatusReport.information.decisions.items.length > 0">
                            <thead>
                                <tr>
                                    <th style="width:14%">{{ translate('table_header_cell.due_date') }}</th>
                                    <th style="width:25%">{{ translate('table_header_cell.topic') }}</th>
                                    <th style="width:36%">{{ translate('table_header_cell.description') }}</th>
                                    <th style="width:14%">{{ translate('table_header_cell.responsible') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="decision in currentStatusReport.information.decisions.items">
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
                            <a :href="downloadPdf" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.download_pdf') }} <download-icon fill="white-fill"></download-icon></a>
                            <a @click="showEmailModal = true" class="btn-rounded btn-auto btn-auto second-bg">{{ translate('button.email_status_report') }} <at-icon fill="white-fill"></at-icon></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
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
import RiskGrid from '../Risks/RiskGrid';
import RiskList from '../Risks/RiskList';
import OpportunityList from '../Opportunities/OpportunityList';
import RiskSummary from '../Risks/RiskSummary';
import OpportunitySummary from '../Opportunities/OpportunitySummary';
import DownloadIcon from '../../_common/_icons/DownloadIcon';
import AtIcon from '../../_common/_icons/AtIcon';
import Modal from '../../_common/Modal';
import AlertModal from '../../_common/AlertModal.vue';
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
        Modal,
        AlertModal,
        OpportunitiesGrid,
        RisksGrid,
    },
    directives: {
        resize,
    },
    created() {
        this.getStatusReport(this.$route.params.reportId);
    },
    methods: {
        ...mapActions(['getStatusReport', 'emailStatusReport']),
        getDuration: function(startDate, endDate, unit) {
            let end = endDate ? moment(endDate) : moment();
            let start = moment(startDate);
            let diff = end.diff(start, unit);

            return !isNaN(diff) ? diff : '-';
        },
        onResizeSameHeightDiv: function() {
            $('.same-height').matchHeight();
        },
        emailReport: function() {
            this
                .emailStatusReport(this.$route.params.reportId)
                .then(
                    (response) => {
                        this.showSaved = true;
                        this.showEmailModal = false;
                    },
                    () => {
                        this.showFailed = true;
                        this.showEmailModal = false;
                    }
                );
        },
    },
    computed: {
        ...mapGetters([
            'currentStatusReport',
            'projectCurrencySymbol',
        ]),
        downloadPdf() {
            return Routing.generate('app_status_report_pdf', {id: this.$route.params.reportId});
        },
        opportunitiesGrid() {
            if (!this.currentStatusReport || !this.currentStatusReport.information) {
                return {};
            }

            let stats = this.currentStatusReport.information.risksOpportunitiesStats;

            if (!stats || !stats.opportunities) {
                return {};
            }

            let gridValues = stats.opportunities.opportunity_data.gridValues;
            let grid = [];

            for (let i = 4; i >= 1; i--) {
                for (let j = 1; j <= 4; j++) {
                    grid.push(
                        {probability: j, impact: i, number: gridValues[j+'-'+i], type: this.opportunityTypes[i-1][j-1], isActive: false},
                    );
                }
            }

            return Object.assign({}, stats.opportunities, {grid});
        },
        risksGrid() {
            if (!this.currentStatusReport || !this.currentStatusReport.information) {
                return {};
            }

            let stats = this.currentStatusReport.information.risksOpportunitiesStats;

            if (!stats || !stats.risks) {
                return {};
            }

            let gridValues = stats.risks.risk_data.gridValues;
            let grid = [];
            for (let i = 4; i >= 1; i--) {
                for (let j = 1; j <= 4; j++) {
                    grid.push(
                        {probability: j, impact: i, number: gridValues[j+'-'+i], type: this.riskTypes[i-1][j-1], isActive: false},
                    );
                }
            }

            return Object.assign({}, stats.risks, {grid});
        },
        currency() {
            if (this.projectCurrencySymbol) {
                return this.projectCurrencySymbol;
            }

            return '';
        },
        pmData() {
            let items = [];
            if (!this.currentStatusReport || !this.currentStatusReport.information) {
                return items;
            }

            let information = this.currentStatusReport.information;
            if (!information || !information.projectMilestones) {
                return items;
            }

            return items.concat(information.projectMilestones.items.map((item) => {
                return {
                    id: item.id,
                    group: 0,
                    content: item.name,
                    start: new Date(item.scheduledFinishAt),
                    value: item.workPackageStatus,
                    title: renderTooltip(item),
                };
            }));
        },
    },
    watch: {
        currentStatusReport(value) {
            this.actionNeeded = this.currentStatusReport.information.actionNeeded;
            let information = this.currentStatusReport.information;
            if (information) {
                this.forecastColorClass =
                    information.tasksForSchedule.forecast_finish.forecastFinishAt > information.tasksForSchedule.base_finish.scheduledFinishAt
                    ? 'column-warning'
                    : 'column'
                ;
                this.actualColorClass =
                    information.tasksForSchedule.actual_finish.actualFinishAt > information.tasksForSchedule.forecast_finish.forecastFinishAt
                    ? 'column-alert'
                    : 'column'
                ;
                // broken
                // Object.entries(information.costData.byPhase).map(([key, value]) => {
                //     this.costRowsByPhase.push([
                //         key,
                //         value.base ? parseInt(value.base) : 0,
                //         value.actual ? parseInt(value.actual) : 0,
                //         value.forecast ? parseInt(value.forecast) : 0,
                //         value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                //     ]);
                // });
                // Object.entries(information.resourceData.byPhase).map(([key, value]) => {
                //     this.resourceRowsByPhase.push([
                //         key,
                //         value.base ? parseInt(value.base) : 0,
                //         value.actual ? parseInt(value.actual) : 0,
                //         value.forecast ? parseInt(value.forecast) : 0,
                //         value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                //     ]);
                // });
                this.trendRows = information.projectTrend ? information.projectTrend : [];
            }
        },
    },
    data() {
        return {
            riskTypes: [
                ['very-low', 'very-low', 'low', 'medium'],
                ['very-low', 'low', 'medium', 'high'],
                ['low', 'medium', 'high', 'very-high'],
                ['medium', 'high', 'very-high', 'very-high'],
            ],
            opportunityTypes: [
                ['very-high', 'very-high', 'high', 'medium'],
                ['very-high', 'high', 'medium', 'low'],
                ['high', 'medium', 'low', 'very-low'],
                ['medium', 'low', 'very-low', 'very-low'],
            ],
            actionNeeded: null,
            today: new Date(),
            comment: null,
            forecastColorClass: null,
            actualColorClass: null,
            activePage: 1,
            showEmailModal: false,
            showSaved: false,
            showFailed: false,
            trendColumns: [{
                'type': 'number',
                'label': Translator.trans('label.week'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.trend'),
            }],
            trendRows: [],
            columns: [{
                'type': 'string',
                'label': Translator.trans('message.total'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.base'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.actual'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.forecast'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.remaining'),
            }],
            costRowsByPhase: [
                ['', 0, 0, 0, 0],
            ],
            resourceRowsByPhase: [
                ['', 0, 0, 0, 0],
            ],
            trendOptions: {
                title: Translator.trans('message.project_trend'),
                pointSize: 8,
                hAxis: {
                    gridlines: {
                        count: 5,
                    },
                    minValue: 0,
                    maxValue: 4,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                vAxis: {
                    gridlines: {
                        count: 5,
                    },
                    minValue: -2,
                    maxValue: 2,
                    textStyle: {
                        color: '#D8DAE5',
                    },
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
                title: Translator.trans('message.costs_chart'),
                hAxis: {
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                vAxis: {
                    title: '',
                    minValue: 0,
                    maxValue: 0,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                width: '100%',
                height: 350,
                curveType: 'function',
                colors: ['#5FC3A5', '#A05555', '#646EA0', '#2E3D60', '#D8DAE5'],
                backgroundColor: '#191E37',
                titleTextStyle: {
                    color: '#D8DAE5',
                },
                legend: {
                    position: 'bottom',
                    maxLines: 5,
                },
                legendTextStyle: {
                    color: '#D8DAE5',
                },
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
                    v-tooltip.top-center="` + Vue.translate('table_header_cell.responsible') + item.responsibilityFullName + `"></div>
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
            background-color: $darkColor;
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

    .ro-grid-wrapper {
        .ro-grid {
            width: 100%;
            float: none;
        }

        .ro-list {
            width: 100%;
            float: none;
        }

        .ro-summary {
            font-size: 0.875em;
            margin-top: 5px;
            padding-top: 5px;
            padding-bottom: 0;
            border-top: 1px solid $darkColor;
        }

        .ro-reprezentative {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid $darkColor;
        }

        .ro-main {
            margin-top: 10px;

            .ro-main-stats {
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            &.ro-main-opportunity {
                .ro-main-stats {
                    .ro-very-high-priority {
                        color: $secondDarkColor;
                    }
                    .ro-high-priority {
                        color: $secondColor;
                    }
                    .ro-medium-priority {
                        color: $warningColor;
                    }
                    .ro-low-priority {
                        color: $dangerColor;
                    }
                    .ro-very-low-priority {
                        color: $dangerDarkColor;
                    }
                }
            }

            &.ro-main-risk {
                .ro-main-stats {
                    .ro-very-high-priority {
                        color: $secondDarkColor;
                    }
                    .ro-high-priority {
                        color: $secondColor;
                    }
                    .ro-medium-priority {
                        color: $warningColor;
                    }
                    .ro-low-priority {
                        color: $dangerColor;
                    }
                    .ro-very-low-priority {
                        color: $dangerDarkColor;
                    }
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
</style>
