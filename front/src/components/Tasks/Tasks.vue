<template>
    <div>
        <div class="page-section tasks">
            <div class="header">
                <h1>My Tasks</h1>
                <div class="flex">
                    <task-filters></task-filters>
                    <div class="pagination" v-if="count > 0">
                        <span v-for="page in count/tasks.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                    </div>
                </div>
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
        changePage(page) {
            this.getTasks(page);
            this.activePage = page;
        },
    },
    watch: {
        user: function() {
            this.getTasks(this.activePage);
            this.getColorStatuses();
        },
    },
    computed: mapGetters({
        user: 'user',
        tasks: 'tasks',
        count: 'count',
        colorStatuses: 'colorStatuses',
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
  @import '../../css/page-section';
</style>
