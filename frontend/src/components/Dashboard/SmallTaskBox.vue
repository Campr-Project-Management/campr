<template>
    <div class="task-box-wrapper">
        <div class="task-box box small-box">
            <div class="box-header">
                <div>
                    <div v-if="task.responsibility" class="user-info flex flex-v-center">
                        <img class="user-avatar" :src="task.responsibilityAvatar" :alt="task.responsibilityFullName"/>
                        <p>{{ task.responsibilityFullName }}</p>
                    </div>
                    <h2>
                        <router-link :to="{name: 'project-task-management-view', params: { id: task.project, taskId: task.id }}">
                            {{ task.name }}
                        </router-link>
                    </h2>
                    <p class="task-id">#{{ task.id }}</p>
                </div>
                <div class="status-boxes">
                    <span v-for="cs in colorStatuses" class="status-box" v-bind:style="{ background: task.colorStatusName === cs.name ? task.colorStatusColor : '' }"></span>
                </div>
            </div>
            <div class="info">
                <div class="plan">
                    <a href="#path-to-phase" title="View phase.name">{{ task.projectName }}</a>
                    <span v-show="task.phaseName">
                        >
                        <a href="#path-to-phase" title="View phase.name">{{ task.phaseName }}</a>
                    </span>
                    <span v-show="task.milestoneName">
                        >
                        <a href="#path-to-milestone" title="View milestone.name">{{ task.milestoneName }}</a>
                    </span>
                </div>
            </div>
            <div class="status">
                <p><span>Status:</span> {{ translate(task.workPackageStatusName) }}</p>
                <bar-chart position="right" :percentage="task.progress" :color="task.colorStatusColor" v-bind:title-right="message.progress"></bar-chart>
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';

export default {
    components: {
        BarChart,
    },
    methods: {
        translate(text) {
            return window.Translator.trans(text);
        },
    },
    props: ['task', 'colorStatuses'],
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
          font-size: 8px;
          height: 23px;
          line-height: 23px;
          display: inline-block;

          &.finished {
              background: $secondColor;
          }
      }
  }
</style>
