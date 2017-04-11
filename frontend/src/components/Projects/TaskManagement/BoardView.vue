<template>
    <vue-scrollbar class="categories-scroll">
        <div class="board-view">
            <div class="flex">
            <div class="column" v-for="taskStatus in taskStatuses">
                <div class="column-header flex flex-v-center flex-space-between">
                <span>{{ translateText(taskStatus.name) }}</span>
                <div class="flex">
                    <span class="notification-balloon">12</span>
                    <span class="notification-balloon second-bg">+</span>
                </div>
                </div>
                <vue-scrollbar class="tasks-scroll">
                    <div>
                        <small-task-box v-bind:task="task" v-for="task in taskStatus.tasks"></small-task-box>
                    </div>
                </vue-scrollbar>
            </div>
            </div>
        </div>
    </vue-scrollbar>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import SmallTaskBox from '../../Dashboard/SmallTaskBox';
import VueScrollbar from 'vue2-scrollbar';

export default {
    components: {
        SmallTaskBox,
        VueScrollbar,
    },
    created() {
        if (!this.$store.state.task.taskStatuses || this.$store.state.task.taskStatuses.length === 0) {
            this.getTaskStatuses();
        }
    },
    computed: mapGetters({
        taskStatuses: 'taskStatuses',
    }),
    methods: {
        ...mapActions(['getTaskStatuses']),
        translateText(text) {
            return this.translate(text);
        },
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
