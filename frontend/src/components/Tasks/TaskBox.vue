<template>
    <div class="task-box-wrapper">
        <div class="task-box box" v-bind:class="'border-color-' + task.id">
            <div class="box-header">
                <div v-if="task.responsibility" class="user-info flex flex-v-center">
                    <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + task.responsibilityAvatar + ')' }"></div>
                    <p class="user-name">{{ task.responsibilityFullName }}</p>
                </div>
                <h2>
                    <router-link
                            :to="{name: 'project-task-management-view', params: { id: task.project, taskId: task.id }}"
                            class="simple-link">
                        {{ task.name }}
                    </router-link>
                </h2>
                <p class="task-id">#{{ task.id }}</p>
                <div class="status-boxes">
                    <span
                            v-for="cs in colorStatuses"
                            :key="cs.id"
                            class="status-box"
                            :style="{ background: statusColor(cs) }"
                            v-tooltip="hasColorStatus(cs) && colorStatusTooltip">
                    </span>
                </div>
            </div>
            <div class="content">
                <div class="info">
                    <router-link :to="{name: 'project-dashboard', params: {id: task.project}}">
                        {{ task.projectName }}
                    </router-link>
                    <span v-show="task.phaseName">
                        >
                        <router-link
                                :to="{name: 'project-phases-view-phase', params: {id: task.project, phaseId: task.phase}}">
                            {{ task.phaseName }}
                        </router-link>
                    </span>
                    <span v-show="task.milestoneName">
                        >
                        <router-link
                                :to="{name: 'project-phases-view-milestone', params: {id: task.project, milestoneId: task.milestone}}">
                            {{ task.milestoneName }}
                        </router-link>
                    </span>

                    <task-schedule-bar :task="task" title="message.schedule"/>
                    <task-cost-bar :task="task" title="message.cost"/>
                </div>
            </div>            
            <bar-chart :percentage="task.progress" :status="task.colorStatusName" :color="task.colorStatusColor"
                       :title-left="'' + translateText(task.workPackageStatusName)"></bar-chart>
            <scrollbar class="task-content customScrollbar">
                <div v-html="task.content"></div>
            </scrollbar>
            <div class="info bottom" v-if="task">
                <div class="icons">
                    <div class="icon-holder">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                        viewBox="0 0 17.3 24.3" style="enable-background:new 0 0 17.3 24.3;" xml:space="preserve">
                        <path id="XMLID_1674_" class="st0" d="M200.8,632.3v-6.8c0-0.5-0.1-1.5-1.1-1.5"/>
                        <g id="XMLID_1672_">
                            <g id="XMLID_1673_">
                                <path id="XMLID_1_" class="st0" d="M7.5,21.4c-2.2,0-3.7-1.7-3.7-4.1V8.7c0-3,2.1-5.3,4.9-5.3s4.9,2.3,4.9,5.3V15
                                c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8V8.7c0-2.2-1.4-3.8-3.4-3.8S5.3,6.5,5.3,8.7v8.6c0,1.3,0.7,2.6,2.3,2.6
                                s2.3-1.3,2.3-2.6v-6.8C9.8,10.1,9.7,9,8.6,9s-1.1,1-1.1,1.5v5.6c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8v-5.6
                                c0-1.8,1.1-3,2.6-3s2.6,1.2,2.6,3v6.8C11.3,19.7,9.7,21.4,7.5,21.4z"/>
                            </g>
                        </g>
                        </svg>
                        <span class="number">{{ task.noAttachments }}</span>
                    </div>

                    <div class="icon-holder">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                        viewBox="0 0 28.2 24.5" style="enable-background:new 0 0 28.2 24.5;" xml:space="preserve">
                        <path id="XMLID_1148_" class="st0" d="M23.8,3.4H4.1c-0.2,0-0.4,0.2-0.4,0.4v13.7c0,0.2,0.2,0.4,0.4,0.4h5.6v3
                        c0,0.4,0.5,0.6,0.7,0.3l3.3-3.3h10.1c0.2,0,0.4-0.2,0.4-0.4V3.9C24.3,3.6,24.1,3.4,23.8,3.4z M8.4,7.7h7.7c0.2,0,0.4,0.2,0.4,0.4
                        c0,0.2-0.2,0.4-0.4,0.4H8.4C8.2,8.6,8,8.4,8,8.1C8,7.9,8.2,7.7,8.4,7.7z M19.5,13.7H8.4c-0.2,0-0.4-0.2-0.4-0.4
                        c0-0.2,0.2-0.4,0.4-0.4h11.1c0.2,0,0.4,0.2,0.4,0.4C20,13.5,19.8,13.7,19.5,13.7z M19.5,11.1H8.4c-0.2,0-0.4-0.2-0.4-0.4
                        c0-0.2,0.2-0.4,0.4-0.4h11.1c0.2,0,0.4,0.2,0.4,0.4C20,10.9,19.8,11.1,19.5,11.1z"/>
                        </svg>
                        <span class="number">{{ task.noComments }}</span>
                    </div>
                    <div class="icon-holder">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                        viewBox="0 0 26.4 26.4" style="enable-background:new 0 0 26.4 26.4;" xml:space="preserve">
                        <g id="XMLID_493_">
                        <path id="XMLID_499_" class="st0" d="M22.3,4.2c-0.3-0.3-0.8-0.2-1.1,0.1L11.7,16l-3.4-3.4c-0.3-0.3-0.8-0.3-1.1,0
                            c-0.3,0.3-0.3,0.8,0,1.1l4,4c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.5-0.1,0.6-0.3l10-12.4C22.7,5,22.6,4.5,22.3,4.2z"/>
                        <path id="XMLID_496_" class="st0" d="M19.4,11.6c-0.4,0-0.8,0.4-0.8,0.8v8H5.8V7.6h9.6c0.4,0,0.8-0.4,0.8-0.8
                            c0-0.4-0.4-0.8-0.8-0.8H5C4.5,6,4.2,6.4,4.2,6.8v14.4C4.2,21.7,4.5,22,5,22h14.4c0.4,0,0.8-0.4,0.8-0.8v-8.8
                            C20.2,12,19.8,11.6,19.4,11.6z"/>
                        </g>
                        </svg>
                        <span class="number">{{ task.noSubtasks }}</span>
                    </div>
                </div>
            </div>
        </div>
        <task-label-bar
            v-if="hasLabel()"
            :title="task.labelName"
                :color="task.labelColor" />
    </div>
