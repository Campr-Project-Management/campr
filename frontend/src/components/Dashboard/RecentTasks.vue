<template>
    <div class="page-section tasks recent-tasks">
        <div class="header">
            <h1>{{ translate('message.recent_tasks') }}</h1>
            <div class="full-filters">
                <task-filters :updateFilters="applyFilters"></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded btn-auto">{{ translate('message.all_tasks') }}</router-link>
            </div>
        </div>
        <div class="grid-view">
            <small-task-box v-for="task in tasks" :task="task"/>
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
        ...mapActions(['getTasks', 'setFilters']),
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setFilters(filterObj);
            this.getRecentTasksData();
        },
        getRecentTasksData: function() {
            this.getTasks({
                queryParams: {
                    page: this.activePage,
                    userRasci: this.userRasci,
                },
            });
        },
    },
    created() {
        this.setFilters({clear: true});
        this.getRecentTasksData();
    },
    computed: mapGetters([
        'tasks',
        'count',
        'user',
    ]),
    data() {
        return {
            activePage: 1,
            userRasci: true,
        };
    },
};
</script>
