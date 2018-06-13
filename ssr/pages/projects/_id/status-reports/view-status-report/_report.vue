<template>
    <div class="project-status-report page-section">
        <div class="row" v-if="currentStatusReport.information">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="header">
                    <h1>
                        {{ currentStatusReport.information.project.name }}
                        <span>{{ translateText('message.week') }} {{ currentStatusReport.information.week }}</span>
                    </h1>
                </div>

                <div class="hero-text">
                    {{ translateText('message.status_report') }}
                </div>

                <!--<div class="row large-half-columns">-->
                    <!--<div class="col-md-6">-->
                        <!--<div class="widget same-height" v-resize="onResizeSameHeightDiv">-->
                            <!--<h3>{{ translateText('message.overall_status') }}</h3>-->
                            <!--<div class="flex flex-center">-->
                                <!--<div class="status-boxes big-status-boxes flex flex-v-center">-->
                                    <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                                    <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                                    <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                                <!--</div>-->
                            <!--</div>-->

                            <!--<h4>{{ translateText('message.tasks_status') }}</h4>-->
                            <!--<div class="status-bar clearfix flex">-->
                                <!--&lt;!&ndash; bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) &ndash;&gt;-->
                                <!--<div class="bar middle-bg flex-v-center" v-bind:style="{width: (currentStatusReport.information.projectTasksStatus['label.open'] * 100) / (currentStatusReport.information.projectTasksStatus['label.open'] + currentStatusReport.information.projectTasksStatus['label.closed']) + '%'}">-->
                                    <!--{{ translateText('label.open') }}: {{ currentStatusReport.information.projectTasksStatus['label.open'] }}-->
                                <!--</div>-->
                                <!--<div class="bar main-bg flex-v-center" v-bind:style="{width: (currentStatusReport.information.projectTasksStatus['label.closed'] * 100) / (currentStatusReport.information.projectTasksStatus['label.open'] + currentStatusReport.information.projectTasksStatus['label.closed']) + '%'}">-->
                                    <!--{{ translateText('label.closed') }}: {{ currentStatusReport.information.projectTasksStatus['label.closed'] }}-->
                                <!--</div>-->
                            <!--</div>-->

                            <!--<h4>{{ translateText('message.tasks_condition') }}</h4>-->
                            <!--<div class="status-bar clearfix flex">-->
                                <!--&lt;!&ndash; bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) &ndash;&gt;-->
                                <!--<div-->
                                    <!--v-for="(condition, key) in currentStatusReport.information.projectTasksStatus.conditions"-->
                                    <!--class="bar flex-v-center"-->
                                    <!--:key="`condition-${key}`"-->
                                    <!--v-bind:style="{width: (condition.count * 100) / (currentStatusReport.information.projectTasksStatus.conditions.total) + '%', background: condition.color}"-->
                                <!--&gt;-->
                                    <!--{{ condition.count }}-->
                                <!--</div>-->
                            <!--</div>-->

                            <!--<div class="checkbox-input clearfix">-->
                                <!--<input v-model="actionNeeded" id="action_needed" disabled type="checkbox" name="" value="">-->
                                <!--<label class="no-margin-bottom" for="action_needed">{{ translateText('message.action_needed') }}</label>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="col-md-6">-->
                        <!--<div class="widget same-height" v-resize="onResizeSameHeightDiv">-->
                            <!--<h3>{{ translateText('message.project_trend') }}</h3>-->
                            <!--<h4>{{ translateText('message.current_date') }}: {{ today | moment('DD.MM.YYYY') }}</h4>-->
                            <!--<no-ssr>-->
                                <!--<vue-chart-->
                                    <!--:columns="trendColumns"-->
                                    <!--:rows="trendRows"-->
                                    <!--:options="trendOptions">-->
                                <!--</vue-chart>-->
                            <!--</no-ssr>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<div class="form">-->
                            <!--&lt;!&ndash; /// Project Status Comment /// &ndash;&gt;-->
                            <!--<div class="form-group last-form-group">-->
                                <!--<br />-->
                                <!--<div v-html="currentStatusReport.information.comment"></div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash; /// End Project Staus Comment /// &ndash;&gt;-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.schedule') }}</h3>-->
                    <!--</div>-->
                    <!--<div class="col-md-8">-->
                        <!--&lt;!&ndash;<vue-scrollbar class="table-wrapper">&ndash;&gt;-->
                            <!--<table class="table table-small">-->
                                <!--<thead>-->
                                <!--<tr>-->
                                    <!--<th>{{ translateText('table_header_cell.schedule') }}</th>-->
                                    <!--<th>{{ translateText('table_header_cell.start') }}</th>-->
                                    <!--<th>{{ translateText('table_header_cell.finish') }}</th>-->
                                    <!--<th>{{ translateText('table_header_cell.duration') }}</th>-->
                                <!--</tr>-->
                                <!--</thead>-->
                                <!--<tbody>-->
                                <!--<tr>-->
                                    <!--<td>{{ translateText('table_header_cell.base') }}</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt | moment('DD.MM.YYYY') }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.base_finish && currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt | moment('DD.MM.YYYY')  }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td  v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_finish">-->
                                        <!--{{ getDuration(currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt, currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt, 'days') }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                <!--</tr>-->
                                <!--<tr :class="forecastColorClass">-->
                                    <!--<td>{{ translateText('table_header_cell.forecast') }}</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt | moment('DD.MM.YYYY')  }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.forecast_finish && currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt | moment('DD.MM.YYYY')  }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td  v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_finish">-->
                                        <!--{{ getDuration(currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt, currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt) }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                <!--</tr>-->
                                <!--<tr :class="actualColorClass">-->
                                    <!--<td>{{ translateText('table_header_cell.actual') }}</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt | moment('DD.MM.YYYY')  }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td v-if="currentStatusReport.information.tasksForSchedule.actual_finish && currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt">-->
                                        <!--{{ currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt | moment('DD.MM.YYYY')  }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                    <!--<td  v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_finish">-->
                                        <!--{{ getDuration(currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt, currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt) }}-->
                                    <!--</td>-->
                                    <!--<td v-else>-</td>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                            <!--</table>-->
                        <!--&lt;!&ndash;</vue-scrollbar>&ndash;&gt;-->
                    <!--</div>-->
                    <!--<div class="col-md-4">-->
                        <!--<div class="range-slider-legend">-->
                            <!--<div class="legend-item">-->
                                <!--<span>{{ translateText('message.base_schedule') }}</span>-->
                                <!--<div class="legend-bar darker-bg"></div>-->
                            <!--</div>-->
                            <!--<div class="legend-item">-->
                                <!--<span>{{ translateText('message.forecast_schedule') }}</span>-->
                                <!--<div class="legend-bar middle-bg"></div>-->
                            <!--</div>-->
                            <!--<div class="legend-item">-->
                                <!--<span>{{ translateText('message.actual_schedule') }}</span>-->
                                <!--<div class="legend-bar second-bg"></div>-->
                            <!--</div>-->
                            <!--<div class="legend-item">-->
                                <!--<span>{{ translateText('message.warning') }}</span>-->
                                <!--<div class="legend-bar warning-bg"></div>-->
                            <!--</div>-->
                            <!--<div class="legend-item">-->
                                <!--<span>{{ translateText('message.danger') }}</span>-->
                                <!--<div class="legend-bar danger-bg"></div>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <div class="row">
                    <div class="col-md-12">
                        <div class="task-range-slider big-range-slider">
                            <!--TODO: determine the values for min and max for the bars-->
                            <!--<no-ssr>-->
                                <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.base_start && currentStatusReport.information.tasksForSchedule.base_finish"-->
                                    <!--class="base dark-range-slider"-->
                                    <!--id="scheduleBase"-->
                                    <!--:message="translateText('table_header_cell.base')"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--v-bind:from="currentStatusReport.information.tasksForSchedule.base_start.scheduledStartAt"-->
                                    <!--v-bind:to="currentStatusReport.information.tasksForSchedule.base_finish.scheduledFinishAt"-->
                                    <!--type="double" />-->
                                <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.forecast_start && currentStatusReport.information.tasksForSchedule.forecast_finish"-->
                                    <!--class="forecast warning"-->
                                    <!--id="translateText('table_header_cell.forecast')"-->
                                    <!--message="Forecast"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--v-bind:from="currentStatusReport.information.tasksForSchedule.forecast_start.forecastStartAt"-->
                                    <!--v-bind:to="currentStatusReport.information.tasksForSchedule.forecast_finish.forecastFinishAt "-->
                                    <!--type="double" />-->
                                <!--<task-range-slider v-if="currentStatusReport.information.tasksForSchedule.actual_start && currentStatusReport.information.tasksForSchedule.actual_finish"-->
                                    <!--class="actual"-->
                                    <!--id="translateText('table_header_cell.actual')"-->
                                    <!--message="Actual"-->
                                    <!--min="2017-01-01"-->
                                    <!--max="2018-01-01"-->
                                    <!--v-bind:from="currentStatusReport.information.tasksForSchedule.actual_start.actualStartAt"-->
                                    <!--v-bind:to="currentStatusReport.information.tasksForSchedule.actual_finish.actualFinishAt"-->
                                    <!--type="double" />-->
                            <!--</no-ssr>-->
                            <p>range sliders here</p>
                        </div>
                    </div>
                </div>

                <!--<hr class="double">-->

                <div class="row statuses">
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.project_progress">
                            <no-ssr>
                                <circle-chart
                                    :percentage="currentStatusReport.information.progresses.project_progress.value"
                                    v-bind:title="translateText('message.overall_progress')"
                                    class="left dark-chart medium-chart"
                                    v-bind:class="currentStatusReport.information.progresses.project_progress.class" />
                            </no-ssr>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.task_progress">
                            <no-ssr>
                                <circle-chart
                                    v-bind:percentage="currentStatusReport.information.progresses.task_progress.value"
                                    v-bind:title="translateText('message.task_progress')"
                                    class="left dark-chart medium-chart"
                                    v-bind:class="currentStatusReport.information.progresses.task_progress.class" />
                            </no-ssr>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status" v-if="currentStatusReport.information.progresses.cost_progress">
                            <no-ssr>
                                <circle-chart
                                    :percentage="currentStatusReport.information.progresses.cost_progress.value"
                                    v-bind:title="translateText('message.costs_progress')"
                                    class="left dark-chart medium-chart"
                                    v-bind:class="currentStatusReport.information.progresses.cost_progress.class" />
                            </no-ssr>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.phases_and_milestones') }}</h3>-->
                        <!--<div class="status-boxes flex flex-v-center marginbottom20">-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.project.overallStatus === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                        <!--</div>-->

                        <!--<no-ssr>-->
                            <!--<vis-timeline :pmData="pmData" />-->
                        <!--</no-ssr>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.internal_costs') }}</h3>-->
                        <!--<div class="status-boxes flex flex-v-center marginbottom20">-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.costData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.costData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.costData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                        <!--</div>-->

                        <!--<vue-chart-->
                                <!--chart-type="ColumnChart"-->
                                <!--:columns="columns"-->
                                <!--:rows="costRowsByPhase"-->
                                <!--:options="options">-->
                        <!--</vue-chart>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.external_costs') }}</h3>-->
                        <!--<div class="status-boxes flex flex-v-center marginbottom20">-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 2 ? '#5FC3A5' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 1 ? '#ccba54' : '', cursor: 'default'}"></div>-->
                            <!--<div class="status-box" v-bind:style="{background: currentStatusReport.information.resourceData.byPhaseTraffic === 0 ? '#c87369' : '', cursor: 'default'}"></div>-->
                        <!--</div>-->

                        <!--<vue-chart-->
                                <!--chart-type="ColumnChart"-->
                                <!--:columns="columns"-->
                                <!--:rows="resourceRowsByPhase"-->
                                <!--:options="options">-->
                        <!--</vue-chart>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row ro-columns large-half-columns">-->
                    <!--<div class="col-md-6 dark-border-right">-->
                        <!--<h3 class="marginbottom20 margintop0">{{ translateText('message.opportunities') }}</h3>-->
                        <!--<div class="ro-grid-wrapper clearfix">-->
                            <!--<no-ssr>-->
                                <!--<risk-grid :gridData="opportunityGridData" :isRisk="false" :clickable="false" />-->
                            <!--</no-ssr>-->
                            <!--<h4>{{ translateText('message.top_opportunity') }}:</h4>-->
                            <!--<div class="ro-main ro-main-opportunity" v-if="currentStatusReport.information.risksOpportunitiesStats.opportunities && currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity">-->
                                <!--<b>{{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.title }}</b>-->
                                <!--<span class="ro-main-stats">|-->
                                    <!--<b v-bind:class="getPriorityNameColor(currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.priority).color">-->
                                        <!--{{ translateText('message.priority') }}: {{ getPriorityNameColor(currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.priority).name }}-->
                                    <!--</b>|-->
                                    <!--{{ translateText('message.potential_savings') }}: {{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.costSavings }}{{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.currency }} |-->
                                    <!--{{ translateText('message.potential_time_savings') }}: {{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.timeSavings }} {{ translateText(currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.timeUnit) }} |-->
                                    <!--{{ translateText('message.strategy') }}: {{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.opportunityStrategyName }} |-->
                                    <!--{{ translateText('message.status') }}: {{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.opportunityStatusName }}-->
                                <!--</span>-->
                                <!--<div class="entry-responsible flex flex-v-center">-->
                                    <!--<div class="user-avatar">-->
                                        <!--<img :src="currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.responsibilityAvatar" :alt="currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.responsibilityFullName"/>-->
                                    <!--</div>-->
                                    <!--<div>-->
                                        <!--{{ translateText('message.responsible') }}:-->
                                        <!--<b>{{ currentStatusReport.information.risksOpportunitiesStats.opportunities.top_opportunity.responsibilityFullName }}</b>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<opportunity-summary :summaryData="currentStatusReport.information.risksOpportunitiesStats.opportunities"></opportunity-summary>-->
                        <!--</div>-->
                    <!--</div>-->

                    <!--<div class="col-md-6">-->
                        <!--<h3 class="marginbottom20 margintop0">{{ translateText('message.risks') }}</h3>-->
                        <!--<div class="ro-grid-wrapper clearfix">-->
                            <!--<risk-grid :gridData="riskGridData" :isRisk="true" :clickable="false"></risk-grid>-->
                            <!--<h4>{{ translateText('message.top_risk') }}:</h4>-->
                            <!--<div class="ro-main ro-main-risk" v-if="currentStatusReport.information.risksOpportunitiesStats.risks && currentStatusReport.information.risksOpportunitiesStats.risks.top_risk">-->
                                <!--<b>{{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.title }}</b>-->
                                <!--<span class="ro-main-stats">|-->
                                    <!--<b v-bind:class="getPriorityNameColor(currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.priority).color">-->
                                        <!--{{ translateText('message.priority') }}: {{ getPriorityNameColor(currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.priority).name }}-->
                                    <!--</b>|-->
                                    <!--{{ translateText('message.potential_savings') }}: {{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.cost }}{{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.currency }} |-->
                                    <!--{{ translateText('message.potential_time_savings') }}: {{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.delay }} {{ translateText(currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.delayUnit) }} |-->
                                    <!--{{ translateText('message.strategy') }}: {{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.riskStrategyName }} |-->
                                    <!--{{ translateText('message.status') }}: {{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.riskStatusName }}-->
                                <!--</span>-->
                                <!--<div class="entry-responsible flex flex-v-center">-->
                                    <!--<div class="user-avatar">-->
                                        <!--<img :src="currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.responsibilityAvatar" :alt="currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.responsibilityFullName"/>-->
                                    <!--</div>-->
                                    <!--<div>-->
                                        <!--{{ translateText('message.responsible') }}:-->
                                        <!--<b>{{ currentStatusReport.information.risksOpportunitiesStats.risks.top_risk.responsibilityFullName }}</b>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<risk-summary :summaryData="currentStatusReport.information.risksOpportunitiesStats.risks"></risk-summary>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.todos') }}</h3>-->
                        <!--<table class="table table-striped table-responsive table-fixed table-small" v-if="currentStatusReport.information.todos.items && currentStatusReport.information.todos.items.length > 0">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th style="width:11%">{{ translateText('table_header_cell.status') }}</th>-->
                                <!--<th style="width:14%">{{ translateText('table_header_cell.due_date') }}</th>-->
                                <!--<th style="width:25%">{{ translateText('table_header_cell.topic') }}</th>-->
                                <!--<th style="width:36%">{{ translateText('table_header_cell.description') }}</th>-->
                                <!--<th style="width:14%">{{ translateText('table_header_cell.responsible') }}</th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr-->
                                <!--v-for="todo in currentStatusReport.information.todos.items"-->
                                <!--:key="`todo-${todo.id}`"-->
                            <!--&gt;-->
                                <!--<td>{{ translateText(todo.statusName) }}</td>-->
                                <!--<td>{{ todo.dueDate | moment('DD.MM.YYYY') }}</td>-->
                                <!--<td class="cell-wrap">{{ todo.title }}</td>-->
                                <!--<td class="cell-wrap">{{ todo.description }}</td>-->
                                <!--<td>-->
                                    <!--<div class="avatar" v-tooltip.top-center="todo.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                        <!--<span v-else>{{ translateText('label.no_data') }}</span>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<h3 class="margintop0">{{ translateText('message.decisions') }}</h3>-->
                        <!--<table class="table table-striped table-responsive table-fixed table-small" v-if="currentStatusReport.information.decisions.items && currentStatusReport.information.decisions.items.length > 0">-->
                            <!--<thead>-->
                            <!--<tr>-->
                                <!--<th style="width:14%">{{ translateText('table_header_cell.due_date') }}</th>-->
                                <!--<th style="width:25%">{{ translateText('table_header_cell.topic') }}</th>-->
                                <!--<th style="width:36%">{{ translateText('table_header_cell.description') }}</th>-->
                                <!--<th style="width:14%">{{ translateText('table_header_cell.responsible') }}</th>-->
                            <!--</tr>-->
                            <!--</thead>-->
                            <!--<tbody>-->
                            <!--<tr-->
                                <!--v-for="decision in currentStatusReport.information.decisions.items"-->
                                <!--:key="`decision-${decision.id}`"-->
                            <!--&gt;-->
                                <!--<td>{{ decision.dueDate | moment('DD.MM.YYYY') }}</td>-->
                                <!--<td class="cell-wrap">{{ decision.title }}</td>-->
                                <!--<td class="cell-wrap cell-large">{{ decision.description }}</td>-->
                                <!--<td>-->
                                    <!--<div class="avatar" v-tooltip.top-center="decision.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + decision.responsibilityAvatar + ')' }"></div>-->
                                <!--</td>-->
                            <!--</tr>-->
                            <!--</tbody>-->
                        <!--</table>-->
                        <!--<span v-else>{{ translateText('label.no_data') }}</span>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<hr class="double">-->

                <!--<div class="row">-->
                    <!--<div class="col-md-12">-->
                        <!--<div class="flex flex-space-between">-->
                            <!--<a :href="downloadPdf" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.download_pdf') }} <download-icon fill="white-fill"></download-icon></a>-->
                            <!--<a @click="showEmailModal = true" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.email_status_report') }} <at-icon fill="white-fill"></at-icon></a>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import moment from 'moment';

