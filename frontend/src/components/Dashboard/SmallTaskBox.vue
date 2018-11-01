<template>
    <div class="task-box-wrapper">
        <div class="task-box box small-box">
            <div class="box-header">
                <div>
                    <div v-if="task.responsibility" class="user-info flex flex-v-center">
                        <user-avatar
                                size="small"
                                :url="task.responsibilityAvatar"
                                :name="task.responsibilityFullName"/>
                        <p class="user-name">{{ task.responsibilityFullName }}</p>
                    </div>
                    <h2>
                        <router-link :to="{name: 'project-task-management-view', params: { id: task.project, taskId: task.id }}">
                            {{ task.name }}
                        </router-link>
                    </h2>
                    <p class="task-id">#{{ task.id }}</p>
                </div>
                <div class="status-boxes">
                    <traffic-light
                            size="small"
                            :value="task.trafficLight"/>
                </div>
            </div>
            <div class="info">
                <div class="plan">
                    <router-link :to="{name: 'project-dashboard', params: {id: task.project}}">
                        {{ task.projectName }}
                    </router-link>
                    <span v-show="task.phaseName">
                        >
                        <router-link :to="{name: 'project-phases-view-phase', params: {id: task.project, phaseId: task.phase}}">
                            {{ task.phaseName }}
                        </router-link>
                    </span>
                    <span v-show="task.milestoneName">
                        >
                        <router-link :to="{name: 'project-phases-view-milestone', params: {id: task.project, milestoneId: task.milestone}}">
                            {{ task.milestoneName }}
                        </router-link>
                    </span>
                </div>
            </div>
            <div class="status">
                <p><span>{{ translate('message.status') }}:</span> {{ translate(task.workPackageStatusName) }}</p>
                <bar-chart
                        position="right"
                        :percentage="task.progress"
                        :color="trafficLightColorByValue(task.trafficLight)"
                        :title-right="translate(message.progress)"/>
            </div>
        </div>
        <task-label-bar
                v-if="hasLabel"
                :title="task.labelName"
                :color="task.labelColor"/>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import BarChart from '../_common/_charts/BarChart';
import TaskLabelBar from '../Tasks/TaskLabelBar';
import TrafficLight from '../_common/TrafficLight';
import UserAvatar from '../_common/UserAvatar';

export default {
    name: 'dashboard-small-task-box',
    props: ['task'],
    components: {
        UserAvatar,
        TrafficLight,
        BarChart,
        TaskLabelBar,
    },
    computed: {
        ...mapGetters([
            'trafficLightColorByValue',
        ]),
    },
    methods: {
        hasLabel() {
            return this.task.label && this.task.labelColor;
        },
    },
    data() {
        return {
            message: {
                progress: Translator.trans('message.progress'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables.scss';
  @import '../../css/_mixins.scss';
  @import '../../css/box';
  @import '../../css/box-task';

  .info {
      padding-top: 16px;

      p {
          margin: 0 0 6px 0;
      }

      .title {
          color: $middleColor;
          text-transform: uppercase;
          font-size: 12px;
      }

      .data {
          font-size: 11px;
          letter-spacing: 0.5px;
          margin-left: 6px;
      }

      .status-label {
          padding: 0 19px;
          margin-left: 5px;
          font-size: 10px;
          height: 23px;
          line-height: 23px;
          display: inline-block;

          &.finished {
              background: $secondColor;
          }
      }
  }

  .task-box.box.small-box {
      white-space: normal;
  }
</style>
