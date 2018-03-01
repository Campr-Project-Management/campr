<template>
    <VuePerfectScrollbar class="categories-scroll">
        <div class="board-view">
            {{ tasksByStatus }}
            <div class="flex">
                <div v-for="taskStatus in taskStatuses"
                     :key="taskStatus.id"
                     v-if="taskStatuses && tasksByStatuses && taskStatus && tasksByStatuses[taskStatus.id]">
                    <board-tasks-column v-bind:tasks="tasksByStatuses[taskStatus.id].items"
                                        v-bind:tasksNumber="tasksByStatuses[taskStatus.id].totalItems"
                                        v-bind:status="taskStatus">
                    </board-tasks-column>
                </div>
            </div>
        </div>
    </VuePerfectScrollbar>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import BoardTasksColumn from './BoardTasksColumn';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';

export default {
    components: {
        BoardTasksColumn,
        VuePerfectScrollbar,
    },
    created() {
        let project = this.$route.params.id;
        this.getTasksByStatuses(project);

        if (!this.$store.state.task.taskStatuses || this.$store.state.task.taskStatuses.length === 0) {
            this.getTaskStatuses();
        }
    },
    mounted() {
    },
    computed: {
        ...mapGetters({
            tasksByStatuses: 'tasksByStatuses',
            taskStatuses: 'taskStatuses',
        }),
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getTasksByStatuses']),
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/_common';
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .board-view {
        padding-top: 20px;
        display: inline-block;
        white-space: nowrap;
    }

    .column {
        margin-right: 10px;
        width: 434px;
    }

    .column-header {
        background: $darkColor;
        padding: 20px;
        margin-bottom: 10px;
    }

    .tasks-scroll {
        margin-bottom: 10px;
    }

    .notification-balloon {
        display: inline-block;
        position: static;
        margin-left: 10px;
    }

    .header .notification-balloon {
        margin-top: 5px
    }

    .categories-scroll {
        width: 100%;
        padding-bottom: 30px;
        position: relative;
        overflow: auto;
    }
</style>

<style lang="scss">
    .categories-scroll {
        .ps__scrollbar-x-rail {
            bottom: auto !important;
            top: 0;
        }
    }
</style>
