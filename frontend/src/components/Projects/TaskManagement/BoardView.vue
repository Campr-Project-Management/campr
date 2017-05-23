<template>
  <div ref="boardViewScroll" class="categories-scroll">
    <div class="board-view">
      {{ tasksByStatus }}
      <div class="flex">
        <div v-for="taskStatus in taskStatuses" v-if="taskStatuses && tasksByStatuses && taskStatus && tasksByStatuses[taskStatus.id]">
          <board-tasks-column v-bind:tasks="tasksByStatuses[taskStatus.id].items" v-bind:tasksNumber="tasksByStatuses[taskStatus.id].totalItems"
            v-bind:status="taskStatus">
          </board-tasks-column>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import BoardTasksColumn from './BoardTasksColumn';
import VueScrollbar from 'vue2-scrollbar';
import Ps from 'perfect-scrollbar';

export default {
    components: {
        BoardTasksColumn,
        VueScrollbar,
    },
    created() {
        let project = this.$route.params.id;
        this.getTasksByStatuses(project);

        if (!this.$store.state.task.taskStatuses || this.$store.state.task.taskStatuses.length === 0) {
            this.getTaskStatuses();
        }
    },
    mounted() {
        Ps.initialize(this.$refs['boardViewScroll']);
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
        max-height: 400px;
        padding-right: 40px;
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
    }
</style>
