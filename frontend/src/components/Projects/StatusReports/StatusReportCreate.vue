<template>
    <div class="project-status-report page-section">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="header">
                    <h1>
                        Tesla - SpaceX Mars Project
                        <span>week 12</span>
                    </h1>
                </div>

                <div class="hero-text">
                    {{ translateText('message.status_report') }}
                </div>

                <div class="row large-half-columns">
                    <div class="col-md-6">
                        <div class="widget same-height">
                            <h3>{{ translateText('message.overall_status') }}</h3>
                            <div class="flex flex-center">
                                <div class="status-boxes big-status-boxes flex flex-v-center">
                                    <div class="status-box" style="background-color:#5FC3A5"></div>
                                    <div class="status-box" style=""></div>
                                    <div class="status-box" style=""></div>
                                </div>
                            </div>

                            <h4>{{ translateText('message.tasks_status') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar middle-bg flex-v-center" data-number="245" style="width:66.40%;">Open: 245</div>
                                <div class="bar main-bg flex-v-center" data-number="124" style="width:33.60%;">Closed: 124</div>
                            </div>

                            <h4>{{ translateText('message.tasks_condition') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar second-bg flex-v-center" data-number="285" style="width:77.23%;">285</div>
                                <div class="bar warning-bg flex-v-center" data-number="55" style="width:14.90%;">55</div>
                                <div class="bar danger-bg flex-v-center" data-number="29" style="width:7.87%;">29</div>
                            </div>

                            <div class="checkbox-input clearfix">
                                <input id="action_needed" type="checkbox" name="" value="">
                                <label class="no-margin-bottom" for="action_needed">{{ translateText('message.action_needed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="widget same-height">
                            <h3>{{ translateText('message.project_trend') }}</h3>
                            <h4>{{ translateText('message.current_date') }}: 12.08.2017</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <!-- /// Project Status Comment /// -->
                            <div class="form-group last-form-group">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('placeholder.comment') }}</div>
                                    <Vueditor ref="content" />
                                </div>
                            </div>
                            <!-- /// End Project Staus Comment /// -->
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.schedule') }}</h3>
                    </div>
                    <div class="col-md-8">
                        <vue-scrollbar class="table-wrapper">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('table_header_cell.schedule') }}</th>
                                        <th>{{ translateText('table_header_cell.start') }}</th>
                                        <th>{{ translateText('table_header_cell.finish') }}</th>
                                        <th>{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Base</td>
                                        <td>10.02.2016</td>
                                        <td>30.05.2018</td>
                                        <td>841</td>
                                    </tr>
                                    <tr>
                                        <td>Forecast</td>
                                        <td>01.04.2016</td>
                                        <td class="cell-warning">30.06.2018</td>
                                        <td>721</td>
                                    </tr>
                                    <tr>
                                        <td>Actual</td>
                                        <td>01.04.2016</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </vue-scrollbar>
                    </div>
                    <div class="col-md-4">
                        <div class="range-slider-legend">
                            <div class="legend-item">
                                <span>{{ translateText('message.base_schedule') }}</span>
                                <div class="legend-bar darker-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translateText('message.forecast_schedule') }}</span>
                                <div class="legend-bar middle-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translateText('message.actual_schedule') }}</span>
                                <div class="legend-bar second-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translateText('message.warning') }}</span>
                                <div class="legend-bar warning-bg"></div>
                            </div>
                            <div class="legend-item">
                                <span>{{ translateText('message.danger') }}</span>
                                <div class="legend-bar danger-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="task-range-slider big-range-slider">
                            <task-range-slider class="base dark-range-slider" id="scheduleBase" message="Base" min="2016-02-10" max="2018-09-20" v-bind:from="'2016-02-10'" v-bind:to="'2018-05-30'" type="double"></task-range-slider>
                            <task-range-slider class="forecast warning" id="scheduleForecast" message="Forecast" min="2016-02-10" max="2018-09-20" v-bind:from="'2016-04-01'" v-bind:to="'2018-09-20'" type="double"></task-range-slider>
                            <task-range-slider class="actual" id="scheduleActual" message="Actual" min="2016-02-10" max="2018-09-20" v-bind:from="'2016-05-01'" v-bind:to="'2017-07-27'" type="double"></task-range-slider>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row statuses">
                    <div class="col-md-4">
                        <div class="status">
                            <circle-chart :percentage="'42.88'" v-bind:title="translateText('message.overall_progress')" class="left dark-chart medium-chart"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status">
                            <circle-chart :percentage="'84.15'" v-bind:title="translateText('message.task_progress')" class="left warning dark-chart medium-chart"></circle-chart>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="status">
                            <circle-chart :percentage="'60.06'" v-bind:title="translateText('message.costs_progress')" class="left danger dark-chart medium-chart"></circle-chart>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.phases_and_milestones') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" style=""></div>
                            <div class="status-box" style="background-color:#CCBA54"></div>
                            <div class="status-box" style=""></div>
                        </div>

                        <vis-timeline :pmData="pmData"></vis-timeline>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.internal_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" style="background-color:#5fc3a5"></div>
                            <div class="status-box" style=""></div>
                            <div class="status-box" style=""></div>
                        </div>

                        <vue-chart
                            chart-type="ColumnChart"
                            :columns="columns"
                            :rows="rowsByPhase"
                            :options="options">
                        </vue-chart>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.external_costs') }}</h3>
                        <div class="status-boxes flex flex-v-center marginbottom20">
                            <div class="status-box" style="background-color:#5fc3a5"></div>
                            <div class="status-box" style=""></div>
                            <div class="status-box" style=""></div>
                        </div>

                        <vue-chart
                            chart-type="ColumnChart"
                            :columns="columns"
                            :rows="rowsByPhase"
                            :options="options">
                        </vue-chart>
                    </div>
                </div>

                <hr class="double">

                <div class="row ro-columns large-half-columns">
                    <div class="col-md-6 dark-border-right">
                        <h3 class="marginbottom20 margintop0">{{ translateText('message.opportunities') }}</h3>
                        <div class="ro-grid-wrapper clearfix">
                            <risk-grid :gridData="opportunityGridData" :isRisk="false"></risk-grid>
                            <h4>Top Opportunity:</h4>
                            <div class="ro-main ro-main-opportunity">
                                <b>Plant based dietary Program</b> <span class="ro-main-stats">| <b class="ro-main-priority">Priority: Very High</b> | Potential Savings: $4.850 | Potential Time Savings: 14 days | Strategy: Take | Status: Ongoing</span>   
                                <div class="entry-responsible flex flex-v-center">
                                    <div class="user-avatar"> 
                                        <img src="http://trisoft.dev.campr.biz/uploads/avatars/49.jpg" :alt="'Kyle Kennedy'"/>
                                    </div>
                                    <div>
                                        {{ translateText('message.responsible') }}:
                                        <b>Kyle Kennedy</b>
                                    </div>
                                </div>
                            </div>
                            <opportunity-summary :summaryData="risksOpportunitiesStats.opportunities"></opportunity-summary>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h3 class="marginbottom20 margintop0">{{ translateText('message.risks') }}</h3>
                        <div class="ro-grid-wrapper clearfix">
                            <risk-grid :gridData="opportunityGridData" :isRisk="true"></risk-grid> 
                            <h4>Top Risk:</h4>
                            <div class="ro-main ro-main-risk">
                                <b>Unknown viral breach</b> <span class="ro-main-stats">| <b class="ro-main-priority">Priority: Very High</b> | Potential Costs: $120.000 | Potential Time Delays: 90 days | Strategy: Avoid | Status: Initiated</span>  
                                 <div class="entry-responsible flex flex-v-center">
                                    <div class="user-avatar"> 
                                        <img src="http://trisoft.dev.campr.biz/uploads/avatars/49.jpg" :alt="'Kyle Kennedy'"/>
                                    </div>
                                    <div>
                                        {{ translateText('message.responsible') }}:
                                        <b>Kyle Kennedy</b>
                                    </div>
                                </div> 
                            </div>
                            <risk-summary :summaryData="risksOpportunitiesStats.risks"></risk-summary>
                        </div>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.todos') }}</h3>
                        <table class="table table-striped table-responsive table-fixed table-small">
                            <thead>
                                <tr>
                                    <th style="width:11%">{{ translateText('table_header_cell.status') }}</th>
                                    <th style="width:14%">{{ translateText('table_header_cell.due_date') }}</th>
                                    <th style="width:25%">{{ translateText('table_header_cell.topic') }}</th>
                                    <th style="width:36%">{{ translateText('table_header_cell.description') }}</th>
                                    <th style="width:14%">{{ translateText('table_header_cell.responsible') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="todo in todos.items">
                                    <td>{{ todo.statusName }}</td>
                                    <td>{{ todo.dueDate | moment('DD.MM.YYYY') }}</td>
                                    <td class="cell-wrap">{{ todo.title }}</td>
                                    <td class="cell-wrap">{{ todo.description }}</td>
                                    <td>
                                        <div class="avatar" v-tooltip.top-center="todo.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + todo.responsibilityAvatar + ')' }"></div>
                                    </td>                               
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margintop0">{{ translateText('message.decisions') }}</h3>                        
                        <table class="table table-striped table-responsive table-fixed table-small">
                            <thead>
                                <tr>
                                    <th style="width:11%">{{ translateText('table_header_cell.status') }}</th>
                                    <th style="width:14%">{{ translateText('table_header_cell.due_date') }}</th>
                                    <th style="width:25%">{{ translateText('table_header_cell.topic') }}</th>
                                    <th style="width:36%">{{ translateText('table_header_cell.description') }}</th>
                                    <th style="width:14%">{{ translateText('table_header_cell.responsible') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                
                                    <td class="danger-color">Undone</td>
                                    <td>01.08.2017</td>
                                    <td class="cell-wrap">Lorem ipsum dolor sit amet</td>
                                    <td class="cell-wrap cell-large">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam eu velit dolor. Morbi at sagittis sapien. Vivamus molestie arcu et sem condimentum, quis fermentum ante elementum. Proin et nulla ut lorem commodo fringilla vel sit amet ante. Donec facilisis orci quis ante mattis accumsan.</td>       
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'Andrea Sinclair'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                    </td>                               
                                </tr>
                                <tr>                                                
                                    <td class="danger-color">Undone</td>
                                    <td>15.07.2017</td>
                                    <td class="cell-wrap">Donec magna massa, tincidunt sit amet quam nec, pellentesque varius libero.</td>
                                    <td class="cell-wrap cell-large">Sed tempor libero vel urna rutrum dapibus. Aenean id interdum ligula. Cras pulvinar ante consectetur imperdiet accumsan. Nunc vitae magna metus. Vivamus vitae felis maximus, vulputate urna vel, aliquam ex. Nullam condimentum elit vel diam volutpat tempus. Proin efficitur efficitur viverra. Aliquam convallis eros ut porttitor ornare. Donec a est vitae turpis congue bibendum et iaculis nisi. Nullam eget iaculis augue, sit amet interdum diam.</td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                    </td>                               
                                </tr>
                                <tr>
                                    <td class="second-color">Done</td>
                                    <td>01.11.2017</td>
                                    <td class="cell-wrap">Donec id rhoncus justo, ac imperdiet purus. Fusce eleifend tortor vulputate leo interdum, eu malesuada ipsum ac imperdiet purus.</td>
                                    <td class="cell-wrap cell-large">Sed elit velit, luctus ac felis commodo, consequat pulvinar tortor. Nulla auctor interdum arcu et rutrum. Sed elit dui, euismod eu porta ut, pellentesque ut lorem. Maecenas posuere nulla ac nisl iaculis aliquet in ut neque. Aliquam ornare sem libero, eget fringilla quam suscipit sit amet. Duis quis dui sit amet erat fermentum vestibulum. Pellentesque nec nulla quis orci egestas facilisis. Curabitur magna leo, scelerisque ut magna id, placerat malesuada sapien. Maecenas vitae mi imperdiet, fringilla leo eget, cursus mauris. Aenean imperdiet purus eros, ut ullamcorper eros porttitor eget. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent eget sapien non massa eleifend pulvinar ac gravida metus. Aliquam nec nunc ornare, fringilla turpis id, tristique ligula. Mauris faucibus eget quam a malesuada.</td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                    </td>                                
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="double">

                <div class="row">
                    <div class="col-md-12">
                        <div class="flex flex-space-between">
                            <a href="#" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.download_pdf') }} <download-icon fill="white-fill"></download-icon></a>
                            <a href="#" class="btn-rounded btn-auto btn-auto second-bg">{{ translateText('button.email_status_report') }} <at-icon fill="white-fill"></at-icon></a>
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

export default {
    components: {
        VisTimeline,
        VueScrollbar,
        TaskRangeSlider,
        CircleChart,
        RiskGrid,
        RiskList,
        RiskSummary,
        OpportunitySummary,
        OpportunityList,
        DownloadIcon,
        AtIcon,
    },
    created() {
        this.getProjectPhases({
            projectId: this.$route.params.id,
            apiParams: {
                page: 1,
            },
        });
        this.getProjectMilestones({
            projectId: this.$route.params.id,
            apiParams: {
                page: 1,
            },
        });
        this.getProjectMilestones({
            projectId: this.$route.params.id,
        });
        this.getProjectCostsResources({id: this.$route.params.id, type: 1});
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
        this.getProjectTodos({
            projectId: this.$route.params.id,
            queryParams: {
                page: this.activePage,
            },
        });
    },
    mounted() {
        window.$('.same-height').matchHeight();
    },
    methods: {
        ...mapActions([
            'getProjectPhases', 'getProjectMilestones',
            'getProjectCostsResources',
            'getProjectOpportunities', 'getProjectRisks', 'getProjectRiskAndOpportunitiesStats',
            'getProjectTodos',
        ]),
        getDuration: function(startDate, endDate) {
            let end = moment().add(1, 'weeks').endOf('isoWeek');
            let start = moment().add(1, 'weeks').startOf('isoWeek');

            return !isNaN(end.diff(start, 'days')) ? end.diff(start, 'days') : '-';
        },
        translateText: function(text) {
            return this.translate(text);
        },
    },
    computed: {
        ...mapGetters({
            projectPhases: 'projectPhases',
            projectMilestones: 'projectMilestones',
            allProjectMilestones: 'allProjectMilestones',
            allProjectPhases: 'allProjectPhases',
            projectCostsAndResources: 'projectCostsAndResources',
            opportunities: 'opportunities',
            risks: 'risks',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            todos: 'todos',
            todosCount: 'todosCount',
        }),
        pmData: function() {
            let items = [];
            if (this.allProjectPhases && this.allProjectPhases.items) {
                items = items.concat(this.allProjectPhases.items.map((item) => {
                    return {
                        id: item.id,
                        group: 0,
                        content: item.name,
                        start: new Date(item.scheduledStartAt),
                        end: new Date(item.scheduledFinishAt),
                        value: item.workPackageStatus,
                        title: renderTooltip(item),
                    };
                }));
            }

            if (this.allProjectMilestones && this.allProjectMilestones.items) {
                items = items.concat(this.allProjectMilestones.items.map((item) => {
                    return {
                        id: item.id,
                        group: 1,
                        content: item.name,
                        start: new Date(item.scheduledFinishAt),
                        value: item.workPackageStatus,
                        title: renderTooltip(item),
                    };
                }));
            }
            return items;
        },
    },
    watch: {
        projectCostsAndResources(value) {
            Object.entries(this.projectCostsAndResources.byPhase).map(([key, value]) => {
                this.rowsByPhase.push([
                    key,
                    value.base ? parseInt(value.base) : 0,
                    value.actual ? parseInt(value.actual) : 0,
                    value.forecast ? parseInt(value.forecast) : 0,
                    value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                ]);
            });
        },
        risksOpportunitiesStats(value) {
            let opportunityGridValues = this.risksOpportunitiesStats.opportunities.opportunity_data.gridValues;
            let riskGridValues = this.risksOpportunitiesStats.risks.risk_data.gridValues;
            let types = ['medium', 'high', 'low', 'very-low'];
            for (let i = 4; i >= 1; i--) {
                for (let j = 1; j <= 4; j++) {
                    let isActive = i === 1 && j === 1;
                    this.opportunityGridData.push(
                        {probability: j, impact: i, number: opportunityGridValues[j+'-'+i], type: types[j-1], isActive: isActive},
                    );
                    this.riskGridData.push(
                        {probability: j, impact: i, number: riskGridValues[j+'-'+i], type: types[j-1], isActive: isActive},
                    );
                }
            }
        },
    },
    data() {
        return {
            projectId: this.$route.params.id,
            milestoneId: '',
            phaseId: '',
            opportunityGridData: [],
            riskGridData: [],
            activePage: 1,
            todoId: null,
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
            rowsByPhase: [
                ['', 0, 0, 0, 0],
            ],
            rowsByDepartment: [
                ['', 0, 0, 0, 0],
            ],
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
    return `<div>
        <div class="task-box box">
            <div class="box-header">
                <div class="user-info flex flex-v-center">
                    <img class="user-avatar"
                        src="` + item.responsibilityAvatar + `" alt="` + Vue.translate('table_header_cell.responsible') + item.responsibilityFullName +
                    `"/>
                    <p>` + item.responsibilityFullName + `</p>
                </div>
                <h2><router-link to="" class="simple-link">` + item.name + `</router-link></h2>
                <p class="task-id">`+ item.id +`</p>
            </div>
            <div class="content">
                <table class="table table-small">
                    <thead>
                        <tr>
                            <th>` + Vue.translate('table_header_cell.schedule') + `</th>
                            <th>` + Vue.translate('table_header_cell.start') + `</th>
                            <th>` + Vue.translate('table_header_cell.finish') + `</th>
                            <th>` + Vue.translate('table_header_cell.duration') + `</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>`+ Vue.translate('table_header_cell.base') +`</td>
                            <td>` + (item.scheduledStartAt ? item.scheduledStartAt : '-') + `</td>
                            <td>` + (item.scheduledFinishAt ? item.scheduledFinishAt : '-') + `</td>
                            <td>` + (!isNaN(moment(item.scheduledFinishAt).diff(moment(item.scheduledStartAt), 'days'))
                                ? moment(item.scheduledFinishAt).diff(moment(item.scheduledStartAt), 'days')
                                : '-') + `</td>
                        </tr>
                        <tr class="column-warning">
                            <td>`+ Vue.translate('table_header_cell.forecast') +`</td>
                            <td>` + (item.forecastStartAt ? item.forecastStartAt : '-') + `</td>
                            <td>` + (item.forecastFinishedAt ? item.forecastFinishedAt: '-') + `</td>
                            <td>` + (!isNaN(moment(item.forecastFinishedAt).diff(moment(item.forecastStartAt), 'days'))
                                ? moment(item.forecastFinishedAt).diff(moment(item.forecastStartAt), 'days')
                                : '-') + `</td>
                        </tr>
                        <tr>
                            <td>` + Vue.translate('table_header_cell.actual') + `</td>
                            <td>` + (item.actualStartAt ? item.actualStartAt : '-') + `</td>
                            <td>` + (item.actualFinishAt ? item.actualFinishAt : '-') + `</td>
                            <td>` + (!isNaN(moment(item.actualFinishAt).diff(moment(item.actualStartAt), 'days'))
                                ? moment(item.actualFinishAt).diff(moment(item.actualStartAt), 'days')
                                : '-') + `</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="status">
                <p><span>` + Vue.translate('table_header_cell.status') + `:</span> ` + Vue.translate(item.workPackageStatusName) +`</p>
                <bar-chart position="right" :percentage="85" :color="Green" v-bind:title-right="green"></bar-chart>
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

            .chart {
                .text {
                    .title {
                        font-size: 12px;
                    }
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
                    .ro-main-priority {
                        color: $secondColor;
                    }
                }
            }

            &.ro-main-risk {
                .ro-main-stats {
                    .ro-main-priority {
                        color: $dangerColor;
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
