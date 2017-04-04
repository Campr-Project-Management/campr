<template>
    <div class="task-box box small-box">
        <div class="header">
            <div>
                <h2>
                    <router-link :to="{name: 'task', params: { id: task.id }}">
                        {{ task.name }}
                    </router-link>
                </h2>
                <p class="task-id">#{{ task.id }}</p>
            </div>
            <div class="status-boxes">
                <span v-for="cs in colorStatuses" class="status-box" v-bind:style="{ background: task.colorStatusName === cs.name ? task.colorStatusColor : '' }"></span>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p class="title">{{ task.projectName }}</p>
                <p class="status">{{ translate(task.workPackageStatusName) }}</p>
            </div>
        </div>
        <bar-chart position="right" :percentage="task.progress" :color="task.colorStatusColor" v-bind:title-right="message.progress"></bar-chart>
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
  @import '../../css/_common.scss';
  @import '../../css/box';
  @import '../../css/box-task';

  .box {
      width: 394px;
      margin: 0 12.5px 30px;
  }

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
