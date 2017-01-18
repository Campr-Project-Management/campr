<template>
    <div class="project-box box small-box">
        <div class="header">
            <h2>{{ task.name }}</h2>
            <div>
              <eye-icon :link="{name: 'tasks', params: { id: task.id }}"></eye-icon>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p class="title">{{ task.projectName }}</p>
                <p class="status">{{ task.colorStatusName }}</p>
                <div class="status-boxes">
                    <span v-for="cs in colorStatuses" class="status-box" v-bind:style="{ background: task.colorStatusName === cs.name ? '#' + task.colorStatusColor : '' }"></span>
                </div>
            </div>
        </div>
        <bar-chart :percentage="task.progress" :status="task.colorStatusName" title-right="Progress"></bar-chart>
    </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';
import EyeIcon from '../_common/_icons/EyeIcon';

export default {
    components: {
        BarChart,
        EyeIcon,
    },
    props: ['task', 'colorStatuses'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common.scss';
  @import '../../css/box';
  @import '../../css/box-task';
</style>
