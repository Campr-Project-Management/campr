<template>
    <div class="task-box box small-box">
        <div class="header">
            <div>
                <h2>{{ task.name }}</h2>
                <p class="task-id">#{{ task.id }}</p>
            </div>
            <div class="status-boxes">
                <span v-for="cs in colorStatuses" class="status-box" v-bind:style="{ background: task.colorStatusName === cs.name ? task.colorStatusColor : '' }"></span>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p class="title">{{ task.projectName }}</p>
                <p class="status">{{ task.colorStatusName }}</p>
            </div>
        </div>
        <bar-chart position="right" :percentage="task.progress" :status="task.colorStatusName" v-bind:title-right="message.progress"></bar-chart>
    </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';

export default {
    components: {
        BarChart,
    },
    props: ['task', 'colorStatuses'],
    data() {
        return {
            message: {
                progress: window.Translator.trans('message.progress'),
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
</style>
