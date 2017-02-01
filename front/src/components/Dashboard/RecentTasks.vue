<template>
    <div class="page-section tasks">
        <div class="header">
            <h1>My Recent Tasks</h1>
            <div class="full-filters">
                <task-filters></task-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'tasks'}" class="btn-rounded">View all my Tasks</router-link>
                <div class="pagination" v-if="count > 0">
                    <span v-for="page in count/tasks.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
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
        ...mapActions(['getRecentTasks', 'getColorStatuses']),
        changePage(page) {
            this.getRecentTasks(page);
            this.activePage = page;
        },
    },
    watch: {
        user: function() {
            this.getRecentTasks(this.activePage);
            this.getColorStatuses();
        },
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
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/page-section';
</style>
