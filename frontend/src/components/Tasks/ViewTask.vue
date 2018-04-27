<template>    
    <div class="project-task-management page-section">
        <div class="row">
            <div class="col-md-6">
                <div class="header">
                    <h1>
                        Task.title
                        <div class="task-label" :style="'background-color:#e04fcc'">
                            Task.Label.Name
                        </div>
                    </h1>

                    <div class="task planning">
                        <a :href="'#Phase.URL'" :title="'View all tasks in Phase.Name'">Phase.Name</a> > <a href="Milestone.URL" :title="'View all tasks in Milestone.Name'">Milestone.Name</a>
                    </div>

                    <div class="task-status"> 
                        <span class="task-status-box">In Progress</span>
                        <b>Task.ID</b>
                        Created on Task.Create.Date.Time by 
                        <div class="user-info">
                            <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg)' }"></div>
                            <p>user.firstName user.lastName</p>
                        </div>
                        <span class="task-subtasks">Subtasks.Completed of Subtasks.Total Completed</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="flex flex-v-center">
                    <a href="javascript:void(0)" class="btn-rounded btn-auto">{{ message.edit_task }}</a>
                    <router-link to="" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                    <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ button.new_task }}</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    methods: mapActions(['getTaskById']),
    created() {
        this.getTaskById(this.$route.params.id);
    },
    computed: mapGetters({
        currentTask: 'currentTask',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
