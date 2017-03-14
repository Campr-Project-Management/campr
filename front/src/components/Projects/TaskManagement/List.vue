<template>
    <div class="project-task-management page-section">
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>Project Tasks</h1>
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
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="!boardView" @click="boardView=!boardView">View Board</a>
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="boardView" @click="boardView=!boardView">View Grid</a>
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto">Edit Labels</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">New Task</router-link>
            </div>
        </div>
        <div class="flex">
            <input-field type="text" label="Search for Tasks" class="search"></input-field>
            <dropdown title="Asignee" options=""></dropdown>
            <dropdown title="Status" options=""></dropdown>
            <dropdown title="Milestone" options=""></dropdown>
            <dropdown title="Filter By" options=""></dropdown>
            <a class="btn-rounded btn-auto">Show Results</a>
        </div>

        <div class="tasks">
            <div class="grid-view" v-show="!boardView">
                <div class="flex flex-row">
                    <task-box v-bind:task="task" user="user" v-for="task in tasks"></task-box>
                </div>
                <div class="pagination flex flex-center" v-if="count > 0">
                    <span v-for="page in [1,2]" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
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

    .task-box {
        margin-left: 0;
        margin-right: 0;
    }

    .board-view {
        display: inline-block;
        white-space: nowrap;
    }
</style>
