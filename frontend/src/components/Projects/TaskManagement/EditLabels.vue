<template>
    <can role="roles.project_manager|roles.project_sponsor" :subject="project">
        <div class="edit-labels page-section">
            <div class="header">
                <h1>{{ message.edit_task_labels }}</h1>
                <div class="flex flex-v-center">
                    <router-link :to="{name: 'project-task-management-list'}" href="javascript:void(0)"
                                 class="btn-rounded btn-auto">{{ message.view_grid }}
                    </router-link>
                    <router-link :to="{name: 'project-task-management-list'}" class="btn-rounded btn-auto">{{ message.view_board }}
                    </router-link>
                    <router-link :to="{name: 'project-task-management-add-label'}"
                                 class="btn-rounded btn-auto second-bg">{{ message.new_label }}
                    </router-link>
                    <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">
                        {{ message.new_task }}
                    </router-link>
                </div>
            </div>
            <div class="label-list">
                <div v-for="label in labels" class="flex flex-space-between label-row">
                    <div class="flex">
                        <div class="label-icon" :style="{ background: label.color }">{{ label.title }}</div>
                        <div class="description">{{ label.description }}</div>
                        <div class="tasks">{{ label.openWorkPackagesNumber }} {{ message.open_tasks }}</div>
                    </div>
                    <div class="actions">
                        <router-link
                                :to="{name: 'project-task-management-edit-label', params: { id: label.project, labelId: label.id }}">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 28.5 29.8" style="enable-background:new 0 0 28.5 29.8;" xml:space="preserve">
                            <g id="XMLID_1677_">
                              <path id="XMLID_1681_" class="second-fill" d="M5.5,22.8c0,0.1,0,0.3,0.1,0.4c0.1,0.1,0.2,0.1,0.4,0.1c0,0,5-1.2,5-1.2l-4.3-4.3
                                C6.7,17.8,5.5,22.8,5.5,22.8z"/>

                                <rect id="XMLID_1680_" x="19.3" y="6" transform="matrix(-0.7074 0.7069 -0.7069 -0.7074 40.0807 1.5692)" class="second-fill" width="0.8" height="6.1"/>

                                <rect id="XMLID_1679_" x="7.4" y="11.6" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 34.5063 15.0131)" class="second-fill" width="13.5" height="6.1"/>
                              <path id="XMLID_1678_" class="second-fill" d="M22.7,10.4l0.3-0.3c0.7-0.7,0.7-1.9,0-2.7l-1.6-1.6C21,5.5,20.5,5.3,20,5.3
                                c-0.5,0-1,0.2-1.3,0.6l-0.3,0.3L22.7,10.4z"/>
                            </g>
                        </svg>
                        </router-link>
                        <a @click="deleteLabel(label.id)">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                           viewBox="0 0 28.5 29.8" style="enable-background:new 0 0 28.5 29.8;" xml:space="preserve">
                            <g id="XMLID_1682_">
                              <path id="XMLID_1685_" class="danger-fill" d="M22.8,7.6h-5.3V5.8c0-0.2-0.2-0.4-0.4-0.4h-6c-0.2,0-0.4,0.2-0.4,0.4v1.9H6.3
                                C6.1,7.6,5.9,7.8,5.9,8c0,0.2,0.2,0.4,0.4,0.4h1.5V23c0,0.2,0.2,0.4,0.4,0.4h12c0.2,0,0.4-0.2,0.4-0.4V8.4h2.3
                                c0.2,0,0.4-0.2,0.4-0.4C23.2,7.8,23,7.6,22.8,7.6z M11.5,19.3c0,0.2-0.2,0.4-0.4,0.4s-0.4-0.2-0.4-0.4V11c0-0.2,0.2-0.4,0.4-0.4
                                s0.4,0.2,0.4,0.4V19.3z M11.5,6.1h5.3v1.5h-5.3V6.1z M14.5,19.3c0,0.2-0.2,0.4-0.4,0.4s-0.4-0.2-0.4-0.4V11c0-0.2,0.2-0.4,0.4-0.4
                                s0.4,0.2,0.4,0.4V19.3z M17.5,19.3c0,0.2-0.2,0.4-0.4,0.4s-0.4-0.2-0.4-0.4V11c0-0.2,0.2-0.4,0.4-0.4s0.4,0.2,0.4,0.4V19.3z"/>
                            </g>
                        </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </can>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
    methods: {
        ...mapActions(['getProjectLabels', 'deleteProjectLabel']),
        deleteLabel: function(id) {
            this.deleteProjectLabel(id);
            this.getProjectLabels(this.$route.params.id);
        },
    },
    created() {
        this.getProjectLabels(this.$route.params.id);
    },
    computed: mapGetters([
        'labels',
        'project',
    ]),
    data() {
        return {
            message: {
                edit_task_labels: this.translate('message.edit_task_labels'),
                new_label: this.translate('message.new_label'),
                open_tasks: this.translate('message.open_tasks'),
                view_grid: this.translate('message.view_grid'),
                view_board: this.translate('message.view_board'),
                new_task: this.translate('message.new_task'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';
    
    svg {
        width: 30px;
        height: 30px;
        margin-left: 5px;
    }

    .label-list {
        margin: 16px 0 20px;
    }

    .label-row {
        padding: 25px 0;
        border-bottom: 1px solid #000;
    }

    .btn-rounded {
        margin-left: 10px;
    }

    .description, .label-icon {
        margin-right: 30px;
    }

    .description {
        width: 400px;
        font-weight: 300;
    }

    .tasks {
        text-transform: uppercase;
        letter-spacing: 1.7px;
        font-weight: 300;
    }

    .label-icon {
        width: 254px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        color: $lighterColor;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 300;
        letter-spacing: 1.3px;
    }

    .yellow {
        background: #D1B646;
    }

    .ocean {
        background: #50C9DD;
    }
</style>
