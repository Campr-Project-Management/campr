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
                                <span>{{ translateText('message.category') }}:</span>
                                <b v-if="project.projectCategoryName">{{ project.projectCategoryName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.scope') }}:</span>
                                <b v-if="project.projectScopeName">{{ project.projectScopeName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.customer') }}:</span>
                                <b v-if="project.company">{{ project.companyName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.approved_on') }}:</span>
                                <b v-if="contract && contract.approvedAt">{{ contract.approvedAt }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translateText('message.project_sponsor') }}:</span>
                                <div>
                                    <b v-if="projectSponsors" v-for="(sponsor, index) in projectSponsors" :key="index">
                                        {{ sponsor.userFullName }}<span v-if="index != projectSponsors.length - 1">, </span>
                                    </b>
                                    <b v-else>-</b>
                                </div>
                            </li>
                            <li>
                                <span>{{ translateText('message.project_managers') }}:</span>
                                <div>
                                    <b v-if="projectManagers" v-for="(manager, index) in projectManagers" :key="index">
                                        {{ manager.userFullName }}<span v-if="index != projectManagers.length - 1">, </span>
                                    </b>
                                    <b v-else>-</b>
                                </div>
                            </li>
                            <li>
                                <span>{{ translateText('message.currency') }}:</span>
                                <div>
                                    <b v-if="project.currency">{{ project.currency.code }} ({{ project.currency.name }})</b>
                                </div>
                            </li>
                        </ul>
                        <h4 class="widget-title">{{ translateText('message.project_schedule') }}</h4>
                        <scrollbar class="customScrollbar">
                            <div class="scroll-wrapper">
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
                                            <td>{{ project.scheduledStartAt | date }}</td>
                                            <td>{{ project.scheduledFinishAt | date }}</td>
                                            <td>{{ project.scheduledDurationDays | formatNumber }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ translateText('table_header_cell.forecast') }}</td>
                                            <td>{{ project.forecastStartAt | date }}</td>
                                            <td>{{ project.forecastFinishAt | date }}</td>
                                            <td>{{ project.forecastDurationDays | formatNumber }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ translateText('table_header_cell.actual') }}</td>
                                            <td>{{ project.actualStartAt | date }}</td>
                                            <td>{{ project.actualFinishAt | date }}</td>
                                            <td>{{ project.actualDurationDays | formatNumber }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </scrollbar>

                        <div class="flex flex-direction-reverse margintop20" v-if="project.status != projectStatus.PROJECT_STATUS_CLOSED">
                            <button v-on:click="doCloseProject()" class="btn-rounded btn-md btn-auto danger-bg">{{ translateText('button.close_project') }}</button>
                        </div>
                        <hr>

                        <h4 class="widget-title">{{ translateText('message.team_members') }} - <b class="second-color" v-if="project.projectUsers">{{ project.projectUsers.length }}</b></h4>
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-md btn-empty btn-auto">View entire team</router-link>
                        <hr>

                        <h4 class="widget-title" v-if="colorStatuses && colorStatuses.length">
                            {{ translateText('message.project_status') }} -
                            <b v-for="(colorStatus, index) in colorStatuses" :key="index" :style="{color: colorStatus.color}"> {{ translateText(colorStatus.name) }} </b>
                        </h4>

                        <div class="status-boxes flex flex-v-center" v-if="colorStatuses && colorStatuses.length && userIsManager">
                            <div v-for="(colorStatus, index) in colorStatuses" :key="index" class="status-box" :style="{backgroundColor: (project.status === colorStatus.id ? colorStatus.color : null)}"  v-on:click="updateColorStatus(colorStatus)"></div>
                        </div>

                        <div class="status-boxes flex flex-v-center" v-if="colorStatuses && colorStatuses.length && !userIsManager">
                            <div v-for="(colorStatus, index) in colorStatuses" :key="index" class="status-box" :style="{backgroundColor: (project.status === colorStatus.id ? colorStatus.color : null)}"></div>
                        </div>

                        <hr v-if="colorStatuses && colorStatuses.length">

                        <!-- /// End Project Condition /// -->

                        <a
                            class="btn-rounded btn-md btn-empty btn-auto"
                            v-if="contract && contract.id"
                            :href="downloadPdf">
                            {{ translateText('button.print_project_contract') }}
                        </a>
                    </div>
                </div>
                <!-- /// End Project Summary Widget /// -->

                <!-- /// Recent Tasks Widget /// -->
                <div class="widget recent-tasks-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translateText('message.project_summary') }}</h4>
                        <div>
                            <small-task-box v-for="(task, index) in tasks" :key="index" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></small-task-box>
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
                            <li v-for="(item, index) in projectTasksStatus" :key="index">
                                <span>{{ translateText(index) }}:</span>
                                <div v-if="index !== 'conditions'">
                                    <b>{{ item }}</b>
                                </div>
                                <div v-else>
                                    <p v-bind:style="{color: item['color_status.not_started'].color}">{{ translateText('color_status.not_started') }}: {{ item['color_status.not_started'].count }}</p>
                                    <p v-bind:style="{color: item['color_status.in_progress'].color}">{{ translateText('color_status.in_progress') }}: {{ item['color_status.in_progress'].count }}</p>
                                    <p v-bind:style="{color: item['color_status.finished'].color}">{{ translateText('color_status.finished') }}: {{ item['color_status.finished'].count }}</p>
                                </div>
                            </li>
                        </ul>
                        <div class="task-status">
                            <circle-chart :percentage="project.progress" v-bind:title="translateText('message.task_status')" class="left"></circle-chart>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status Widget /// -->
            </div>
        </div>

        <alert-modal v-if="showClosed" body="message.project_closed" @close="showClosed = false;" />
        <alert-modal v-if="showFailed" body="message.unable_to_save" @close="showFailed = false;" />
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import CircleChart from '../_common/_charts/CircleChart';
import SmallTaskBox from '../Dashboard/SmallTaskBox';
import AlertModal from '../_common/AlertModal.vue';
import moment from 'moment';
import * as projectStatus from '../../store/modules/project-status';

export default {
    components: {
        CircleChart,
        SmallTaskBox,
        moment,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getContractByProjectId',
            'getRecentTasksByProject',
            'getProjectUsers',
            'getTasksForSchedule',
            'getColorStatuses',
            'getTasksStatus',
            'closeProject',
            'editProject',
        ]),
        translateText(text) {
            return this.translate(text);
        },
        getDuration(startDate, endDate) {
            let end = moment(endDate);
            let start = moment(startDate);
            let diff = end.diff(start, 'days');

            return !isNaN(diff) ? diff + 1 : '-';
        },
        updateColorStatus(colorStatus) {
            this.editProject({
                status: colorStatus.id,
                projectId: this.$route.params.id,
            });
        },
        doCloseProject() {
            const {id} = this.project;
            this
                .closeProject({id})
                .then(
                    (response) => {
                        if (response.status === 200) {
                            this.showClosed = true;
                        } else {
                            this.showFailed = true;
                        }
                    },
                    () => {
                        this.showFailed = true;
                    }
                )
            ;
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getTasksForSchedule(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id});
        this.getTasksStatus(this.$route.params.id);
        this.getRecentTasksByProject(this.$route.params.id);
        this.getContractByProjectId(this.$route.params.id);
        if (!this.$store.state.colorStatus || (this.$store.state.colorStatus.colorStatuses && this.$store.state.colorStatus.colorStatuses.length === 0)) {
            this.getColorStatuses();
        }
    },
    computed: {
        ...mapGetters({
            project: 'project',
            tasks: 'tasks',
            colorStatuses: 'colorStatuses',
            projectSponsors: 'projectSponsors',
            projectManagers: 'projectManagers',
            tasksForSchedule: 'tasksForSchedule',
            projectTasksStatus: 'projectTasksStatus',
            contract: 'currentContract',
            localUser: 'localUser',
        }),
        userIsManager() {
            let isManager = false;

            this.projectManagers.forEach((item, index) => {
                if(item.user == this.localUser.id) {
                    isManager = true;
                }
            });

            return isManager;
        },
        projectContractId() {
            if (this.contract && this.contract.id) {
                return this.contract.id;
            }

            return null;
        },
        projectContract() {
            if (this.contract && this.contract.id) {
                return this.contract;
            }

            return null;
        },
        downloadPdf() {
            return Routing.generate('app_contract_pdf', {id: this.projectContractId});
        },
    },
    data() {
        return {
            showClosed: false,
            showFailed: false,
            activePage: 1,
            projectStatus,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '../../css/_variables';
    @import '../../css/page-section';
    @import '../../css/common';

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

            .task-box-wrapper {
                margin-bottom: 20px;
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
                    padding: 5px 15px !important;
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
        margin: 0 0 30px;
        padding: 0;
        list-style: none;

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

            div {
                b {
                    padding-left: 5px;
                }
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
            background-color: $fadeColor;
            cursor: auto;
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

        svg {
            width: 100%;
        }

        @media (max-width: 1440px) and (min-width:1280px) {
            padding: 0;
        }
    }
</style>
