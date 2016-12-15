<template>
    <div class="my-recent tasks">
        <div class="header">
            <h1>My Recent Tasks</h1>
            <task-filters></task-filters>
        </div>
        <div class="content">
            <task-box v-for="task in tasks" v-bind:task="task"></task-box>
        </div>
    </div>
</template>

<script>
import TaskFilters from './TaskFilters';
import TaskBox from '../TaskBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        TaskFilters,
        TaskBox,
    },
    methods: mapActions(['getTasks']),
    created() {
        this.getTasks();
    },
    computed: mapGetters({
        tasks: 'tasks',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/dashboard/my-recent-sections';
</style>
