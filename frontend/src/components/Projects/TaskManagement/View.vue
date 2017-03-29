<template>
    <div class="project-task-management page-section">
        <div class="header">
            <h1>Tasks Title</h1>
            <div class="flex flex-v-center">
                <a href="javascript:void(0)" class="btn-rounded btn-auto">{{ message.edit_task }}</a>
                <router-link to="" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ button.new_task }}</router-link>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            message: {
                edit_task: Translator.trans('message.edit_task'),
                close_task: Translator.trans('message.close_task'),
            },
            button: {
                new_task: Translator.trans('button.new_task'),
            },
        };
    },
};
</script>


<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';

    .btn-rounded {
        margin-left: 10px;
    }
</style>
