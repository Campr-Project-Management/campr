<template>
    <div class="project-task-management page-section">
        <!-- /// Tasks List Header /// -->
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ message.project_tasks }}</h1>
                <!--<div class="flex flex-v-center" v-if="!boardView">
                    <a class="btn-rounded btn-auto btn-empty btn-notification active">
                        <span>Open</span>
                        <span class="notification-balloon">12</span>
                    </a>
                    <a class="btn-rounded btn-auto btn-empty btn-notification">
                        <span>Closed</span>
                        <span class="notification-balloon">12</span>
                    </a>
                    <a class="btn-rounded btn-auto btn-empty btn-notification">
                        <span>All</span>
                        <span class="notification-balloon">12</span>
                    </a>
                </div>-->
            </div>
            <div class="flex flex-v-center">
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="!boardView" @click="boardView=!boardView">{{ message.view_board }}</a>
                <a href="javascript:void(0)" class="btn-rounded btn-auto" v-show="boardView" @click="boardView=!boardView">{{ message.view_grid }}</a>
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto">{{ message.edit_labels }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ message.new_task }}</router-link>
            </div>
        </div>
        <!-- /// End Tasks List Header /// -->

        <!-- /// Tasks Filters /// -->
        <div class="flex">
            <input-field v-model="searchString" type="text" v-bind:label="label.search_for_tasks" class="search"></input-field>
            <dropdown :selectedValue="selectAsignee" title="Asignee" :options="users"></dropdown>
            <dropdown :selectedValue="selectStatus" v-if="!boardView" title="Status" :options="statusesLabel"></dropdown>
            <dropdown :selectedValue="selectCondition" title="Condition" :options="conditions"></dropdown>
            <!--To be added after disscusion about milestones-->
            <!--<dropdown title="Milestone" options=""></dropdown>-->
            <a @click="filterTasks" class="btn-rounded btn-auto">{{ button.show_results }}</a>
        </div>
        <!-- /// End Tasks Filters /// -->

        <!-- /// Tasks List /// -->
        <div class="tasks">
            <BoardView v-show="boardView"></BoardView>
            <GridView v-show="!boardView"></GridView>
        </div>
        <!-- /// End Tasks List /// -->
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import GridView from './GridView';
import BoardView from './BoardView';
import {mapActions, mapGetters} from 'vuex';
import Vue from 'vue';

export default {
    components: {
        InputField,
        Dropdown,
        GridView,
        BoardView,
    },
    created() {
        if (!this.$store.state.task.taskStatuses || this.$store.state.task.taskStatuses.length === 0) {
            this.getTaskStatuses();
        }
        let project = this.$route.params.id;
        this.getUsers(project);
        this.getConditions();
    },
    computed: {
        ...mapGetters({
            taskStatuses: 'taskStatuses',
        }),
        statusesLabel: function() {
            let statuses = this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            statuses.unshift({label: 'Status', key: null});
            return statuses;
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getTasksByStatus', 'setFilters', 'resetTasks', 'getAllTasksGrid']),
        getUsers: function(statusId) {
            Vue.http
            .get(Routing.generate('app_api_project_project_users', {id: statusId})).then((response) => {
                if (response.status === 200) {
                    this.users = response.data.map((item) => ({label: item.userFullName, key: item.id}));
                    this.users.unshift({label: 'Asignee', key: null});
                }
            }, (response) => {
            });
        },
        getConditions: function() {
            Vue.http
            .get(Routing.generate('app_api_color_status_list')).then((response) => {
                if (response.status === 200) {
                    this.conditions = response.data.map((item) => ({label: item.name, key: item.id}));
                    this.conditions.unshift({label: 'Condition', key: null});
                }
            }, (response) => {
            });
        },
        selectCondition: function(condition) {
            this.conditionFilter = condition;
        },
        selectAsignee: function(asignee) {
            this.asigneeFilter = asignee;
        },
        selectStatus: function(status) {
            this.statusFilter = status;
        },
        setSearchString: function(search) {
            this.searchString = search;
        },
        filterTasks: function() {
            const project = this.$route.params.id;

            const filters = {};
            filters.condition = this.conditionFilter ? this.conditionFilter : undefined;
            filters.asignee = this.asigneeFilter ? this.asigneeFilter : undefined;
            filters.searchString = this.searchString ? this.searchString : undefined;
            filters.status = this.statusFilter ? this.statusFilter : undefined;

            this.setFilters(filters);
            this.resetTasks(project);
            this.getAllTasksGrid({project, page: 1});
        },
    },
    data() {
        return {
            message: {
                project_tasks: this.translate('message.project_tasks'),
                view_board: this.translate('message.view_board'),
                view_grid: this.translate('message.view_grid'),
                edit_labels: this.translate('message.edit_labels'),
                new_task: this.translate('message.new_task'),
            },
            label: {
                search_for_tasks: this.translate('label.search_for_tasks'),
            },
            button: {
                show_results: this.translate('button.show_results'),
            },
            count: 2,
            boardView: true,
            users: [],
            conditions: [],
            conditionFilter: null,
            asigneeFilter: null,
            statusFilter: null,
            searchString: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_common';
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .vue-scrollbar__scrollbar-vertical {
        padding: 30px 10px !important;
        width: 30px !important;
    }

    .vue-scrollbar__scrollbar-vertical:before, .vue-scrollbar__scrollbar-vertical:after {
        left: 9px;
    }

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

    .categories-scroll {
        width: 100%;
        padding-bottom: 30px;
    }

    .task-box {
        margin-left: 0;
        margin-right: 0;
    }
</style>
