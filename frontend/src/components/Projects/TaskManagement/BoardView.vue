<template>
    <vue-perfect-scrollbar class="categories-scroll">
        <div class="board-view">
            {{ tasksByStatus }}
            <div class="flex">
                <div v-for="taskStatus in taskStatuses"
                     :key="taskStatus.id"
                     v-if="tasksByStatuses[taskStatus.id]">
                    <board-tasks-column
                            :tasks="tasksByStatuses[taskStatus.id].items"
                            :totalCount="tasksByStatuses[taskStatus.id].totalItems"
                            :status="taskStatus"/>
                </div>
            </div>
        </div>
    </vue-perfect-scrollbar>
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
