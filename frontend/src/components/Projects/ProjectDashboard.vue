<template>
    <div>
        <div class="page-section">
            <div class="header flex">
                <h1>Project Dashboard</h1>
            </div>

            <div class="content widget-grid">
                <!-- /// Project Summary Widget /// -->
                <div class="widget project-summary-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">Project Summary</h4>
                        <ul class="widget-list">
                            <li>
                                <span>Project Name:</span>
                                <b>Tesla-SpaceX Mars Project</b>
                            </li>
                            <li>
                                <span>Project No.:</span>
                                <b>#22</b>
                            </li>
                            <li>
                                <span>Portfolio:</span>
                                <b>Tesla Projects</b>
                            </li>
                            <li>
                                <span>Programme:</span>
                                <b>Space Colonization</b>
                            </li>
                            <li>
                                <span>Customer:</span>
                                <b>SpaceX</b>
                            </li>
                            <li>
                                <span>Approved on:</span>
                                <b>10.02.2016</b>
                            </li>
                            <li>
                                <span>Project Sponsor:</span>
                                <b>Elon Musk</b>
                            </li>
                            <li>
                                <span>Project Manager(s):</span>
                                <b>Christoph Pohl, Manuel Weiler, Radu TopalA </b>
                            </li>
                        </ul>

                        <h4 class="widget-title">Project Schedule</h4>
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>Schedule</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Base</td>
                                    <td>10.02.2017</td>
                                    <td>30.05.2018</td>
                                    <td>841</td>
                                </tr>
                                <tr>
                                    <td>Forecast</td>
                                    <td>10.02.2018</td>
                                    <td>30.01.2018</td>
                                    <td>721</td>
                                </tr>
                                <tr>
                                    <td>Actual</td>
                                    <td>10.02.2018</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex-direction-reverse margintop20">
                            <a href="#path-to-close-project" class="btn-rounded btn-md btn-auto danger-bg">Close Project</a>
                        </div>
                        <hr>
                        <h4 class="widget-title">Team Members - <b class="second-color">62</b></h4>
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-md btn-empty btn-auto">View entire team</router-link>
                        <hr>
                        <h4 class="widget-title">Team Members - <b style="color:#5FC3A5;">Green</b> <b style="color:#CCBA54;">Yellow</b> <b style="color:#C87369;">Red</b></h4>
                        <div class="status-boxes flex flex-v-center">
                            <div class="status-box" style="background-color:#5FC3A5"></div>
                            <div class="status-box" style=""></div>
                            <div class="status-box" style=""></div>
                        </div>
                        <hr>
                        <button type="button" class="btn-rounded btn-md btn-empty btn-auto">Print Project Handbook</button>
                    </div>
                </div>
                <!-- /// End Project Summary Widget /// -->

                <!-- /// Recent Tasks Widget /// -->
                <div class="widget recent-tasks-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">Project Summary</h4>
                        <div>
                            <small-task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></small-task-box>    
                        </div>
                        <div class="margintop20 buttons">
                            <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-md btn-empty btn-auto">View all tasks</router-link>
                            <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-md btn-empty btn-auto">View task boards</router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End Recent Tasks Widget /// -->

                <!-- /// Task Status Widget /// -->
                <div class="widget task-status-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">Task Status</h4>
                        <ul class="widget-list">
                            <li>
                                <span>Total Tasks:</span>
                                <b>185</b>
                            </li>
                            <li>
                                <span>Open Tasks:</span>
                                <b>45</b>
                            </li>
                            <li>
                                <span>Closed Tasks:</span>
                                <b>120</b>
                            </li>
                            <li>
                                <span>Idle:</span>
                                <b>20</b>
                            </li>
                            <li>
                                <span><b>To Do:</b></span>
                                <b>27</b>
                            </li>
                            <li>
                                <span><b>In Progress:</b></span>
                                <b>10</b>
                            </li>
                            <li>
                                <span><b>Code Review:</b></span>
                                <b>5</b>
                            </li>
                            <li>
                                <span><b>Finished:</b></span>
                                <b>120</b>
                            </li>
                        </ul>
                        <div class="task-status">
                            <circle-chart :percentage="project.task_status" v-bind:title="message.task_status" class="left"></circle-chart>
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

export default {
    components: {
        CircleChart,
        SmallTaskBox,
    },
    methods: mapActions(['getProjectById', 'getRecentTasks']),
    created() {
        this.getProjectById(this.$route.params.id);
        if (!this.$store.state.task || this.$store.state.task.items.length === 0) {
            this.getRecentTasks(this.activePage);
        }
    },
    computed: mapGetters({
        project: 'project',
        tasks: 'tasks',
    }),
    data() {
        return {
            message: {
                task_status: Translator.trans('message.task_status'),
            },
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