<template>
    <div class="page-section tasks">
        <div class="header">
            <h1>My Recent Tasks</h1>
            <div class="full-filters">
                <task-filters></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded">View all my Tasks</router-link>
            </div>
        </div>
        <div class="content">
            <small-task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></small-task-box>
        </div>
    </div>
</template>

<script>
import TaskFilters from '../_common/TaskFilters';
import SmallTaskBox from './SmallTaskBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        TaskFilters,
        SmallTaskBox,
    },
    methods: mapActions(['getRecentTasks', 'getColorStatuses']),
    created() {
        this.getRecentTasks();
        this.getColorStatuses();
    },
    computed: mapGetters({
        tasks: 'filteredTasks',
        colorStatuses: 'colorStatuses',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/page-section';
</style>
