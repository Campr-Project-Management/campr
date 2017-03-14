<template>
    <div class="page-section tasks">
        <div class="header">
            <h1>{{ message.recent_tasks }}</h1>
            <div class="full-filters">
                <task-filters></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded">{{ message.all_tasks }}</router-link>
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
    methods: {
        ...mapActions(['getRecentTasks', 'getColorStatuses', 'getProjects']),
        changePage(page) {
            this.getRecentTasks(page);
            this.activePage = page;
        },
    },
    created() {
        if (!this.$store.state.task || this.$store.state.task.items.length === 0) {
            this.getRecentTasks(this.activePage);
        }
        if (!this.$store.state.project || this.$store.state.project.items.length === 0) {
            this.getProjects();
        }
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length === 0) {
            this.getColorStatuses();
        }
    },
    computed: mapGetters({
        tasks: 'tasks',
        count: 'count',
        colorStatuses: 'colorStatuses',
        user: 'user',
    }),
    data() {
        return {
            activePage: 1,
            message: {
                recent_tasks: window.Translator.trans('message.recent_tasks'),
                all_tasks: window.Translator.trans('message.all_tasks'),
                new_task: window.Translator.trans('message.new_task'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/page-section';
</style>
