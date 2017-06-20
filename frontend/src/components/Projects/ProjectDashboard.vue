<template>
    <div>
        <div class="page-section">
            <div class="header flex">
                <h1>{{ translateText('message.project_dashboard') }}</h1>
            </div>

            <div class="content widget-grid">
                <!-- /// Project Summary Widget /// -->
                <div class="widget project-summary-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translateText('message.project_summary') }}</h4>
                        <ul class="widget-list">
                            <li>
                                <span>{{ translateText('message.project_name') }}:</span>
                                <b v-if="project.name">{{ project.name }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.project_number') }}:</span>
                                <b v-if="project.number">#{{ project.number }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('label.portfolio') }}:</span>
                                <b v-if="project.portfolioName">{{ project.portfolioName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('label.programme') }}:</span>
                                <b v-if="project.programmeName">{{ project.programmeName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.customer') }}:</span>
                                <b v-if="project.company">{{ project.companyName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.approved_on') }}:</span>
                                <b v-if="project.approvedAt">{{ project.approvedAt }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.project_sponsor') }}:</span>
                                <b v-if="projectSponsors" v-for="(sponsor, index) in projectSponsors">
                                    {{ sponsor.userFullName }}
                                    <span v-if="index != projectSponsors.length - 1">, </span>
                                </b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.project_managers') }}:</span>
                                <b v-if="projectManagers" v-for="(manager, index) in projectManagers">
                                    {{ manager.userFullName }}
                                    <span v-if="index != projectManagers.length - 1">, </span>
                                </b>
                                <b v-else>-</b>
                            </li>
                        </ul>

                        <h4 class="widget-title">{{ translateText('message.project_schedule') }}</h4>
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>{{ translateText('message.schedule') }}</th>
                                    <th>{{ translateText('message.start') }}</th>
                                    <th>{{ translateText('message.finish') }}</th>
                                    <th>{{ translateText('message.duration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ translateText('table_header_cell.base') }}</td>
                                    <td v-if="tasksForSchedule.base_start && tasksForSchedule.base_start.scheduledStartAt">
                                        {{ tasksForSchedule.base_start.scheduledStartAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.base_finish && tasksForSchedule.base_finish.scheduledFinishAt">
                                        {{ tasksForSchedule.base_finish.scheduledFinishAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.base_start && tasksForSchedule.base_finish">
                                        {{ getDuration(tasksForSchedule.base_start.scheduledStartAt, tasksForSchedule.base_finish.scheduledFinishAt) }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                                <tr>
                                    <td>{{ translateText('table_header_cell.forecast') }}</td>
                                    <td v-if="tasksForSchedule.forecast_start && tasksForSchedule.forecast_start.forecastStartAt">
                                        {{ tasksForSchedule.forecast_start.forecastStartAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.forecast_finish && tasksForSchedule.forecast_finish.forecastFinishAt">
                                        {{ tasksForSchedule.forecast_finish.forecastFinishAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.forecast_start && tasksForSchedule.forecast_finish">
                                        {{ getDuration(tasksForSchedule.forecast_start.forecastStartAt, tasksForSchedule.forecast_finish.forecastFinishAt) }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                                <tr>
                                    <td>{{ translateText('table_header_cell.actual') }}</td>
                                    <td v-if="tasksForSchedule.actual_start && tasksForSchedule.actual_start.actualStartAt">
                                        {{ tasksForSchedule.actual_start.actualStartAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td v-if="tasksForSchedule.actual_finish && tasksForSchedule.actual_finish.actualFinishAt">
                                        {{ tasksForSchedule.actual_finish.actualFinishAt }}
                                    </td>
                                    <td v-else>-</td>
                                    <td  v-if="tasksForSchedule.actual_start && tasksForSchedule.actual_finish">
                                        {{ getDuration(tasksForSchedule.actual_start.actualStartAt, tasksForSchedule.actual_finish.actualFinishAt) }}
                                    </td>
                                    <td v-else>-</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex-direction-reverse margintop20">
                            <a href="#path-to-close-project" class="btn-rounded btn-md btn-auto danger-bg">{{ translateText('button.close_project') }}</a>
                        </div>
                        <hr>
                        <h4 class="widget-title">{{ translateText('message.team_members') }} - <b class="second-color" v-if="project.projectUsers">{{ project.projectUsers.length }}</b></h4>
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-md btn-empty btn-auto">View entire team</router-link>
                        <hr>
                        <h4 class="widget-title">{{ translateText('message.project_status') }} - <b style="color:#5FC3A5;">Green</b> <b style="color:#CCBA54;">Yellow</b> <b style="color:#C87369;">Red</b></h4>
                        <div class="status-boxes flex flex-v-center">
                            <div class="status-box" style="background-color:#5FC3A5"></div>
                            <div class="status-box" style=""></div>
                            <div class="status-box" style=""></div>
                        </div>
                        <hr>
                        <button type="button" class="btn-rounded btn-md btn-empty btn-auto">{{ translateText('button.print_project_handbook') }}</button>
                    </div>
                </div>
                <!-- /// End Project Summary Widget /// -->

                <!-- /// Recent Tasks Widget /// -->
                <div class="widget recent-tasks-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translateText('message.project_summary') }}</h4>
                        <div>
                            <small-task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></small-task-box>
                        </div>
                        <div class="margintop20 buttons">
                            <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-md btn-empty btn-auto">{{ translateText('button.view_all_tasks') }}</router-link>
                            <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-md btn-empty btn-auto">{{ translateText('button.view_tasks_board') }}</router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End Recent Tasks Widget /// -->

                <!-- /// Task Status Widget /// -->
                <div class="widget task-status-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translateText('message.task_status') }}</h4>
                        <ul class="widget-list">
                            <li v-for="(item, index) in projectTasksStatus">
                                <span>{{ translateText(index) }}:</span>
                                <b>{{ item }}</b>
                            </li>
                        </ul>
                        <div class="task-status">
                            <circle-chart :percentage="project.task_status" v-bind:title="translateText('message.task_status')" class="left"></circle-chart>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status Widget /// -->
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import CircleChart from '../_common/_charts/CircleChart';
import SmallTaskBox from '../Dashboard/SmallTaskBox';
import moment from 'moment';

export default {
    components: {
        CircleChart,
        SmallTaskBox,
        moment,
    },
    methods: {
        ...mapActions(['getProjectById', 'getRecentTasksByProject', 'getProjectUsers', 'getTasksForSchedule', 'getColorStatuses', 'getTasksStatus']),
        translateText: function(text) {
            return this.translate(text);
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate);
            let start = moment(startDate);
            let diff = end.diff(start, 'days');

            return !isNaN(diff) ? diff : '-';
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getTasksForSchedule(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id});
        this.getTasksStatus(this.$route.params.id);
        this.getRecentTasksByProject(this.$route.params.id);
        if (!this.$store.state.colorStatus || (this.$store.state.colorStatus.colorStatuses && this.$store.state.colorStatus.colorStatuses.length === 0)) {
            this.getColorStatuses();
        }
    },
    computed: mapGetters({
        project: 'project',
        tasks: 'tasks',
        colorStatuses: 'colorStatuses',
        projectSponsors: 'projectSponsors',
        projectManagers: 'projectManagers',
        tasksForSchedule: 'tasksForSchedule',
        projectTasksStatus: 'projectTasksStatus',
    }),
    data() {
        return {
            activePage: 1,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '../../css/_variables';
    @import '../../css/page-section';

    .widget-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        margin-right: -15px;
        margin-left: -15px;

        .widget {
            width: 33.3333%;
            padding: 0 15px;

            .widget-content {
                background-color: $darkColor;
                padding: 25px 20px;

                h4 {
                    margin: 0 0 20px;
                }
            }

            hr{
                border-color: $middleColor;
            }
        }

        @media (max-width:1280px) {
            .widget {
                width: 50%;
            }
        }

        @media (max-width:800px) {
            .widget {
                width: 100%;
            }
        }
    }

    .table-small {
        >thead {
            >tr {
                >th {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        >tbody {
            >tr {
                >td {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        .btn-icon {
            margin-right: 10px;
            position: relative;
            top: 2px;
        }

        label {
            margin: 0;
        }
    }

    .widget-list {
        margin-bottom: 30px;

        li {
            display: flex;
            justify-content: space-between;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid $middleColor;
            padding: 5px 0;
            margin-bottom: 5px;

            span {
                color: $lightColor;
                width: 40%;

                b {
                    padding: 0;
                    color: $lighterColor;
                }
            }

            b {
                text-align: right;
                width: 60%;
                padding-left: 20px;
            }

            &:last-child {
                margin-bottom: 0;
            }
        }
    }

    .status-boxes {
        .status-box {
            width: 30px;
            height: 30px;
            margin-right: 5px;
            background-color:$fadeColor;
        }
    }

    .buttons {
        a.btn-rounded {
            margin-right: 10px;

            &:last-child {
                margin-right: 0;
            }
        }
    }

    .task-status {
        padding: 0 10%;
    }
</style>
