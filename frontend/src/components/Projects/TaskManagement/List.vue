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
                    v-model="filters.searchString"
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
            <a @click="clearFilters" class="btn-rounded btn-auto second-bg">{{ translate('button.clear_filters') }}</a>
        </div>
        <!-- /// End Tasks Filters /// -->

        <!-- /// Tasks List /// -->
        <div class="tasks">
            <scrollbar class="categories-scroll customScrollbar" :suppress-scroll-y="true">
                <div class="board-view">
                    {{ tasksByStatus }}
                    <div class="flex" v-if="taskStatuses.length > 0">
                        <board-tasks-column
                                v-for="status in taskStatuses"
                                :key="status.id"
                                :status="status"
                                :criteria="criteria"/>
                    </div>
                </div>
            </scrollbar>
        </div>
        <!-- /// End Tasks List /// -->
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import Dropdown from '../../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';
import BoardTasksColumn from './BoardTasksColumn';

export default {
    name: 'board-view',
    components: {
        InputField,
        Dropdown,
        BoardTasksColumn,
    },
    created() {
        this.setFilters({clear: true});

        if (!this.taskStatuses || !this.taskStatuses.length) {
            this.getTaskStatuses();
        }

        if (!this.project) {
            this.getProjectById(this.$route.params.id);
        }

        this.getProjectUsers({id: this.$route.params.id});
        this.getColorStatuses();
    },
    computed: {
        ...mapGetters([
            'project',
            'taskStatuses',
            'projectUsersForSelect',
            'colorStatusesForSelect',
            'colorStatuses',
        ]),
        colorStatusesOptions() {
            return this.colorStatusesForSelect.filter((status) => status.key);
        },
    },
    methods: {
        ...mapActions([
            'getProjectById',
            'getTaskStatuses',
            'setFilters',
            'getProjectUsers',
            'getColorStatuses',
        ]),
        selectColorStatus(colorStatus) {
            this.filters.colorStatus = colorStatus;
        },
        selectAssignee(assignee) {
            this.filters.assignee = assignee;
        },
        clearFilters() {
            this.filters = {
                colorStatus: null,
                assignee: null,
                searchString: null,
            };

            this.$refs.assignee.resetCustomTitle();
            if (this.$refs.statuses) {
                this.$refs.statuses.resetCustomTitle();
            }
            this.$refs.colorStatus.resetCustomTitle();
            this.criteria = this.filters;
        },
        filterTasks() {
            this.criteria = Object.assign({}, this.filters);
        },
    },
    data() {
        return {
            users: [],
            filters: {
                colorStatus: null,
                assignee: null,
                searchString: null,
            },
            criteria: {
                colorStatus: null,
                assignee: null,
                searchString: null,
            },
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
        height: calc(100vh - 222px);
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
        height: 100%;
        padding-top: 20px;
        overflow-y: hidden !important;
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
        display: inline-block;
        white-space: nowrap;
        min-height: 100%;
    }

    .notification-balloon {
        display: inline-block;
        position: static;
        margin-left: 10px;
    }

    .header .notification-balloon {
        margin-top: 5px
    }
</style>

<style lang="scss">
    .categories-scroll {
        .ps__scrollbar-x-rail {
            bottom: auto !important;
            top: 0;
        }
        > .ps__scrollbar-y-rail {
            visibility: hidden;
        }
    }
</style>
