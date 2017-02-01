<template>
    <div>
        <div class="page-section tasks">
            <div class="header">
                <h1>My Tasks</h1>
                <task-filters></task-filters>
            </div>
            <div class="content">
                <task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></task-box>
                <a href="" class="new-box">New Task +</a>
            </div>
        </div>
    </div>
</template>

<script>
import TaskFilters from '../_common/TaskFilters';
import TaskBox from './TaskBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        TaskFilters,
        TaskBox,
    },
    methods: {
        ...mapActions(['getTasks', 'getColorStatuses']),
    },
    created() {
        this.getTasks();
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
  @import '../../css/page-section';
</style>
