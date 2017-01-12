<template>
    <div class="project-box box small-box">
        <div class="header">
            <h2>{{ task.title }}</h2>
            <div>
              <eye-icon :link="{name: 'task', params: { id: task.id }}"></eye-icon>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p class="title">DigitlWall > Phase 2 > Millestone 2.2</p>
                <p class="status">Status</p>
                <div class="status-boxes">
                    <span class="status-box" v-bind:class="{ 'second-col-bg': task.status === 'FINISHED' }"></span>
                    <span class="status-box" v-bind:class="{ 'warning-col-bg': task.status === 'IN_PROGRESS' }"></span>
                    <span class="status-box" v-bind:class="{ 'danger-col-bg': task.status === 'NOT_STARTED' }"></span>
                </div>
            </div>
        </div>
        <bar-chart :percentage="task.percentage" :status="task.status" title-right="Progress"></bar-chart>
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
    props: ['task'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common.scss';
  @import '../../css/box';
  @import '../../css/box-task';
</style>
