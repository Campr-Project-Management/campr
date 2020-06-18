<template>
    <div>
        <div class="page-section">
            <div class="header flex">
                <h1>{{ translate('message.project_dashboard') }}</h1>
                <button
                        v-if="$can('ROLE_ADMIN|ROLE_SUPER_ADMIN', project)"
                        v-on:click="openCopyProjectModal()"
                        class="btn-rounded btn-md btn-auto info-bg">{{ translate('message.duplicate') }}</button>
            </div>

            <div class="content widget-grid">
                <!-- /// Project Summary Widget /// -->
                <div class="widget project-summary-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translate('message.project_summary') }}</h4>
                        <ul class="widget-list">
                            <li>
                                <span>{{ translate('message.project_name') }}:</span>
                                <b v-if="project.name">{{ project.name }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.project_number') }}:</span>
                                <b v-if="project.number">#{{ project.number }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('label.portfolio') }}:</span>
                                <b v-if="project.portfolioName">{{ project.portfolioName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('label.programme') }}:</span>
                                <b v-if="project.programmeName">{{ project.programmeName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.category') }}:</span>
                                <b v-if="project.projectCategoryName">{{ project.projectCategoryName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.scope') }}:</span>
                                <b v-if="project.projectScopeName">{{ project.projectScopeName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.customer') }}:</span>
                                <b v-if="project.company">{{ project.companyName }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.approved_on') }}:</span>
                                <b v-if="contract && contract.approvedAt">{{ contract.approvedAt }}</b>
                                <b v-else>-</b>
                            </li>
                            <li>
                                <span>{{ translate('message.project_sponsor') }}:</span>
                                <div>
                                    <b
                                            v-if="project.projectSponsors"
                                            v-for="(sponsor, index) in project.projectSponsors"
                                            :key="`project_sponsor_${index}`">
                                        {{ sponsor.userFullName }}<span
                                            v-if="index !== project.projectSponsors.length - 1">, </span>
                                    </b>
                                    <b v-else>-</b>
                                </div>
                            </li>
                            <li>
                                <span>{{ translate('message.project_managers') }}:</span>
                                <div>
                                    <b v-if="project.projectManagers"
                                       v-for="(manager, index) in project.projectManagers"
                                       :key="`project_manager_${index}`">
                                        {{ manager.userFullName }}<span
                                            v-if="index !== project.projectManagers.length - 1">, </span>
                                    </b>
                                    <b v-else>-</b>
                                </div>
                            </li>
                            <li>
                                <span>{{ translate('message.currency') }}:</span>
                                <div>
                                    <b v-if="project.currency">{{ project.currency.symbol }}</b>
                                </div>
                            </li>
                        </ul>
                        <h4 class="widget-title">{{ translate('message.project_schedule') }}</h4>
                        <scrollbar class="customScrollbar">
                            <div class="scroll-wrapper">
                                <table class="table table-small">
                                    <thead>
                                    <tr>
                                        <th>{{ translate('message.schedule') }}</th>
                                        <th>{{ translate('message.start') }}</th>
                                        <th>{{ translate('message.finish') }}</th>
                                        <th>{{ translate('message.duration') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ translate('table_header_cell.base') }}</td>
                                        <td>{{ project.scheduledStartAt | date }}</td>
                                        <td>{{ project.scheduledFinishAt | date }}</td>
                                        <td>{{ project.scheduledDurationDays | formatNumber }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ translate('table_header_cell.forecast') }}</td>
                                        <td>{{ project.forecastStartAt | date }}</td>
                                        <td>{{ project.forecastFinishAt | date }}</td>
                                        <td>{{ project.forecastDurationDays | formatNumber }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ translate('table_header_cell.actual') }}</td>
                                        <td>{{ project.actualStartAt | date }}</td>
                                        <td>{{ project.actualFinishAt | date }}</td>
                                        <td>{{ project.actualDurationDays | formatNumber }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </scrollbar>

                        <div class="flex flex-direction-reverse margintop20">
                            <button v-if="project.status != projectStatus.PROJECT_STATUS_CLOSED" v-on:click="doCloseProject()" class="btn-rounded btn-md btn-auto danger-bg">{{ translate('button.close_project') }}</button>
                        </div>
                        <hr>

                        <h4 class="widget-title">{{ translate('message.team_members') }} - <b class="second-color" v-if="project.projectUsers">{{ project.projectUsers.length }}</b></h4>
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-md btn-empty btn-auto">
                            View entire team
                        </router-link>
                        <hr>

                        <h4 class="widget-title">
                            {{ translate('message.project_condition') }} -
                            <b
                                    v-for="(tl, index) in trafficLights"
                                    :key="index"
                                    :style="{color: tl.getColor()}"> {{ translate(tl.getLabel()) }} </b>
                        </h4>

                        <traffic-light
                                :value="projectTrafficLight"
                                size="small"
                                :editable="true"
                                @input="onUpdateProjectTrafficLight"/>
                        <hr/>

                        <!-- /// End Project Condition /// -->
                        <a
                                class="btn-rounded btn-md btn-empty btn-auto"
                                v-if="contract && contract.id"
                                :href="downloadPdf">
                            {{ translate('button.print_project_contract') }}
                        </a>
                    </div>
                </div>
                <!-- /// End Project Summary Widget /// -->

                <!-- /// Recent Tasks Widget /// -->

                <div class="widget recent-tasks-widget">
                    <project-dashboard-recent-tasks :tasks="recentTasks"/>
                </div>
                <!-- /// End Recent Tasks Widget /// -->

                <!-- /// Task Status Widget /// -->
                <div class="widget task-status-widget">
                    <div class="widget-content">
                        <h4 class="widget-title">{{ translate('message.task_status') }}</h4>
                        <ul class="widget-list">
                            <li v-for="(item, index) in projectTasksStatus" :key="index">
                                <span>{{ translate(index) }}:</span>
                                <div v-if="index !== 'conditions'">
                                    <b>{{ item }}</b>
                                </div>
                                <div v-else>
                                    <p v-bind:style="{color: item['color_status.not_started'].color}">{{ translate('color_status.not_started') }}: {{ item['color_status.not_started'].count }}</p>
                                    <p v-bind:style="{color: item['color_status.in_progress'].color}">{{ translate('color_status.in_progress') }}: {{ item['color_status.in_progress'].count }}</p>
                                    <p v-bind:style="{color: item['color_status.finished'].color}">{{ translate('color_status.finished') }}: {{ item['color_status.finished'].count }}</p>
                                </div>
                            </li>
                        </ul>
                        <div class="task-status">
                            <circle-chart
                                    :bg-stroke-color="$theme.darker"
                                    :percentage="project.progress"
                                    :precision="0"
                                    :title="translate('message.task_status')"
                                    class="left"/>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status Widget /// -->
            </div>
        </div>

        <alert-modal v-if="showClosed" body="message.project_closed" @close="showClosed = false;"/>
        <alert-modal v-if="showCloned" body="message.project.clone.in_progress" @close="showCloned = false;" />
        <modal v-if="showCopyProjectModal" @close="showCopyProjectModal = false" v-bind:hasSpecificClass="true">
            <p class="modal-title">{{ translate('message.duplicate') }}</p>
            <div class="form-group">
                <input-field
                        v-model="projectName"
                        type="text"
                        :label="translate('message.project_name')"/>
                <error at-path="projectName" v-if="validationMessages"
                       v-for="message in validationMessages"
                       :message="message" />
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showCopyProjectModal = false"
                   class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="doCopyProjectModal()" v-if="projectName"
                   class="btn-rounded btn-auto second-bg">{{ translate('button.copy_project') }} +</a>
            </div>
        </modal>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../_common/_form-components/InputField.vue';
import Modal from '../_common/Modal.vue';
import Error from '../_common/_messages/Error.vue';
import CircleChart from '../_common/_charts/CircleChart';
import SmallTaskBox from '../Dashboard/SmallTaskBox';
import AlertModal from '../_common/AlertModal.vue';
import moment from 'moment';
import * as projectStatus from '../../store/modules/project-status';
import TrafficLight from '../_common/TrafficLight';
import tl from '../../util/traffic-light';
import ProjectDashboardRecentTasks from './Dashboard/RecentTasks';

export default {
    props: {
        locale: {
            type: String,
        },
    },
    components: {
        InputField,
        Modal,
        Error,
        ProjectDashboardRecentTasks,
        TrafficLight,
        CircleChart,
        SmallTaskBox,
        moment,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getContractByProjectId',
            'getRecentTasksByProjectAndUser',
            'getProjectUsers',
            'getTasksForSchedule',
            'getTasksStatus',
            'closeProject',
            'editProject',
            'cloneProject',
        ]),
        onUpdateProjectTrafficLight(trafficLight) {
            this.editProject({
                trafficLight: trafficLight,
                projectId: this.$route.params.id,
            });
            this.projectTrafficLight = trafficLight;
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
                            this.$flashError('message.unable_to_save');
                        }
                    },
                    () => {
                        this.$flashError('message.unable_to_save');
                    },
                )
            ;
        },
        openCopyProjectModal() {
            this.showCopyProjectModal = true;
        },
        doCopyProjectModal() {
            let data = {id: this.project.id, name: this.projectName};
            this
            .cloneProject(data)
             .then(
                (response) => {
                    this.showCopyProjectModal = false;
                    this.projectName = null;
                    if(response.status === 200) {
                        this.showFailed = false;
                        this.showCloned = true;
                    }else{
                        this.showFailed = true;
                        this.showCloned = false;
                    }
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
        this.getContractByProjectId(this.$route.params.id);

        if (this.localUser && this.localUser.id) {
            this.getRecentTasksByProjectAndUser({
                projectId: this.$route.params.id,
                userId: this.localUser.id,
            });
        }
    },
    computed: {
        ...mapGetters({
            contract: 'currentContract',
        }),
        ...mapGetters([
            'trafficLights',
            'project',
            'tasks',
            'tasksForSchedule',
            'projectTasksStatus',
            'localUser',
        ]),
        projectContractId() {
            if (this.contract && this.contract.id) {
                return this.contract.id;
            }

            return null;
        },
        downloadPdf() {
            return Routing.generate('app_contract_pdf', {id: this.projectContractId});
        },
        recentTasks() {
            let tasks = this.tasks;
            if (!tasks) {
                return [];
            }

            return tasks;
        },
    },
    watch: {
        project(value) {
            this.projectTrafficLight = value && value.trafficLight;
        },
        localUser(value, oldValue) {
            if (value.id !== oldValue.id) {
                this.getRecentTasksByProjectAndUser({
                    projectId: this.$route.params.id,
                    userId: value.id,
                });
            }
        },
    },
    data() {
        return {
            showClosed: false,
            showFailed: false,
            showCloned: false,
            activePage: 1,
            projectStatus,
            projectTrafficLight: tl.TrafficLight.GREEN,
            showCopyProjectModal: false,
            projectName: '',
            cloneErrorMessages: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '~theme/variables';
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

            hr {
                border-color: $middleColor;
            }

            .task-box-wrapper {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 1280px) {
            .widget {
                width: 50%;
            }
        }

        @media (max-width: 800px) {
            .widget {
                width: 100%;
            }
        }
    }

    .table-small {
        > thead {
            > tr {
                > th {
                    height: 30px;
                    padding: 5px 15px !important;
                }
            }
        }

        > tbody {
            > tr {
                > td {
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

        @media (max-width: 1440px) and (min-width: 1280px) {
            padding: 0;
        }
    }
</style>
