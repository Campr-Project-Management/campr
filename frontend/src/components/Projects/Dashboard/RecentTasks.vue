<template>
    <div class="widget-content">
        <h4 class="widget-title">{{ translate('message.project_summary') }}</h4>
        <div>
            <small-task-box
                    v-for="(task, index) in tasks"
                    :key="index"
                    v-bind:task="task"/>
        </div>
        <div class="margintop20 buttons">
            <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-md btn-empty btn-auto">
                {{ translate('button.view_tasks_board') }}
            </router-link>
            <router-link :to="{name: 'project-task-management-list-grid'}"
                         class="btn-rounded btn-md btn-empty btn-auto">{{ translate('button.view_tasks_grid') }}
            </router-link>
        </div>
    </div>
</template>

<script>
    import SmallTaskBox from '../../Dashboard/SmallTaskBox';

    export default {
        name: 'project-dashboard-recent-tasks',
        props: {
            tasks: {
                type: Array,
                default: () => [],
                required: true,
            },
        },
        components: {
            SmallTaskBox,
        },
    };
</script>
