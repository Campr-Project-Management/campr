<template>
    <div class="project-task-management page-section">
        <!-- /// Tasks List Header /// -->
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ translate('message.project_tasks') }}</h1>
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
                <router-link :to="{name: 'project-task-management-list-grid'}" class="btn-rounded btn-auto">{{ translate('message.view_grid') }}</router-link>
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
                    :content="searchString"
                    type="text"
                    :label="translate('label.search_for_tasks')"
                    class="search"/>
            <dropdown
                    ref="assignee"
                    :selectedValue="selectAssignee"
                    :title="translate('message.assignee')"
                    :options="projectUsersForSelect"/>
            <dropdown
                    ref="colorStatus"
                    :selectedValue="selectColorStatus"
                    :title="translate('message.condition')"
                    :options="colorStatusesOptions"/>
            <!--To be added after disscusion about milestones-->
            <!--<dropdown title="Milestone" options=""></dropdown>-->
            <a @click="filterTasks" class="btn-rounded btn-auto">{{ translate('button.show_results') }}</a>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translate('button.clear_filters') }}</a>
        </div>
        <!-- /// End Tasks Filters /// -->

        <!-- /// Tasks List /// -->
        <div class="tasks">
            <scrollbar class="categories-scroll" v-if="tasks.nbItems > 0">
                <div class="board-view">
                    {{ tasksByStatus }}
                    <div class="flex">
                        <div v-for="status in taskStatuses"
                             :key="status.id">
                            <board-tasks-column
                                    :tasks="tasks.items[status.id].items"
                                    :count="tasks.items[status.id].nbItems"
                                    v-if="tasks.items && tasks.items[status.id] && tasks.items[status.id].items && tasks.items[status.id].items.length > 0"
                                    :status="status"/>
                        </div>
                    </div>
                </div>
                <pagination
                        v-model.number="page"
                        :number-of-pages="numberOfPages"
                        :show-description="false"
                        @input="onPageChange"/>
            </scrollbar>
            <div class="no-results" v-else-if="tasks.nbItems != null">{{ translate('message.no_results') }}</div>
        </div>
        <!-- /// End Tasks List /// -->
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';
import BoardTasksColumn from './BoardTasksColumn';
import Pagination from '../../_common/Pagination';

export default {
    name: 'board',
    components: {
        InputField,
        Dropdown,
        BoardTasksColumn,
        Pagination,
    },
    created() {
        this.setFilters({clear: true});

        if (!this.taskStatuses || !this.taskStatuses.length) {
            this.getTaskStatuses();
        }

        let project = this.$route.params.id;
        this.getProjectUsers({id: project});
        this.getColorStatuses();
        this.getAllTasksBoard({project, page: 1});
    },
    computed: {
        ...mapGetters([
            'taskStatuses',
            'allTasks',
            'projectUsersForSelect',
            'colorStatusesForSelect',
            'colorStatuses',
        ]),
        colorStatusesOptions() {
            return this.colorStatusesForSelect.filter((status) => status.key);
        },
        tasks() {
            if (_.isArray(this.allTasks)) {
                return {};
            }

            return this.allTasks;
        },
        numberOfPages: function() {
            return this.tasks.nbPages;
        },
    },
    methods: {
        ...mapActions([
            'getTaskStatuses',
            'setFilters',
            'resetTasks',
            'getAllTasksBoard',
            'getProjectUsers',
            'getColorStatuses',
        ]),
        selectColorStatus: function(colorStatus) {
            this.criteria.colorStatus = colorStatus;
        },
        selectAssignee: function(assignee) {
            this.criteria.assignee = assignee;
        },
        selectStatus: function(status) {
            this.criteria.status = status;
        },
        filterTasks: function() {
            const project = this.$route.params.id;

            this.setFilters(Object.assign({}, this.criteria));
            this.getAllTasksBoard({project, page: 1});
        },
        clearFilters: function() {
            const project = this.$route.params.id;
            this.criteria = {};
            this.$refs.assignee.resetCustomTitle();
            if (this.$refs.statuses) {
                this.$refs.statuses.resetCustomTitle();
            }
            this.$refs.colorStatus.resetCustomTitle();
            this.setFilters({clear: true});
            this.getAllTasksBoard({project, page: 1});
        },
        onPageChange() {
            let project = this.$route.params.id;
            this.getAllTasksBoard({project, page: this.page});
        },
    },
    data() {
        return {
            count: 2,
            users: [],
            criteria: {},
            page: 1,
            tasksPerPage: 8,
        };
    },
};
</script>

<style scoped lang="scss">
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

    .board-view {
        padding-top: 20px;
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
        position: relative;
        overflow: auto;
    }
</style>

<style lang="scss">
    .categories-scroll {
        .ps__scrollbar-x-rail {
            bottom: auto !important;
            top: 0;
        }
    }
</style>
