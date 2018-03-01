<template>
    <div class="project-task-management page-section">
        <!-- /// Tasks List Grid Header /// -->
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{  translateText('message.project_tasks') }}</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ translateText('message.view_board') }}</router-link>
                <router-link :to="{name: 'project-task-management-edit-labels'}" class="btn-rounded btn-auto">{{ translateText('message.edit_labels') }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.new_task') }}</router-link>
            </div>
        </div>
        <!-- /// End Tasks List Header /// -->

        <!-- /// Tasks Filters /// -->
        <div class="flex">
            <input-field
                    v-model="searchString"
                    :content="searchString"
                    type="text"
                    v-bind:label="translateText('label.search_for_tasks')"
                    class="search"/>
            <dropdown
                    ref="assignee"
                    :selectedValue="selectAssignee"
                    :title="translateText('message.asignee')"
                    :options="users"/>
            <dropdown
                    ref="statuses"
                    :selectedValue="selectStatus"
                    :title="translateText('label.status')"
                    :options="statusesLabel"/>
            <dropdown
                    ref="conditions"
                    :selectedValue="selectCondition"
                    :title="translateText('message.condition')"
                    :options="conditions"/>
            <a @click="filterTasks" class="btn-rounded btn-auto">{{ translateText('button.show_results') }}</a>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
        <!-- /// End Tasks Filters /// -->

        <!-- /// Tasks List /// -->
        <div class="tasks">
            <GridView></GridView>
        </div>
        <!-- /// End Tasks List /// -->
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import GridView from './GridView';
import {mapActions, mapGetters} from 'vuex';
import Vue from 'vue';

export default {
    components: {
        InputField,
        Dropdown,
        GridView,
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
            return this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getTasksByStatus', 'setFilters', 'resetTasks', 'getAllTasksGrid']),
        translateText(text) {
            return this.translate(text);
        },
        getUsers: function(statusId) {
            Vue.http.get(Routing.generate('app_api_project_project_users', {id: statusId})).then((response) => {
                this.users = response.data.items.map((item) => ({label: item.userFullName, key: item.user}));
            }, (response) => {
            });
        },
        getConditions: function() {
            Vue.http
            .get(Routing.generate('app_api_color_status_list')).then((response) => {
                if (response.status === 200) {
                    this.conditions = response.data.map((item) => ({label: item.name, key: item.id}));
                }
            }, (response) => {
            });
        },
        selectCondition: function(condition) {
            this.conditionFilter = condition;
        },
        selectAssignee: function(assignee) {
            this.assigneeFilter = assignee;
        },
        selectStatus: function(status) {
            this.statusFilter = status;
        },
        filterTasks: function() {
            const project = this.$route.params.id;

            const filters = {
                condition: this.conditionFilter ? this.conditionFilter : undefined,
                assignee: this.assigneeFilter ? this.assigneeFilter : undefined,
                searchString: this.searchString ? this.searchString : undefined,
                status: this.statusFilter ? this.statusFilter : undefined,
            };

            this.setFilters(filters);
            this.resetTasks(project);
            this.getAllTasksGrid({project, page: 1});
        },
        clearFilters: function() {
            const project = this.$route.params.id;
            this.searchString = null;
            this.conditionFilter = null;
            this.assigneeFilter = null;
            this.statusFilter = null;
            this.$refs.assignee.resetCustomTitle();
            if (this.$refs.statuses) {
                this.$refs.statuses.resetCustomTitle();
            }
            this.$refs.conditions.resetCustomTitle();
            this.setFilters({clear: true});
            this.resetTasks(project);
            this.getAllTasksGrid({project, page: 1});
        },
    },
    data() {
        return {
            count: 2,
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
        width: 25%;
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

    @media (max-width:1500px) {
        .search {
            width: auto;
        }
    }
</style>