</template>

<script>
    import BarChart from '../_common/_charts/BarChart';
    import moment from 'moment';
    import TaskScheduleBar from './TaskScheduleBar.vue';
    import TaskCostBar from './TaskCostBar.vue';
    import TaskLabelBar from './TaskLabelBar';
    import _ from 'lodash';

    export default {
        components: {
            BarChart,
            TaskScheduleBar,
            TaskCostBar,
            TaskLabelBar,
        },
        computed: {
            colorStatusTooltip() {
                let tooltip = '';
                if (!this.task.colorStatus) {
                    return tooltip;
                }

                let colorStatus = _.find(this.colorStatuses, (colorStatus) => {
                    return colorStatus.id === this.task.colorStatus;
                });

                if (!colorStatus) {
                    return tooltip;
                }

                return this.translate(colorStatus.name);
            },
        },
        methods: {
            duration: function(startDate, endDate) {
                let start = moment(startDate);
                let end = moment(endDate);
                return end.diff(start, 'days');
            },
            translateText: function(text) {
                return this.translate(text);
            },
            hasColorStatus(colorStatus) {
                return !!(this.task.colorStatus && this.task.colorStatus === colorStatus.id);
            },
            statusColor(colorStatus) {
                if (!this.hasColorStatus(colorStatus)) {
                    return false;
                }

                return colorStatus.color;
            },
            hasLabel() {
                return this.task.label && this.task.labelName && this.task.labelColor;
            },
        },
        props: {
            task: {
                type: Object,
                required: true,
            },
            colorStatuses: {
                type: Array,
                required: true,
            },
        },
    };
</script>

<style lang="scss" scoped="scoped">
    @import '../../css/_variables';
    @import '../../css/_mixins';
    @import '../../css/box';
    @import '../../css/box-task';

    .task-box-wrapper {
        .ps__scrollbar-y-rail {
            width: 8px !important;

            .ps__scrollbar-y {
                width: 4px !important;
                background: $middleColor !important;
            }
        }
    }

    h2 {
        line-height: 15px;
    }

    table {
        margin: 0 -10px;
        white-space: nowrap;

        td {
            font-size: 10px;
        }

        th, td {
            padding: 3px 9px;
        }
    }

    .st0 {
        fill: $middleColor;
    }

    .progress-line.bar-chart {
        margin-top: 40px;
    }

    .task-content {
        margin: 20px 0;
        padding-right: 10px;
        max-height: 100px;
        overflow: hidden;
    }

    .bullets {
        li {
            margin-bottom: 19px;
        }
    }

    .info.bottom {
        padding-top: 0;
    }
</style>