// /var/www/campr/frontend/src/components/Projects/Risks/RiskSummary.vue
// import $ from 'jquery';
// import 'jquery-match-height/jquery.matchHeight.js';
import CircleChart from '../../../../../components/_charts/CircleChart';
// import RiskSummary from '../../../../../../frontend/src/components/Projects/Risks/RiskSummary.vue';
// import resize from 'vue-resize-directive';
// import VisTimeline from '../../../../../components/_phases-and-milestones-components/VisTimeline';
// import TaskRangeSlider from '../../../../../components/_task-components/TaskRangeSlider';

/*
import {mapGetters, mapActions} from 'vuex';
import VisTimeline from '../../_common/_phases-and-milestones-components/VisTimeline';
import Vue from 'vue';
import moment from 'moment';
import 'jquery-match-height/jquery.matchHeight.js';
import VueScrollbar from 'vue2-scrollbar';
import TaskRangeSlider from '../../_common/_task-components/TaskRangeSlider';
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
*/

export default {
    validate({params}) {
        return /^\d+$/.test(params.id);
    },
    components: {
        CircleChart,
        // RiskSummary,
        // TaskRangeSlider,
        // VisTimeline,
    },
    directives: {
        // resize,
    },
    methods: {
        // ...mapActions(['getStatusReport', 'emailStatusReport']),
        getDuration: function(startDate, endDate, unit) {
            let end = endDate ? moment(endDate) : moment();
            let start = moment(startDate);
            let diff = end.diff(start, unit);

            return !isNaN(diff) ? diff : '-';
        },
        onResizeSameHeightDiv: function() {
            // $('.same-height').matchHeight();
        },
        translateText: function(text) {
            return this.translate(text);
        },
        getPriorityNameColor: function(value) {
            const priorityNames = [
                {name: 'message.very_low', color: 'ro-very-low-priority'},
                {name: 'message.low', color: 'ro-low-priority'},
                {name: 'message.medium', color: 'ro-medium-priority'},
                {name: 'message.high', color: 'ro-high-priority'},
                {name: 'message.very_high', color: 'ro-very-high-priority'},
            ];

            return {
                name: this.translateText(priorityNames[value].name),
                color: priorityNames[value].color,
            };
        },

        transformCurrentStatusReport(value) {
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
                let items = [];
                if (information.projectMilestones && information.projectMilestones.items) {
                    items = items.concat(information.projectMilestones.items.map((item) => {
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
                this.pmData = items;
                Object.entries(information.costData.byPhase).map(([key, value]) => {
                    this.costRowsByPhase.push([
                        key,
                        value.base ? parseInt(value.base) : 0,
                        value.actual ? parseInt(value.actual) : 0,
                        value.forecast ? parseInt(value.forecast) : 0,
                        value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                    ]);
                });
                Object.entries(information.resourceData.byPhase).map(([key, value]) => {
                    this.resourceRowsByPhase.push([
                        key,
                        value.base ? parseInt(value.base) : 0,
                        value.actual ? parseInt(value.actual) : 0,
                        value.forecast ? parseInt(value.forecast) : 0,
                        value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                    ]);
                });
                let opportunityGridValues = information.risksOpportunitiesStats.opportunities.opportunity_data.gridValues;
                let riskGridValues = information.risksOpportunitiesStats.risks.risk_data.gridValues;
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
                this.trendRows = information.projectTrend ? information.projectTrend : [];
            }
        }
    },
    async asyncData({params, query}) {
        let res = await Vue.doFetch(`http://${query.host}/api/status-reports/${params.report}`, query.key);
        const currentStatusReport = await res.json();

        return {
            params: JSON.stringify(params),
            query: JSON.stringify(query),

            currentStatusReport,

            actionNeeded: null,
            today: new Date(),
            comment: null,
            forecastColorClass: null,
            actualColorClass: null,
            pmData: null,
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
    }
}
</script>

<!--@import '../../../../../../frontend/src/css/page-section';-->

<style scoped lang="scss">
    @import '../../../../../../frontend/src/css/_common';
    @import '../../../../../../frontend/src/css/_variables';
    @import '../../../../../../frontend/src/css/_mixins';

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
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;

        img {
            width: 30px;
            height: 30px;
            @include border-radius(50%);
            margin: 0 10px 0 0;
            display: inline-block;
            position: relative;
            top: -2px;
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
