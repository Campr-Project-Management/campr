<template>
    <div class="page-section tasks recent-tasks">
        <div class="header">
            <h1>{{ translateText('message.recent_tasks') }}</h1>
            <div class="full-filters">
                <task-filters :updateFilters="applyFilters"></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded btn-auto">{{ translateText('message.all_tasks') }}</router-link>
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
        ...mapActions(['getUserRasciTasks', 'getColorStatuses', 'setFilters']),
        translateText: function(text) {
            return this.translate(text);
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setFilters(filterObj);
            this.getRecentTasksData();
        },
        getRecentTasksData: function() {
            this.getUserRasciTasks({
                queryParams: {
                    page: this.activePage,
                },
            });
        },
    },
    created() {
        this.setFilters({clear: true});
        this.getRecentTasksData();
        this.getColorStatuses();
    },
    computed: mapGetters({
        tasks: 'rasciTasks',
        count: 'count',
        colorStatuses: 'colorStatuses',
        user: 'user',
    }),
    data() {
        return {
            activePage: 1,
        };
    },
};
</script>

<style scoped lang="scss">
</style>
