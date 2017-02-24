<template>
    <div class="project-task-management page-section">
        <div class="header">
            <h1>Tasks Title</h1>
            <div class="flex flex-v-center">
                <a href="javascript:void(0)" class="btn-rounded btn-auto">Edit Task</a>
                <router-link to="" class="btn-rounded btn-auto danger-bg">Close Task</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">New Task</router-link>
            </div>
        </div>
    </div>
</template>

<script>

export default {

};
</script>


<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .btn-rounded {
        margin-left: 10px;
    }
</style>
