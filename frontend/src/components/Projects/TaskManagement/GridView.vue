<template>
    <div>
        <div class="grid-view" v-if="allTasks">
            <task-box
                    :task="task"
                    user="user"
                    :colorStatuses="colorStatuses"
                    v-for="task in allTasks.items"
                    :key="task.id">
            </task-box>
        </div>
        <div class="pagination flex flex-center" v-if="allTasks && allTasks.totalItems > 0">
            <span
                    v-for="page in pages"
                    :key="page"
                    v-bind:class="{'active': page === activePage}"
                    @click="getTaskPerPage(page)">{{ page }}</span>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import TaskBox from '../../Tasks/TaskBox';

export default {
    components: {
        TaskBox,
    },
    created() {
        if (!this.$store.state.colorStatuses || this.$store.state.colorStatuses.length === 0) {
            this.getColorStatuses();
        }
        if (!this.allTasks.length || this.allTasks.totalMilestones === 0) {
            this.getTaskPerPage(1);
        }
    },
    computed: {
        ...mapGetters({
            colorStatuses: 'colorStatuses',
            allTasks: 'allTasks',
        }),
        pages: function() {
            return Math.ceil(this.allTasks.totalItems/this.tasksPerPage);
        },
    },
    methods: {
        ...mapActions(['getColorStatuses', 'getAllTasksGrid']),
        changePage: function(page) {
            this.activePage = page;
        },
        getTaskPerPage: function(page) {
            const project = this.$route.params.id;
            this.getAllTasksGrid({project, page});
            this.activePage = page;
        },
    },
    data() {
        return {
            activePage: 1,
            tasksPerPage: 8,
        };
    },
};
</script>

<style scoped lang="scss">
</style>
