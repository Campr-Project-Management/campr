<template>
    <div class="column">
        <div class="column-header flex flex-v-center flex-space-between">
            <span>{{ translateText(status.name) }}</span>
            <div class="flex">
                <span class="notification-balloon">{{ tasksByStatuses[status.id].totalItems }}</span>
                <span class="notification-balloon second-bg">+</span>
            </div>
        </div>
        <div class="tasks-scroll" infinite-wrapper>
            <div>
                <small-task-box v-if="tasksByStatuses[status.id]"
                    v-bind:task="task"
                    v-for="(task, index) in tasksByStatuses[status.id].items"
                    :key="index"></small-task-box>
                <infinite-loading :status="status.id" :on-infinite="onInfinite" v-bind:ref="'infiniteLoading' + status.id"></infinite-loading>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import SmallTaskBox from '../../Dashboard/SmallTaskBox';
import InfiniteLoading from 'vue-infinite-loading';

export default {
    props: ['status'],
    components: {
        SmallTaskBox,
        InfiniteLoading,
    },
    computed: {
        ...mapGetters({
            tasksByStatuses: 'tasksByStatuses',
        }),
    },
    mounted() {
    },
    methods: {
        ...mapActions(['getTasksByStatus']),
        onInfinite() {
            const infiniteLoadingRef = 'infiniteLoading' + this.status.id;
            this.page++;
            this.getTasksByStatus({
                project: this.project,
                status: this.status.name,
                statusId: this.status.id,
                page: this.page,
                callback: () => {
                    if (this.$refs[infiniteLoadingRef]) {
                        this.$refs[infiniteLoadingRef].$emit('$InfiniteLoading:loaded');
                        if (this.tasksByStatuses[this.status.id].items.length >= this.tasksByStatuses[this.status.id].totalItems) {
                            // every task has been loaded in the store (length of array equals to totalItems)
                            this.$refs[infiniteLoadingRef].$emit('$InfiniteLoading:complete');
                        }
                    }
                },
            });
        },
        translateText(text) {
            return this.translate(text);
        },
    },
    data: function() {
        return {
            page: 1,
            project: this.$route.params.id,
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
        padding-right: 40px;
        margin-bottom: 10px;
        position: relative;
        overflow: auto;
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
