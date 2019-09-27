<template>
    <page :project="project" :team="team" :title="report.projectName" :subtitle="translate('message.week') + ' ' + report.weekNumber">
        <div class="row">
            <div class="col-xs-6 hero-text" style="text-align: left;">
                {{ translate('message.status_report') }}
            </div>
            <div class="col-xs-6 hero-text">
                <traffic-light
                    style="text-align: right;"
                    :value="report.projectTrafficLight"
                    size="normal"
                    :editable="false"/>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <h4>{{ translate('message.tasks_status') }}</h4>
                <no-ssr>
                    <progress-bar-chart :series="tasksStatusSeries"/>
                </no-ssr>
            </div>
            <div class="col-xs-6">
                <h4>{{ translate('message.tasks_condition') }}</h4>
                <no-ssr>
                    <progress-bar-chart
                        :series="tasksTrafficLightSeries"
                        :options="{labels: {enabled: false}}"/>
                </no-ssr>
            </div>
            <br/>
        </div>

        <div class="row">
            <h3>
                {{ translate('message.project_trend') }}
                <span class="pull-right">{{ translate('message.current_date') }}: {{ report.createdAt | date }}</span>
            </h3>
            <div class="col-xs-4" style="height: 200px">
                <no-ssr>
                    <status-report-trend-chart
                            class="status-report-trend-chart"
                            v-if="trendChartData.length > 0"
                            :data="trendChartData"
                            :labels="trendChartLabels"
                            :point-color="trendChartPointColor"
                            :options="{responsive: false}"
                            :height="400"
                            :width="600"/>
                    <div class="no-results" v-else>{{ translate('message.not_enough_data') }}</div>
                </no-ssr>
            </div>
            <div class="col-xs-8">
                <div class="statuses min-status" v-if="progress">
                    <div class="col-xs-3">
                        <div class="status">
                            <no-ssr>
                                <circle-chart
                                    :bgStrokeColor="options.backgroundColor"
                                    :stroke-width="8"
                                    :percentage="projectPlannedProgress"
                                    :animation-duration="0"
                                    :title="translate('message.planned_progress')"
                                    class="left center-content"/>
                            </no-ssr>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="status">
                            <no-ssr>
                                <circle-chart
                                    :bgStrokeColor="options.backgroundColor"
                                    :stroke-width="8"
                                    :animation-duration="0"
                                    :percentage="progress.tasks"
                                    :title="translate('message.task_status')"
                                    class="left center-content"/>
                            </no-ssr>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="status">
                            <no-ssr>
                                <circle-chart
                                    :bgStrokeColor="options.backgroundColor"
                                    :stroke-width="8"
                                    :animation-duration="0"
                                    :percentage="progress.costs"
                                    :title="translate('message.cost_status')"
                                    class="left center-content"/>
                            </no-ssr>
                        </div>
                    </div>
                </div>

                <div class="no-results" v-else>{{ translate('message.not_enough_data') }}</div>
            </div>
        </div>


        <div class="row" v-if="report.comment">
            <h4>{{ translate('message.comment') }}</h4>
            <div class="ve-design" v-html="report.comment"></div>
        </div>

        <hr class="double">

        <template v-if="schedule">
            <div class="row">
                <h3>{{ translate('message.schedule') }}</h3>
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
            </div>
        </template>

        <hr class="double">

        <template v-if="isPhasesAndMilestoneModuleActive && (phases || milestones)">
            <div class="row">
                <h3>{{ translate('message.phases_and_milestones') }}</h3>

                <no-ssr>
                    <status-report-timeline
                        style="width: 777px"
                        :phases="phases"
                        :milestones="milestones"
                        :locale="forcedLocale"/>
                </no-ssr>
            </div>
        </template>

        <template v-if="isInternalCostsModuleActive && internalCostsGraphData && isExternalCostsModuleActive && externalCostsGraphData">
            <div class="row" style="padding-left: 0; padding-right: 0; height: 200px; clear: both; overflow: hidden;">
                <div class="col-xs-6" style="padding-left: 0;">
                    <h3>{{ translate('message.internal_costs') }}</h3>
                    <div class="resources-half">
                        <no-ssr>
                            <chart :data="internalCostsGraphData.byPhase" theme="print" />
                        </no-ssr>
                    </div>
                </div>
                <div class="col-xs-6" style="padding-left: 0;">
                    <h3>{{ translate('message.external_costs') }}</h3>
                    <div class="resources-half">
                        <no-ssr>
                            <chart :data="externalCostsGraphData.byPhase" theme="print" />
                        </no-ssr>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="row" v-if="isInternalCostsModuleActive && internalCostsGraphData">
                <h3>{{ translate('message.internal_costs') }}</h3>
                <div class="resources">
                    <no-ssr>
                        <chart :data="internalCostsGraphData.byPhase" theme="print" />
                    </no-ssr>
                </div>
            </div>

            <div class="row" v-if="isExternalCostsModuleActive && externalCostsGraphData">
                <h3>{{ translate('message.external_costs') }}</h3>
                <div class="resources">
                    <no-ssr>
                        <chart :data="externalCostsGraphData.byPhase" theme="print" />
                    </no-ssr>
                </div>
            </div>
        </template>

        <template v-if="isRiskAndOpportunitiesModuleActive">
            <div class="row ro-columns">
                <div class="col-md-6 col-xs-6 dark-border-right">
                    <no-ssr>
                        <opportunities-grid :value="opportunitiesGrid" :currency="currency"
                                            :locale="forcedLocale"/>
                    </no-ssr>
                </div>

                <div class="col-md-6 col-xs-6">
                    <no-ssr>
                        <risks-grid :value="risksGrid" :currency="currency" :locale="forcedLocale"/>
                    </no-ssr>
                </div>
            </div>

            <hr class="double">
        </template>

        <template v-if="isTodosModuleActive">
            <div class="row">
                <h3>{{ translate('message.todos') }}</h3>
                <status-report-todos :items="todosItems"/>
            </div>

            <hr class="double">
        </template>

        <template v-if="isDecisionsModuleActive">
            <div class="row">
                <h3>{{ translate('message.decisions') }}</h3>
                <status-report-decisions :items="decisionsItems"/>
            </div>

            <hr class="double">
        </template>

        <template v-if="isInfosModuleActive">
            <div class="row">
                <h3>{{ translate('message.infos') }}</h3>
                <status-report-infos :items="infosItems"/>
            </div>
        </template>
    </page>
