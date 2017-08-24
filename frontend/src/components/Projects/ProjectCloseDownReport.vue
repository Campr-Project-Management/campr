<template>
    <div class="project-contract">
        <div class="page-section">
            <div class="row">
                <!-- /// Header /// -->
                <div class="col-md-12">
                    <div class="header">
                        <div class="text-center">                            
                            <h1>Project Name</h1>
                        </div>
                    </div>

                    <div class="hero-text">
                        {{ translateText('message.close_down_report') }}
                    </div>


                    <div class="flex buttons flex-center" v-if="contract && contract.id">
                        <a class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                            {{ translateText('button.download_pdf') }}<downloadbutton-icon fill="white-fill"></downloadbutton-icon>
                        </a>
                    </div>
                </div>
                <!-- /// End Header /// -->
            </div>

            <div class="row">
                <!-- /// Overall Impression /// -->
                <div class="col-md-6 col-md-offset-3">
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('message.overall_impression') }}</div>
                        <Vueditor :ref="'overall-impression'"/>
                    </div>
                </div>
                <!-- End Overall Impression -->
            </div>

            <div class="row margintop40">
                <!-- /// Evaluation Objectives /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.evaluation_objectives') }}</h3>

                    <div v-dragula="colOne" v-if="'add if condition'">
                        <drag-box></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('message.new_evaluation_objective')"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a class="btn-rounded btn-auto">{{ translateText('message.add_new_evaluation_objective') }} +</a>
                    </div>
                </div>
                <!-- /// End Evaluation Objectives /// -->

                <!-- /// Lessons Learned /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.lessons_learned') }}</h3>

                    <div v-dragula="colOne" v-if="'add if condition'">
                        <drag-box></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <div class="form-group">
                        <input-field type="text" v-bind:label="translateText('message.new_lesson')"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a href="#" class="btn-rounded btn-auto">{{ translateText('message.add_new_lesson') }} +</a>
                    </div>
                </div>
                <!-- /// End Lessons Learned /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Reflection: Performance / Schedule /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.performance_schedule') }}</h4>
                    <div class="vueditor-holder">
                        <Vueditor :ref="'reflexion-performance-shcedule'"/>
                    </div>
                </div>
                <!-- /// End Reflection: Performance / Schedule /// -->

                <!-- /// Reflection: Organization / Context /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.organization_context') }}</h4>
                    <div class="vueditor-holder">
                        <Vueditor :ref="'reflexion-organization-context'"/>
                    </div>
                </div>
                <!-- /// End Reflection: Organization / Context /// -->

                <!-- /// Reflection: Project Management /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.project_management') }}</h4>
                    <div class="vueditor-holder">
                        <Vueditor :ref="'reflexion-project-management`'"/>
                    </div>
                </div>
                <!-- /// End Reflection: Project Management /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Remaining Actions Table /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.remaining_action') }}</h3>
                    <div class="table-wrapper">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th class="date-cell">{{ translateText('table_header_cell.due_date') }}</th>
                                    <th>{{ translateText('table_header_cell.topic') }}</th>
                                    <th>{{ translateText('table_header_cell.description') }}</th>
                                    <th class="text-center">{{ translateText('table_header_cell.responsible') }}</th>
                                    <th>{{ translateText('table_header_cell.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>15.08.2018</td>
                                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                    <td>
                                        <p class="action-description">Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action'}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                <view-icon fill="second-fill"></view-icon>
                                            </router-link>
                                            <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action'}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                <edit-icon fill="second-fill"></edit-icon>
                                            </router-link>
                                            <button data-target="#action-1-delete" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                <delete-icon fill="danger-fill"></delete-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /// End Remaining Actions Table /// -->

                <!-- /// Remaining Actions Form /// -->
                <div class="col-md-6" id="addAction">
                    <h3>{{ translateText('message.add_new_action') }}</h3>
                    <div class="form">
                        <!-- /// Title and Description /// -->
                        <div class="form-group">
                            <input-field type="text" v-bind:label="translateText('placeholder.title')" v-model="title" v-bind:content="title" />
                        </div>

                        <div class="form-group">
                            <div class="vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.description') }}</div>
                                <Vueditor ref="description" />
                            </div>
                        </div>
                        <!-- /// End Title and Description /// -->

                        <!-- /// Responsible and Due Date /// -->
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search v-model="responsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.due_date') }}</label>
                                        <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- /// End Responsible and Due Date /// -->             

                        <!-- /// Actions /// -->
                        <div class="flex flex-direction-reverse">
                            <a href="#" class="btn-rounded btn-auto">{{ translateText('button.add_new_remaining_action') }} +</a>
                        </div>
                        <!-- /// End Actions /// -->
                    </div>
                </div>
                <!-- /// End Remaining Actions Form /// -->
            </div>

            <!-- /// Project Acceptance /// -->
            <div class="row margintop40">
                <div class="col-md-6 col-md-offset-3">
                    <h3 class="text-center">{{ translateText('message.project_acceptance') }}</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="job-title">
                                    Project Sponsor
                                </div>
                                <div class="member-name">
                                    Ian McKellen
                                </div>
                                <div class="signature-holder"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="job-title">
                                    Project Manager
                                </div>
                                <div class="member-name">
                                    David Gilmour
                                </div>
                                <div class="signature-holder"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /// End Project Acceptance /// -->

            <div class="row">
                <!-- /// Footer Buttons /// -->
                <div class="col-md-12">
                    <div class="hr"></div>
                    <div class="flex flex-space-between buttons">
                        <a href="#" class="btn-rounded second-bg">{{ translateText('button.save') }}</a>
                        <a class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                            {{ translateText('button.download_pdf') }}<downloadbutton-icon fill="white-fill"></downloadbutton-icon>
                        </a>
                    </div>
                </div>
                <!-- /// End Footer Buttons /// -->
            </div>
        </div>
    </div>
</template>

<script>
import DragBox from './TaskManagement/DragBox.vue';
import InputField from '../_common/_form-components/InputField.vue';
import CalendarIcon from '../_common/_icons/CalendarIcon.vue';
import DownloadbuttonIcon from '../_common/_icons/DownloadbuttonIcon.vue';
import datepicker from 'vuejs-datepicker';
import moment from 'moment';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import MemberSearch from '../_common/MemberSearch';

export default {
    components: {
        DragBox,
        datepicker,
        InputField,
        CalendarIcon,
        DownloadbuttonIcon,
        moment,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        MemberSearch,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            title: '',
            responsible: [],
            schedule: {
                dueDate: new Date(),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 20px;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 10px;
                }
            }
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 30px; 
    }

    .header .btn-md {
        margin-top: 24px;
    }

    .hr {
        margin: 30px 0;

        &.small {
            margin: 20px 0;
        }
    }

    .buttons {
        a {
            margin: 0 20px 10px 0;

            &:last-child {
                margin: 0 0 10px 0;
            }
        }
    }

    .long-cell {
        width: 50%;
    }

    .table-wrapper {
        overflow: auto;
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
    }

    .table-responsive {
        th, td {
            white-space: nowrap;
        }
    }

    .action-description {
        width: 300px;
        margin: 0;
        white-space: normal;
        text-transform: none;
    }

    .project-acceptance {
        text-align: center;

        .job-title {
            color: $lightColor;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.2em;
        }

        .member-name {
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .signature-holder {
            height: 100px;
            border-bottom: 3px solid $darkColor;
        }
    }
</style>
