<template>
    <div class="column">
        <div class="column-header flex flex-v-center flex-space-between">
            <span>{{ translate(status.name) }}</span>
            <div class="flex">
                <span class="notification-balloon">{{ count }}</span>
            </div>
        </div>
        <scrollbar class="tasks-scroll" @ps-y-reach-end="onScrollEnd">
            <small-task-box
                    :task="task"
                    v-for="(task, index) in tasks"
                    :key="task.id + '_' + index"/>

            <div v-if="showLoading" class="loading-more">{{ translate('message.loading_more_tasks') }}</div>
        </scrollbar>
    </div>
</template>

<script>
import SmallTaskBox from '../../Dashboard/SmallTaskBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    name: 'board-tasks-column',
    props: {
        status: {
            type: Object,
            required: true,
        },
        criteria: {
            type: Object,
            required: false,
            default: () => {},
        },
    },
    created() {
        this.loadMore();
    },
    components: {
        SmallTaskBox,
    },
    computed: {
        ...mapGetters([
            'tasksByStatus',
        ]),
        tasks() {
            let tasks = this.tasksByStatus(this.status.id);
            if (!tasks.items) {
                return [];
            }

            return tasks.items;
        },
        count() {
            let tasks = this.tasksByStatus(this.status.id);
            if (!tasks.nbItems) {
                return 0;
            }

            return tasks.nbItems;
        },
        numberOfPages() {
            let tasks = this.tasksByStatus(this.status.id);
            if (!tasks.nbPages) {
                return 0;
            }

            return tasks.nbPages;
        },
        showLoading() {
            return this.loading && this.count > 0;
        },
    },
    methods: {
        ...mapActions([
            'getTasksByStatus',
            'resetTasks',
        ]),
        getTasks() {
            let project = this.$route.params.id;
            let criteria = Object.assign({}, this.criteria, {
                status: this.status.id,
            });

            this.getTasksByStatus({project, page: this.page, status: this.status.id, criteria}).then(() => {
                this.loading = false;
            });
        },
        loadMore() {
            if (this.page && this.page >= this.numberOfPages) {
                return;
            }

            this.page++;
            this.loading = true;
            this.getTasks();
        },
        onScrollEnd(evt) {
            if (this.loading) {
                return;
            }

            this.loadMore();
        },
    },
    watch: {
        criteria: {
            handler(value) {
                this.resetTasks();
                this.page = 0;
                this.loadMore();
            },
            deep: true,
        },
    },
    data() {
        return {
            page: 0,
            loading: false,
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .board-view {
        display: inline-block;
        white-space: nowrap;
        height: 100vh;
    }

    .column {
        margin-right: 10px;
        width: 394px;
        height: calc(100vh - 240px);
        overflow: hidden;
    }

    .column-header {
        background: $darkColor;
        padding: 20px;
        margin-bottom: 10px;
    }

    .tasks-scroll {
        margin-bottom: 10px;
        height: calc(100% - 100px);
    }

    .notification-balloon {
        display: inline-block;
        position: static;
        margin-left: 10px;
    }

    .header .notification-balloon {
        margin-top: 5px
    }

    .loading-more {
        text-align: center;
        padding: 1em 0 1em;
        color: $middleColor;
    }
</style>

<style lang="scss">
    .tasks-scroll {
        .ps__scrollbar-y-rail {
            visibility: visible;
            z-index: 100;
        }
    }
</style>
