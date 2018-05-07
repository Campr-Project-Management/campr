<template>
    <div class="project-task-management page-section">
        <!-- /// Tasks List Grid Header /// -->
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{  translate('message.project_tasks') }}</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ translate('message.view_board') }}</router-link>
                <router-link
                        v-if="$can('roles.project_manager|roles.project_sponsor', project)"
                        :to="{name: 'project-task-management-edit-labels'}"
                        class="btn-rounded btn-auto">{{ translate('message.edit_labels') }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ translate('message.new_task') }}</router-link>
            </div>
        </div>
        <!-- /// End Tasks List Header /// -->

        <!-- /// Tasks Filters /// -->
        <div class="flex">
            <input-field
                    v-model="criteria.searchString"
                    type="text"
                    :label="translate('label.search_for_tasks')"
                    class="search"/>
            <dropdown
                    ref="assignee"
                    :selectedValue="selectAssignee"
                    :title="translate('message.assignee')"
                    :options="projectUsersForSelect"/>
            <dropdown
                    ref="statuses"
                    :selectedValue="selectStatus"
                    :title="translate('label.status')"
                    :options="statusesOptions"/>
            <dropdown
                    ref="colorStatuses"
                    :selectedValue="selectColorStatus"
                    :title="translate('message.condition')"
                    :options="colorStatusesOptions"/>
            <a @click="filterTasks" class="btn-rounded btn-auto">{{ translate('button.show_results') }}</a>
            <a @click="clearFilters" class="btn-rounded btn-auto second-bg">{{ translate('button.clear_filters') }}</a>
        </div>
        <!-- /// End Tasks Filters /// -->

        <!-- /// Tasks List /// -->
        <div class="tasks">
            <template v-if="tasks.nbItems > 0">
                <div class="grid-view">
                    <task-box
                            user="user"
                            :color-statuses="colorStatuses"
                            v-for="task in tasks.items"
                            :task="task"
                            :key="task.id">
                    </task-box>
                </div>
                <pagination
                        v-model.number="page"
                        :number-of-pages="tasks.nbPages"
                        :show-description="false"
                        @input="onPageChange"/>
            </template>
            <div class="no-results" v-else-if="tasks.nbItems != null">{{ translate('message.no_results') }}</div>
        </div>
        <!-- /// End Tasks List /// -->
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';
import TaskBox from '../../Tasks/TaskBox';
import Pagination from '../../_common/Pagination';

export default {
    name: 'list-grid',
    components: {
        InputField,
        Dropdown,
        TaskBox,
        Pagination,
    },
    created() {
        this.setFilters({clear: true});

        if (!this.taskStatuses || !this.taskStatuses.length) {
            this.getTaskStatuses();
        }

        if (!this.project) {
            this.getProjectById(this.$route.params.id);
        }

        let project = this.$route.params.id;
        this.getProjectUsers({id: project});
        this.getColorStatuses();
        this.getAllTasksGrid({project, page: 1});
    },
    computed: {
        ...mapGetters([
            'project',
            'taskStatuses',
            'allTasks',
            'projectUsersForSelect',
            'colorStatusesForSelect',
            'colorStatuses',
        ]),
        colorStatusesOptions() {
            return this.colorStatusesForSelect.filter((status) => status.key);
        },
        statusesOptions() {
            return this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
        },
        tasks() {
            if (_.isArray(this.allTasks)) {
                return {
                    totalItems: 0,
                };
            }

            return this.allTasks;
        },
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getTaskStatuses',
            'setFilters',
            'getAllTasksGrid',
            'getProjectUsers',
            'getColorStatuses',
        ]),
        selectColorStatus(colorStatus) {
            this.criteria.colorStatus = colorStatus;
        },
        selectAssignee(assignee) {
            this.criteria.assignee = assignee;
        },
        selectStatus(status) {
            this.criteria.status = status;
        },
        filterTasks: function() {
            const project = this.$route.params.id;

            this.page = 1;
            this.setFilters(this.criteria);
            this.getAllTasksGrid({project, page: this.page});
        },
        clearFilters: function() {
            const project = this.$route.params.id;
            this.criteria = {};
            this.$refs.assignee.resetCustomTitle();
            if (this.$refs.statuses) {
                this.$refs.statuses.resetCustomTitle();
            }
            this.page = 1;
            this.$refs.colorStatuses.resetCustomTitle();
            this.setFilters({clear: true});
            this.getAllTasksGrid({project, page: 1});
        },
        onPageChange() {
            let project = this.$route.params.id;
            this.getAllTasksGrid({project, page: this.page});
        },
    },
    data() {
        return {
            criteria: {},
            tasksPerPage: 8,
            page: 1,
        };
    },
};
</script>

<style scoped lang="scss">
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
