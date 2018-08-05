<template>
    <div>
        <div class="header">
            <h1>
                {{ report.projectName }}
                <span>{{ translate('message.week') }} {{ report.weekNumber }}</span>
            </h1>
        </div>

        <div class="hero-text">
            {{ translate('message.status_report') }}
        </div>

        <table width="100%">
            <tbody>
            <tr>
                <td width="50%">
                    <div class="widget">
                        <h3>{{ translate('message.overall_status') }}</h3>
                        <div class="flex flex-center" style="text-align: center">
                            <traffic-light
                                    :value="report.projectTrafficLight"
                                    size="small"
                                    :editable="false"/>
                        </div>

                        <h4>{{ translate('message.tasks_status') }}</h4>
                        <progress-bar-chart :series="tasksStatusSeries"/>
                        <br/>

                        <h4>{{ translate('message.tasks_condition') }}</h4>
                        <progress-bar-chart
                                :series="tasksTrafficLightSeries"
                                :options="{labels: {enabled: false}}"/>

                        <div class="checkbox-input clearfix">
                            <input
                                    :value="report.projectActionNeeded"
                                    type="checkbox"
                                    :disabled="true"/>
                            <label class="no-margin-bottom">{{ translate('message.action_needed') }}</label>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="widget">
                        <h3>{{ translate('message.project_trend') }}</h3>
                        <h4>{{ translate('message.current_date') }}: {{ report.createdAt | date }}</h4>

                        <status-report-trend-chart
                                v-if="trendChartData.length > 0"
                                :data="trendChartData"
                                :labels="trendChartLabels"
                                :point-color="trendChartPointColor"
                                :options="{responsive: false}"
                                :width="420"/>
                        <div class="trend-no-results" v-else>{{ translate('message.not_enough_data') }}</div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row" v-if="report.comment">
            <div class="col-md-12">
                <div class="form">
                    <div class="form-group last-form-group">
                        <br/>
                        <h4>{{ translate('message.comment') }}</h4>
                        <div class="vueditor-holder">
                            <div class="vueditor campr-editor">
                                <br>
                                <div class="ve-design" v-html="report.comment"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="double">

        <template v-if="schedule">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="margintop0">{{ translate('message.schedule') }}</h3>
                    <br/>
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
            </div>

            <hr class="double">
        </template>

        <div class="row statuses min-status" v-if="progress">
            <div class="col-md-4" style="width: 33%; display: inline-block;">
                <div class="status">
                    <circle-chart
                            :bgStrokeColor="options.backgroundColor"
                            :stroke-width="8"
                            :percentage="projectScheduledProgress"
                            :animation-duration="0"
                            :title="translate('message.planned_progress')"
                            class="left center-content"/>
                </div>
            </div>
            <div class="col-md-4" style="width: 33%; display: inline-block;">
                <div class="status">
                    <circle-chart
                            :bgStrokeColor="options.backgroundColor"
                            :stroke-width="8"
                            :animation-duration="0"
                            :percentage="progress.tasks"
                            :title="translate('message.task_status')"
                            class="left center-content"/>
                </div>
            </div>
            <div class="col-md-4" style="width: 33%; display: inline-block;">
                <div class="status">
                    <circle-chart
                            :bgStrokeColor="options.backgroundColor"
                            :stroke-width="8"
                            :animation-duration="0"
                            :percentage="progress.costs"
                            :title="translate('message.cost_status')"
                            class="left center-content"/>
                </div>
            </div>
        </div>

        <hr class="double">

        <template v-if="phases || milestones">
            <div class="page-break-before">
                <h3 class="margintop0">{{ translate('message.phases_and_milestones') }}</h3>
                <div class="flex flex-center" style="text-align: center">
                    <traffic-light :value="projectTrafficLight"/>
                </div>

                <br/>

                <status-report-timeline
                        style="width: 90%;"
                        :phases="phases"
                        :milestones="milestones"/>

            </div>
        </template>

        <div v-if="internalCostsGraphData">
            <h3 class="margintop0">{{ translate('message.internal_costs') }}</h3>
            <div class="marginbottom20" style="text-align: center">
                <traffic-light :value="internalCostsTrafficLight"/>
            </div>

            <chart
                    theme="print"
                    :data="internalCostsGraphData"
                    style="width: 90%;"/>
        </div>

        <hr class="double">

        <div v-if="externalCostsGraphData">
            <h3 class="margintop0">{{ translate('message.external_costs') }}</h3>
            <div class="marginbottom20" style="text-align: center">
                <traffic-light :value="externalCostsTrafficLight"/>
            </div>

            <chart
                    theme="print"
                    :data="externalCostsGraphData"
                    style="width: 90%;"/>
        </div>

        <table width="100%" class="page-break-before">
            <tbody>
            <tr>
                <td width="50%" style="padding-right: 10px;">
                    <opportunities-grid :value="opportunitiesGrid" :currency="currency"/>
                </td>
                <td>
                    <risks-grid :value="risksGrid" :currency="currency"/>
                </td>
            </tr>
            </tbody>
        </table>


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

        <hr class="double">

        <div class="row" v-if="infosItems.length > 0">
            <div class="col-md-12">
                <h3 class="margintop0">{{ translate('message.infos') }}</h3>
                <status-report-infos :items="infosItems"/>
            </div>
        </div>
    </div>
</template>

<script>
    import 'jquery-match-height/jquery.matchHeight.js';
    import StatusReport from './StatusReport';

    export default {
        name: 'status-report-print',
        extends: StatusReport,
    };
</script>