</template>

<script>
    import Vue from 'vue';
    import Chart from '~/components/Charts/CostsChart.vue';
    import colors from '../../../../../../frontend/src/util/colors';
    import TrafficLight from '~/components/_common/TrafficLight.vue';
    import CircleChart from '~/components/_common/_charts/CircleChart';
    import ProgressBarChart from '~/components/_common/_charts/ProgressBarChart';
    import StatusReportTrendChart
        from '../../../../../../frontend/src/components/Projects/StatusReports/Create/TrendChart';
    import StatusReportSchedule from '~/components/Projects/StatusReports/Create/Schedule';
    import StatusReportTimeline from '~/components/Projects/StatusReports/Create/Timeline';
    import StatusReportTodos from '~/components/Projects/StatusReports/Create/Todos';
    import StatusReportDecisions from '~/components/Projects//StatusReports/Create/Decisions';
    import StatusReportInfos from '~/components/Projects/StatusReports/Create/Infos';
    import OpportunitiesGrid from '~/components/Projects/StatusReports/Create/OpportunitiesGrid';
    import RisksGrid from '~/components/Projects/StatusReports/Create/RisksGrid';
    import {
        MODULE_PHASES_AND_MILESTONES,
        MODULE_INTERNAL_COSTS,
        MODULE_EXTERNAL_COSTS,
        MODULE_RISKS_AND_OPPORTUNITIES,
        MODULE_TODOS,
        MODULE_DECISIONS,
        MODULE_INFOS,
    } from '../../../../../../frontend/src/helpers/project-module';
    import Page from '../../../../../layouts/Page';

    export default {
        components: {
            Page,
            Chart,
            CircleChart,
            ProgressBarChart,
            TrafficLight,
            OpportunitiesGrid,
            RisksGrid,
            StatusReportTrendChart,
            StatusReportSchedule,
            StatusReportTimeline,
            StatusReportTodos,
            StatusReportDecisions,
            StatusReportInfos,
        },
        validate({params}) {
            return /^\d+$/.test(params.id);
        },
        created() {
            if (this.locale) {
                Translator.locale = this.locale;
            }
        },
        async asyncData({params, query}) {
            let report = {};
            let project = {};
            let team = {};
            let statusReportTrendGraph = [];
            let locale = query.locale ? query.locale : '';

            if (query.host && query.key) {
                let res = await Vue.doFetch(`http://${query.host}/api/status-reports/${params.report}`, query.key);
                report = await res.json();

                // project
                res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}`, query.key);
                project = await res.json();

                res = await Vue.doFetch(`http://${query.host}/api/team`, query.key);
                team = await res.json();

                // project users
                // res = await Vue.doFetch(`http://${query.host}/api/projects/${params.id}/project-users`, query.key);

                // status report trend graph
                let url = `http://${query.host}/api/projects/${params.id}/status-reports/trend-graph?before=${report.createdAt}`;
                res = await Vue.doFetch(url, query.key);
                if (res.status === 200) {
                    statusReportTrendGraph = await res.json();
                }
            }

            if (project.projectUsers && report.information) {
                let avatarNeedsUpdate = ['todos', 'infos', 'decisions'];
                avatarNeedsUpdate.map(key => {
                    if (!report.information[key] || !report.information[key].items) {
                        return;
                    }
                    report.information[key].items.map((item, k) => {
                        if (!item.responsibilityId) {
                            return;
                        }

                        let projectUser = project.projectUsers.filter(pu => {
                                return pu.user === item.responsibilityId;
                            })
                        ;
                        if (projectUser.length) {
                            report.information[key].items[k].responsibilityAvatarUrl = projectUser[0].userAvatarUrl;
                        }
                    });
                });
            }

            return {
                report,
                team,
                project,
                statusReportTrendGraph,
                editableData: {},
                projectId: params.id,
                actionNeeded: null,
                milestoneColorClass: '#5FC3A5',
                options: {
                    backgroundColor: '#191E37',
                },
                locale,
            };
        },
        methods: {
            isModuleActive(module) {
                return this.report && this.report.modules && this.report.modules.indexOf(module) >= 0;
            },
        },
        computed: {
            isPhasesAndMilestoneModuleActive() {
                return this.isModuleActive(MODULE_PHASES_AND_MILESTONES);
            },
            isInternalCostsModuleActive() {
                return this.isModuleActive(MODULE_INTERNAL_COSTS);
            },
            isExternalCostsModuleActive() {
                return this.isModuleActive(MODULE_EXTERNAL_COSTS);
            },
            isRiskAndOpportunitiesModuleActive() {
                return this.isModuleActive(MODULE_RISKS_AND_OPPORTUNITIES);
            },
            isTodosModuleActive() {
                return this.isModuleActive(MODULE_TODOS);
            },
            isInfosModuleActive() {
                return this.isModuleActive(MODULE_INFOS);
            },
            isDecisionsModuleActive() {
                return this.isModuleActive(MODULE_DECISIONS);
            },
            projectName() {
                return this.report.projectName;
            },
            weekNumber() {
                return this.report.weekNumber;
            },
            projectTrafficLight() {
                return this.editable ? this.value.projectTrafficLight : this.report.projectTrafficLight;
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
                        name: 'label.executing',
                        value: this.snapshot.tasks.total.status.executing,
                        color: '#465079',
                    }, {
                        name: 'label.closed',
                        value: this.snapshot.tasks.total.status.closed,
                        color: '#232D4B',
                    },
                ];
            },
            tasksTrafficLightSeries() {
                let data = [];

                if (this.snapshot.tasks.total.trafficLight.green > 0) {
                    data.push({
                        name: 'color_status.finished',
                        value: this.snapshot.tasks.total.trafficLight.green,
                        color: colors.trafficLight.green,
                    });
                }

                if (this.snapshot.tasks.total.trafficLight.yellow > 0) {
                    data.push({
                        name: 'color_status.in_progress',
                        value: this.snapshot.tasks.total.trafficLight.yellow,
                        color: colors.trafficLight.yellow,
                    });
                }

                if (this.snapshot.tasks.total.trafficLight.red > 0) {
                    data.push({
                        name: 'color_status.not_started',
                        value: this.snapshot.tasks.total.trafficLight.red,
                        color: colors.trafficLight.red,
                    });
                }

                return data;
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
                    tasks: Math.round(this.snapshot.tasks.progress),
                    costs: Math.round(this.snapshot.costs.progress),
                };
            },
            projectPlannedProgress() {
                return this.snapshot.plannedProgress;
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
            opportunitiesGrid() {
                return {
                    top: this.snapshot.opportunities.topItem,
                    items: this.snapshot.opportunities.items,
                    summary: {
                        potentialCost: this.snapshot.opportunities.total.potentialCost,
                        potentialTime: this.snapshot.opportunities.total.potentialTime,
                        measuresCount: this.snapshot.opportunities.total.measuresCount,
                        measuresCost: this.snapshot.opportunities.total.measuresCost,
                    },
                };
            },
            risksGrid() {
                return {
                    top: this.snapshot.risks.topItem,
                    items: this.snapshot.risks.items,
                    summary: {
                        potentialCost: this.snapshot.risks.total.potentialCost,
                        potentialDelay: this.snapshot.risks.total.potentialDelay,
                        measuresCount: this.snapshot.risks.total.measuresCount,
                        measuresCost: this.snapshot.risks.total.measuresCost,
                    },
                };
            },
            todosItems() {
                return this.snapshot.todos
                    ? this.snapshot.todos.items
                    : [];
            },
            decisionsItems() {
                return this.snapshot.decisions
                    ? this.snapshot.decisions.items
                    : [];
            },
            infosItems() {
                return this.snapshot.infos
                    ? this.snapshot.infos.items
                    : [];
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
                    return data.label;
                });
            },
            phases() {
                if (!this.snapshot.phases) {
                    return [];
                }

                return this.snapshot.phases.items;
            },
            milestones() {
                if (!this.snapshot.milestones || !this.snapshot.milestones.items) {
                    return [];
                }

                return this.snapshot.milestones.items.filter((milestone) => {
                    return milestone.isKeyMilestone;
                });
            },
            currency() {
                return this.snapshot.currency.symbol;
            },
            forcedLocale() {
                return this.locale ? this.locale : '';
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
    @import '../../../../../../frontend/src/css/_variables';
    @import '../../../../../../frontend/src/css/_mixins';

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
        margin-top: 30px;
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
        background-color: #fff;
        padding: 0;
        height: 360px;

        h3, h4 {
            margin: 0 0 10px;
            text-align: center;
        }
    }

    hr.double {
        margin: 0;
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
        @media print {
            padding-top: 20px;
        }
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

    .no-results {
        text-align: center;
        color: $middleColor;
        min-height: 80%;
        line-height: 20em;
    }
</style>
