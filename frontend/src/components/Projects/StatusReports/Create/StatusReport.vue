<template>
    <div>
        <div class="header">
            <h1>
                {{ projectName }}
                <span>{{ translate('message.week') }} {{ weekNumber }}</span>
            </h1>
        </div>

        <div class="hero-text">
            {{ translate('message.status_report') }}
        </div>

        <div class="row large-half-columns">
            <div class="col-md-6">
                <div class="widget">
                    <h3>{{ translate('message.overall_status') }}</h3>
                    <div class="flex flex-center">
                        <traffic-light :status="projectTrafficLight" size="large"/>
                    </div>

                    <h4>{{ translate('message.tasks_status') }}</h4>
                    <progress-bar-chart :series="tasksStatusSeries"/>
                    <br/>

                    <h4>{{ translate('message.tasks_condition') }}</h4>
                    <progress-bar-chart
                            :series="tasksTrafficLightSeries"
                            :options="{labels: {enabled: false}}"/>
                    <br/>

                    <div class="checkbox-input clearfix">
                        <template v-if="editable">
                            <input
                                    :value="value.projectActionNeeded"
                                    id="projectActionNeeded"
                                    type="checkbox"
                                    @input="onActionNeededUpdate"/>
                        </template>
                        <template v-else>
                            <input
                                    :value="report.projectActionNeeded"
                                    type="checkbox"
                                    :disabled="true"/>
                        </template>
                        <label class="no-margin-bottom" for="projectActionNeeded">{{ translate('message.action_needed') }}</label>
                        <error :at-path="projectActionNeeded"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="widget">
                    <h3>{{ translate('message.project_trend') }}</h3>
                    <h4>{{ translate('message.current_date') }}: {{ report.createdAt | date }}</h4>

                    <status-report-trend-chart
                            v-if="trendChartData.length > 0"
                            :data="trendChartData"
                            :labels="trendChartLabels"
                            :point-color="trendChartPointColor"/>
                    <div class="trend-no-results" v-else>{{ translate('message.not_enough_data') }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form">
                    <div class="form-group last-form-group">
                        <template v-if="editable">
                            <editor
                                    :value="value.comment"
                                    label="placeholder.comment"
                                    @input="onCommentUpdate"/>
                            <error at-path="comment"/>
                        </template>
                        <template v-else-if="report.comment">
                            <br/>
                            <h4>{{ translate('message.comment') }}</h4>
                            <div v-html="report.comment"></div>
                        </template>
                    </div>
                    <!-- /// End Project Staus Comment /// -->
                </div>
            </div>
        </div>

        <hr class="double">

        <template v-if="schedule">
            <div class="row" >
                <div class="col-md-12">
                    <h3 class="margintop0">{{ translate('message.schedule') }}</h3>
                </div>
            </div>

            <status-report-schedule
                    :base-start-at="schedule.base.startAt"
                    :base-finish-at="schedule.base.finishAt"
                    :base-duration-days="schedule.base.durationDays"
                    :forecast-start-at="schedule.forecast.startAt"
                    :forecast-finish-at="schedule.forecast.finishAt"
                    :forecast-duration-days="schedule.forecast.durationDays"
                    :actual-start-at="schedule.actual.startAt"
                    :actual-finish-at="schedule.actual.finishAt"
                    :actual-duration-days="schedule.actual.durationDays"/>

            <hr class="double">
        </template>

        <div class="row statuses min-status" v-if="progress">
            <div class="col-md-4">
                <div class="status">
                    <circle-chart
                            :bgStrokeColor="options.backgroundColor"
                            :percentage="progress.tasks"
                            :title="translate('message.task_progress')"
                            class="left center-content"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="status">
                    <circle-chart
                            :bgStrokeColor="options.backgroundColor"
                            :percentage="progress.costs"
                            :title="translate('message.costs_progress')"
                            class="left center-content"/>
                </div>
            </div>
        </div>

        <hr class="double">

        <div class="row">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.phases_and_milestones') }}</h3>
                <traffic-light :status="projectTrafficLight"/>

                <vis-timeline :items="pmData" :with-phases="true"/>
            </div>
        </div>

        <hr class="double">

        <div class="row" v-if="internalCostsGraphData">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.internal_costs') }}</h3>
                <div class="marginbottom20">
                    <traffic-light :status="internalCostsTrafficLight"/>
                </div>

                <chart :data="internalCostsGraphData"/>
            </div>
        </div>

        <hr class="double">

        <div class="row" v-if="externalCostsGraphData">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.external_costs') }}</h3>
                <div class="marginbottom20">
                    <traffic-light :status="externalCostsTrafficLight"/>
                </div>

                <chart :data="externalCostsGraphData"/>
            </div>
        </div>

        <hr class="double">

        <div class="row ro-columns large-half-columns">
            <div class="col-md-6 dark-border-right">
                <opportunities-grid :value="opportunitiesGrid" :currency="currency"/>
            </div>

            <div class="col-md-6">
                <risks-grid :value="risksGrid" :currency="currency"/>
            </div>
        </div>

        <hr class="double">

        <div class="row">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.todos') }}</h3>
                <status-report-todos :items="todosItems"/>
            </div>
        </div>

        <hr class="double">

        <div class="row">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.decisions') }}</h3>
                <status-report-decisions :items="decisionsItems"/>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import VisTimeline from '../../../_common/_phases-and-milestones-components/VisTimeline';
    import Vue from 'vue';
    import moment from 'moment';
    import 'jquery-match-height/jquery.matchHeight.js';
    import CircleChart from '../../../_common/_charts/CircleChart';
    import Chart from '.././../Charts/CostsChart.vue';
    import RiskGrid from '../../Risks/RiskGrid';
    import RiskList from '../../Risks/RiskList';
    import OpportunityList from '../../Opportunities/OpportunityList';
    import RiskSummary from '../../Risks/RiskSummary';
    import OpportunitySummary from '../../Opportunities/OpportunitySummary';
    import DownloadIcon from '../../../_common/_icons/DownloadIcon';
    import AtIcon from '../../../_common/_icons/AtIcon';
    import Editor from '../../../_common/Editor';
    import TrafficLight from '../../../_common/TrafficLight';
    import OpportunitiesGrid from './OpportunitiesGrid';
    import RisksGrid from './RisksGrid';
    import colors from '../../../../util/colors';
    import ProgressBarChart from '../../../_common/_charts/ProgressBarChart';
    import StatusReportTodos from './Todos';
    import StatusReportDecisions from './Decisions';
    import StatusReportTrendChart from './TrendChart';
    import Error from '../../../_common/_messages/Error';
    import StatusReportSchedule from './Schedule';

    export default {
        name: 'status-report',
        props: {
            report: {
                type: Object,
                required: true,
            },
            value: {
                type: Object,
                required: false,
                default: () => {},
            },
            editable: {
                type: Boolean,
                required: false,
                default: true,
            },
        },
        components: {
            StatusReportSchedule,
            Error,
            StatusReportTrendChart,
            StatusReportDecisions,
            StatusReportTodos,
            ProgressBarChart,
            VisTimeline,
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
            TrafficLight,
        },
        created() {
            this.getProjectUsers({id: this.$route.params.id});
            this.getStatusReportTrendGraph(this.$route.params.id);
        },
        methods: {
            ...mapActions([
                'setMilestonesFilters',
                'getProjectMilestones',
                'getStatusReportTrendGraph',
                'createStatusReport',
                'getProjectUsers',
            ]),
            onActionNeededUpdate(event) {
                if (!this.editable) {
                    return;
                }

                this.$emit('input', Object.assign({}, this.value, {projectActionNeeded: event.target.checked}));
            },
            onCommentUpdate(value) {
                if (!this.editable) {
                    return;
                }

                this.$emit('input', Object.assign({}, this.value, {comment: value}));
            },
        },
        computed: {
            ...mapGetters([
                'project',
                'projectMilestones',
                'statusReportTrendGraph',
            ]),
            projectName() {
                return this.report.projectName;
            },
            projectTrafficLight() {
                return this.snapshot.trafficLight;
            },
            weekNumber() {
                return this.report.weekNumber;
            },
            snapshot() {
                return this.report.information;
            },
            tasksStatusSeries() {
                return [
                    {
                        name: 'label.open',
                        value: this.snapshot.tasks.total.status.opened,
                        color: '#646EA0',
                    }, {
                        name: 'label.closed',
                        value: this.snapshot.tasks.total.status.closed,
                        color: '#232D4B',
                    },
                ];
            },
            tasksTrafficLightSeries() {
                return [
                    {
                        name: 'color_status.finished',
                        value: this.snapshot.tasks.total.trafficLight.green,
                        color: colors.trafficLight.green,
                    }, {
                        name: 'color_status.in_progress',
                        value: this.snapshot.tasks.total.trafficLight.yellow,
                        color: colors.trafficLight.yellow,
                    }, {
                        name: 'color_status.not_started',
                        value: this.snapshot.tasks.total.trafficLight.red,
                        color: colors.trafficLight.red,
                    },
                ];
            },
            schedule() {
                return {
                    base: {
                        startAt: this.snapshot.schedule.scheduled.startAt,
                        finishAt: this.snapshot.schedule.scheduled.finishAt,
                        durationDays: this.snapshot.schedule.scheduled.durationDays,
                    },
                    forecast: {
                        startAt: this.snapshot.schedule.forecast.startAt,
                        finishAt: this.snapshot.schedule.forecast.finishAt,
                        durationDays: this.snapshot.schedule.forecast.durationDays,
                    },
                    actual: {
                        startAt: this.snapshot.schedule.actual.startAt,
                        finishAt: this.snapshot.schedule.actual.finishAt,
                        durationDays: this.snapshot.schedule.actual.durationDays,
                    },
                };
            },
            progress() {
                return {
                    tasks: this.snapshot.tasks.progress,
                    costs: this.snapshot.costs.progress,
                };
            },
            internalCostsTrafficLight() {
                return this.snapshot.costs.internal.total.trafficLight;
            },
            externalCostsTrafficLight() {
                return this.snapshot.costs.external.total.trafficLight;
            },
            internalCostsGraphData() {
                let data = {};
                this.snapshot.costs.internal.graphs.byPhase.forEach((row) => {
                    data[row.name] = row.values;
                });

                return data;
            },
            externalCostsGraphData() {
                let data = {};
                this.snapshot.costs.external.graphs.byPhase.forEach((row) => {
                    data[row.name] = row.values;
                });

                return data;
            },
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
                let data = {
                    top: this.snapshot.opportunities.topItem,
                    grid: [],
                    summary: {
                        potentialCost: this.snapshot.opportunities.total.potentialCost,
                        potentialDelay: this.snapshot.opportunities.total.potentialDelay,
                        measuresCount: this.snapshot.opportunities.total.measuresCount,
                        measuresCost: this.snapshot.opportunities.total.measuresCost,
                    },
                };

                for (let i = 4; i >= 1; i--) {
                    for (let j = 1; j <= 4; j++) {
                        data.grid.push(
                            {
                                probability: j,
                                impact: i,
                                number: this.snapshot.opportunities.grid[j + '-' + i],
                                type: this.opportunityTypes[i - 1][j - 1],
                                isActive: false,
                            },
                        );
                    }
                }

                return data;
            },
            risksGrid() {
                let data = {
                    top: this.snapshot.risks.topItem,
                    grid: [],
                    summary: {
                        potentialCost: this.snapshot.risks.total.potentialCost,
                        potentialDelay: this.snapshot.risks.total.potentialDelay,
                        measuresCount: this.snapshot.risks.total.measuresCount,
                        measuresCost: this.snapshot.risks.total.measuresCost,
                    },
                };

                for (let i = 4; i >= 1; i--) {
                    for (let j = 1; j <= 4; j++) {
                        data.grid.push(
                            {
                                probability: j,
                                impact: i,
                                number: this.snapshot.risks.grid[j + '-' + i],
                                type: this.riskTypes[i - 1][j - 1],
                                isActive: false,
                            },
                        );
                    }
                }

                return data;
            },
            todosItems() {
                return this.snapshot.todos.items;
            },
            decisionsItems() {
                return this.snapshot.decisions.items;
            },
            trendChartData() {
                return this.statusReportTrendGraph.map(data => {
                    return data.value;
                });
            },
            trendChartPointColor() {
                return this.statusReportTrendGraph.map(data => {
                    return colors.trafficLight[data.color];
                });
            },
            trendChartLabels() {
                return this.statusReportTrendGraph.map(data => {
                    return `${this.translate('message.week')} ${data.week}`;
                });
            },
            currency() {
                return this.snapshot.currency.symbol;
            },
        },
        data() {
            return {
                projectId: this.$route.params.id,
                actionNeeded: null,
                comment: '',
                milestoneColorClass: '#5FC3A5',
                options: {
                    backgroundColor: '#191E37',
                },
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
                    v-tooltip.top-center="` + Vue.translate('table_header_cell.responsible') +
            item.responsibilityFullName + `"></div>
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
                            <td>` + Vue.translate('table_header_cell.base') + `</td>` +
            `<td>` + (item.scheduledFinishAt ? item.scheduledFinishAt : '-') + `</td>` +
            `</tr>
                        <tr class="` + forecastColorClass + `">
                            <td>` + Vue.translate('table_header_cell.forecast') + `</td>` +
            `<td>` + (item.forecastFinishAt ? item.forecastFinishAt : '-') + `</td>` +
            `</tr>` +
            `</tbody>
                </table>
            </div>
            <div class="status">
                <p><span>` + Vue.translate('table_header_cell.status') + `:</span> ` +
            Vue.translate(item.workPackageStatusName) + `</p>
                <bar-chart position="right" :percentage="85" :color="Green" :title-right="green"></bar-chart>
            </div>
        </div>
    </div>`;
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../../css/_variables';
    @import '../../../../css/_mixins';

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
        height: 360px;

        h3, h4 {
            margin: 0 0 10px;
            text-align: center;
        }
    }

    hr.double {
        margin: 40px 0;
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

    .task-range-slider {
        margin-bottom: -9px;
    }

    .statuses {
        .status {
            max-width: 400px;
            margin: 20px auto 0;
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
        @media(min-width: 1601px) {
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

    .trend-no-results {
        text-align: center;
        color: $middleColor;
        min-height: 80%;
        line-height: 20em;
    }
</style>
