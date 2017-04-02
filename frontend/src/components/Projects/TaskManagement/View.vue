<template>
    <div class="project-task-management page-section">
        <div class="row">
            <div class="col-md-6">

               <!-- /// Task Title and Label /// -->
                <div class="header">
                    <h1>Build Self-Landing Rocket Legs</h1> 

                    <div class="task-label" :style="'background-color:#e04fcc'">
                        High Priority
                    </div>              
                </div>
                <!-- /// End Task Title and Label /// -->

                <!-- /// Task Status /// -->
                <div class="task-status table">
                    <div class="cell">
                        <div class="task-status-box">In Progress</div>
                    </div>
                    <div class="cell">
                        <div class="task-status-info">
                            <b>#244</b>
                            Created on 25.02.2017 by 
                            <img class="user-avatar" src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                            <b>Nelson</b> <b>Carr</b>
                            <span class="task-subtasks">&nbsp;&nbsp;|&nbsp;&nbsp; 1 of 3 Subtasks Completed</span>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status /// -->

                <hr class="double">
                
                <!-- /// Task Description /// -->
                <div class="task-description">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas luctus venenatis lorem, vel semper enim vehicula aliquet. Donec eget egestas augue. Nulla cursus purus lectus, et imperdiet nisl varius quis. Nulla eget augue sed sapien sodales ultrices. Donec et purus neque. Etiam venenatis ut nulla eget vestibulum. Maecenas a eros at erat aliquet pellentesque. Suspendisse scelerisque velit vulputate tortor rutrum, in pellentesque ante condimentum. Vivamus lacinia diam ut neque tempor dictum. Nullam nec rutrum sem, ac maximus odio.</p>

                    <p><b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Maecenas luctus venenatis lorem, vel semper enim vehicula aliquet. Donec eget egestas augue. Nulla cursus purus lectus, et imperdiet nisl varius quis. Nulla eget augue sed sapien sodales ultrices. Donec et purus neque. Etiam venenatis ut nulla eget vestibulum. Maecenas a eros at erat aliquet pellentesque. Suspendisse scelerisque velit vulputate tortor rutrum, in pellentesque ante condimentum. Vivamus lacinia diam ut neque tempor dictum. Nullam nec rutrum sem, ac maximus odio.</p>
                </div>
                <!-- /// End Task Description /// -->

                <!-- /// End Subtasks /// -->
                <div class="subtasks">
                    <div class="checkbox-input clearfix">
                        <input id="subtask1" type="checkbox" name="" value="1" checked>
                        <label for="subtask1">Curabitur lacinia dictum dui ac malesuada</label>
                    </div>
                    <div class="checkbox-input clearfix">
                        <input id="subtask2" type="checkbox" name="" value="0">
                        <label for="subtask2">Duis euismod ipsum eu luctus varius vestibulum auctor, odio id molestie gravida</label>
                    </div>
                    <div class="checkbox-input clearfix">
                        <input id="subtask3" type="checkbox" name="" value="0">
                        <label for="subtask3">Suspendisse lobortis suscipit aliquet. Aliquam lacinia, nunc a lacinia commodo, felis dui mollis erat, cursus fermentum nisl eros a libero. Maecenas tristique
et sapien quis faucibus.</label>
                    </div>
                </div>
                <!-- /// End Subtasks /// -->

                <hr class="double">

                <!-- /// Task History /// -->
                <div class="task-history">
                    <div class="task-comment">
                    </div>
                </div>
                <!-- /// End Task History /// -->
            </div>

            <div class="col-md-6">
                <div class="header-buttons">
                    <a href="javascript:void(0)" class="btn-rounded btn-auto">{{ message.edit_task }}</a>
                    <router-link to="" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                    <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">{{ button.new_task }}</router-link>
                </div>
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
    @import '../../../css/_mixins';

    .table {
        display: table;
        width: 100%;
        height: 100%;
    }

    .cell {
        display: table-cell;
        vertical-align: middle;
    }

    .btn-rounded {
        margin-left: 10px;
    }

    .page-section {
        .header {
            justify-content: flex-start;
            align-items: center;

            .task-label {
                margin-left: 20px;
                @include border-radius(3px);
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: $white;
                height: 30px;
                padding: 0 30px;
                line-height: 31px;
            }
        }
    }

    .header-buttons {
        margin: 20px 0;
        text-align: right;

        a {
            margin-left: 20px;
        }
    }

    .task-status {
        text-transform: uppercase;
        letter-spacing: 0.1em;

        .task-status-box {
            background-color: $middleColor;
            color: $lighterColor;
            @include border-radius(3px);
            font-size: 10px;
            padding: 0 30px;
            line-height: 31px;
            margin-right: 10px;
            display: inline-block;
            white-space: nowrap;
        }

        .task-status-info {
            padding-left: 10px;

            .user-avatar {
                width: 30px;
                height: 30px;
                @include border-radius(50%);
                margin: 0 10px;  
                display: inline-block;
            }

            b {
                display: inline-block;
            }
        }
    }

    p {
        margin-bottom: 20px;
    }

    .subtasks {
        .checkbox-input {
            margin-bottom: 20px;

            label {
                position: relative;
                font-size: 12px;
                text-transform: none;
                padding-left: 30px;

                &:before {
                    position: absolute;
                    left: 0;
                }
            }

            &:last-child {
                margin-bottom: 0;
            }
        }
    }
</style>
