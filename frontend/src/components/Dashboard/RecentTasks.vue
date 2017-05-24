<template>
    <div class="page-section tasks recent-tasks">
        <div class="header">
            <h1>{{ message.recent_tasks }}</h1>
            <div class="full-filters">
                <task-filters></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded btn-auto">{{ message.all_tasks }}</router-link>
            </div>
        </div>
        <div class="grid-view">
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
        if (!this.$store.state.task || (this.$store.state.task.items && this.$store.state.task.items.length === 0)) {
            this.getRecentTasks(this.activePage);
        }
        if (!this.$store.state.project || (this.$store.state.project.items && this.$store.state.project.items.length === 0)) {
            this.getProjects();
        }
        if (!this.$store.state.colorStatus || (this.$store.state.colorStatus.items && this.$store.state.colorStatus.items.length === 0)) {
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
                recent_tasks: Translator.trans('message.recent_tasks'),
                all_tasks: Translator.trans('message.all_tasks'),
                new_task: Translator.trans('message.new_task'),
            },
        };
    },
};
</script>

<style scoped lang="scss">
</style>
