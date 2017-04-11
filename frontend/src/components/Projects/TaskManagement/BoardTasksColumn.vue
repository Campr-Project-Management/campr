<template>
    <div class="column">
        <div class="column-header flex flex-v-center flex-space-between">
            <span>{{ status.name }}</span>
            <div class="flex">
                <span class="notification-balloon">{{ tasksNumber }}</span>
                <span class="notification-balloon second-bg">+</span>
            </div>
        </div>
        <vue-scrollbar class="tasks-scroll">
            <div>
                <small-task-box v-bind:task="task" v-for="task in tasks"></small-task-box>
                <!--<infinite-loading :on-infinite="onInfinite" ref="infiniteLoading"></infinite-loading>-->
           </div>
        </vue-scrollbar>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import SmallTaskBox from '../../Dashboard/SmallTaskBox';
import VueScrollbar from 'vue2-scrollbar';
import InfiniteLoading from 'vue-infinite-loading';

export default {
    props: ['status', 'tasks', 'tasksNumber'],
    components: {
        SmallTaskBox,
        VueScrollbar,
        InfiniteLoading,
    },
    computed: {
        ...mapGetters({
            tasksByStatuses: 'tasksByStatuses',
        }),
    },
    methods: {
        ...mapActions(['getTasksByStatus']),
        onInfinite() {
            this.getTasksByStatus({
                project: this.project,
                status: this.status.name,
                page: this.page,
            });
            this.$refs.infiniteLoading.$emit('$InfiniteLoading:complete');
        },
        translate(text) {
            return this.translate(text);
        },
    },
    data: function() {
        return {
            'page': 1,
            'project': this.$route.params.id,
        };
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
