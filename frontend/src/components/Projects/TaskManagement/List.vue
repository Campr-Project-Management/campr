<template>
    <div class="project-task-management page-section">
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ message.project_tasks }}</h1>
                <div class="flex flex-v-center">
                    <a class="btn-rounded btn-auto second-bg btn-md">
                        <span>Open</span>
                        <span class="notification-balloon second-dark-bg">12</span>
                    </a>
                    <a class="btn-rounded btn-auto btn-empty btn-md">
                        <span>Closed</span>
                        <span class="notification-balloon">12</span>
                    </a>
                    <a class="btn-rounded btn-auto btn-empty btn-md">
                        <span>All</span>
                        <span class="notification-balloon">12</span>
                    </a>
                </div>
            </div>
            <div class="flex flex-v-center">
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="!boardView" @click="boardView=!boardView">{{ message.view_board }}</a>
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="boardView" @click="boardView=!boardView">{{ message.view_grid }}</a>
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto">{{ message.edit_labels }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ message.new_task }}</router-link>
            </div>
        </div>
        <div class="flex">
            <input-field type="text" v-bind:label="label.search_for_tasks" class="search"></input-field>
            <dropdown title="Asignee" options=""></dropdown>
            <dropdown title="Status" options=""></dropdown>
            <dropdown title="Milestone" options=""></dropdown>
            <dropdown title="Filter By" options=""></dropdown>
            <a class="btn-rounded btn-auto">{{ button.show_results }}</a>
        </div>

        <div class="tasks">
            <div class="grid-view" v-show="!boardView">
                <task-box v-bind:task="task" user="user" :colorStatuses="colorStatuses" v-for="task in tasks"></task-box>
            </div>
            <div class="pagination flex flex-center" v-if="count > 0">
                <span v-for="page in [1,2]" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
            </div>
            <vue-scrollbar class="categories-scroll">

            <div class="board-view" v-show="boardView">
                <div class="flex">
                    <div class="column" v-for="taskStatus in taskStatuses">
                        <div class="column-header flex flex-v-center flex-space-between">
                            <span>{{ translate(taskStatus.name) }}</span>
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
        </div>
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import TaskBox from '../../Tasks/TaskBox';
import SmallTaskBox from '../../Dashboard/SmallTaskBox';
import VueScrollbar from 'vue2-scrollbar';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        Dropdown,
        TaskBox,
        SmallTaskBox,
        VueScrollbar,
    },
    created() {
        if (!this.$store.state.task.taskStatuses || this.$store.state.task.taskStatuses.length === 0) {
            this.getTaskStatuses();
        }
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length === 0) {
            this.getColorStatuses();
        }
    },
    computed: mapGetters({
        taskStatuses: 'taskStatuses',
        colorStatuses: 'colorStatuses',
    }),
    methods: {
        ...mapActions(['getTaskStatuses', 'getColorStatuses']),
        translate(text) {
            return window.Translator.trans(text);
        },
    },
    data() {
        return {
            message: {
                project_tasks: Translator.trans('message.project_tasks'),
                view_board: Translator.trans('message.view_board'),
                view_grid: Translator.trans('message.view_grid'),
                edit_labels: Translator.trans('message.edit_labels'),
                new_task: Translator.trans('message.new_task'),
            },
            label: {
                search_for_tasks: Translator.trans('label.search_for_tasks'),
            },
            button: {
                show_results: Translator.trans('button.show_results'),
            },
            tasks: [
                {
                    'id': 1,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 33,
                    'phase': 'Phase 1',
                    'milestone': 'Milestone 1.2',
                    'content': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sit amet felis blandit eros dapibus aliquam.',
                    'status': 'todo',
                }, {
                    'id': 2,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 75,
                    'phase': 'Phase 3',
                    'milestone': 'Milestone 3.2',
                    'content': 'Suspendisse tempus efficitur posuere. Phasellus laoreet neque ligula, sed laoreet neque lacinia et.',
                    'status': 'inprogress',
                }, {
                    'id': 3,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 10,
                    'phase': 'Phase 3',
                    'milestone': 'Milestone 3.3',
                    'content': 'Mauris sapien nisi, placerat at elit ut, gravida auctor eros.',
                    'status': 'codereview',
                }, {
                    'id': 4,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 100,
                    'phase': 'Phase 2',
                    'milestone': 'Milestone 2.3.5',
                    'content': '',
                    'status': 'todo',
                }, {
                    'id': 4,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 68,
                    'phase': 'Phase 5',
                    'milestone': 'Milestone 5.1.2',
                    'content': 'Praesent rutrum libero nec lectus ultrices, ac rhoncus risus rhoncus.',
                    'status': 'todo',
                },
            ],
            count: 2,
            boardView: true,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../../css/_common';

    .vue-scrollbar__scrollbar-vertical {
        padding: 30px 10px !important;
        width: 30px !important;
    }

    .vue-scrollbar__scrollbar-vertical:before, .vue-scrollbar__scrollbar-vertical:after {
        left: 9px;
    }
</style>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .search {
        width: 420px;
    }

    .btn-rounded {
        margin-left: 20px;
    }

    .dropdown {
        margin-left: 20px;
    }

    .tasks {
        padding-top: 20px;
        overflow: hidden;
    }

    .notification-balloon {
        display: inline-block;
        position: static;
        margin-left: 10px;
    }

    .header .notification-balloon {
        margin-top: 5px
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

    .categories-scroll {
        width: 100%;
        padding-bottom: 30px;
    }

    .tasks-scroll {
        max-height: 400px;
        padding-right: 40px;
        margin-bottom: 10px;
    }

    .grid-view {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        margin-right: -15px;
        margin-left: -15px;
    }

    .task-box-wrapper{
        width: 25%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .task-box {
        margin-left: 0;
        margin-right: 0;
    }

    .board-view {
        display: inline-block;
        white-space: nowrap;
    }
</style>
