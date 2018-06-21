<template>
    <div class="project-contract">
        <div class="page-section">
            <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                <p class="modal-title">{{ translateText('message.delete_remaining_action') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                    <a href="javascript:void(0)" @click="removeAction" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
                </div>
            </modal>

            <div class="row">
                <!-- /// Header /// -->
                <div class="col-md-12">
                    <div class="header">
                        <div class="text-center">
                            <h1>{{ projectCloseDown.projectName }}</h1>
                        </div>
                    </div>

                    <div class="hero-text">
                        {{ translateText('message.close_down_report') }}
                    </div>

                    <div class="project-info">
                        <span>{{ translateText('message.scope') }}: {{ project.projectScopeName || '-' }}</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>{{ translateText('message.category') }}: {{ project.projectCategoryName || '-' }}</span>
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
                    <editor
                        height="200px"
                        :class="{disabledpicker: projectCloseDown.frozen}"
                        id="overallImpression"
                        v-model="overallImpression"
                        :label="'message.overall_impression'" />
                </div>
                <!-- End Overall Impression -->
            </div>

            <div class="row margintop40">
                <!-- /// Evaluation Objectives /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.evaluation_objectives') }}</h3>

                    <div v-dragula="colOne" drake="evaluations" v-if="projectCloseDown.evaluationObjectives && projectCloseDown.evaluationObjectives.length > 0">
                        <drag-box :disabled="projectCloseDown.frozen" v-for="(item, index) in projectCloseDown.evaluationObjectives" v-bind:item="item" v-bind:index="index" type="evaluation"></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <div class="form-group" v-if="!projectCloseDown.frozen">
                        <input-field v-model="evaluationObjective" :content="evaluationObjective" type="text" v-bind:label="translateText('message.new_evaluation_objective')"></input-field>
                        <error
                            v-if="validationMessages.evaluationForm && validationMessages.title && validationMessages.title.length"
                            v-for="message in validationMessages.title"
                            :message="message" />
                    </div>
                    <div class="flex flex-direction-reverse" v-if="!projectCloseDown.frozen">
                        <a @click="createObjective" class="btn-rounded btn-auto">{{ translateText('message.add_new_evaluation_objective') }} +</a>
                    </div>
                </div>
                <!-- /// End Evaluation Objectives /// -->

                <!-- /// Lessons Learned /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.lessons_learned') }}</h3>

                    <div v-dragula="colOne" drake="lessons" v-if="projectCloseDown.lessons && projectCloseDown.lessons.length > 0">
                        <drag-box :disabled="projectCloseDown.frozen" v-for="(item, index) in projectCloseDown.lessons" v-bind:item="item" v-bind:index="index" type="lesson"></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <div class="form-group" v-if="!projectCloseDown.frozen">
                        <input-field v-model="lesson" :content="lesson" type="text" v-bind:label="translateText('message.new_lesson')"></input-field>
                        <error
                            v-if="validationMessages.lessonForm && validationMessages.title && validationMessages.title.length"
                            v-for="message in validationMessages.title"
                            :message="message" />
                    </div>
                    <div class="flex flex-direction-reverse" v-if="!projectCloseDown.frozen">
                        <a @click="createCloseDownLesson" class="btn-rounded btn-auto">{{ translateText('message.add_new_lesson') }} +</a>
                    </div>
                </div>
                <!-- /// End Lessons Learned /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Reflection: Performance / Schedule /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.performance_schedule') }}</h4>
                    <editor
                        height="200px"
                        :class="{disabledpicker: projectCloseDown.frozen}"
                        id="performanceSchedule"
                        v-model="performanceSchedule" />
                </div>
                <!-- /// End Reflection: Performance / Schedule /// -->

                <!-- /// Reflection: Organization / Context /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.organization_context') }}</h4>
                    <editor
                        height="200px"
                        :class="{disabledpicker: projectCloseDown.frozen}"
                        id="organizationContext"
                        v-model="organizationContext" />
                </div>
                <!-- /// End Reflection: Organization / Context /// -->

                <!-- /// Reflection: Project Management /// -->
                <div class="col-md-4">
                    <h4>{{ translateText('message.reflection') }}: {{ translateText('message.project_management') }}</h4>
                    <editor
                        height="200px"
                        :class="{disabledpicker: projectCloseDown.frozen}"
                        id="projectManagement"
                        v-model="projectManagement" />
                </div>
                <!-- /// End Reflection: Project Management /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Remaining Actions Table /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.remaining_action') }}</h3>
                    <scrollbar class="customScrollbar" v-if="closeDownActions">
                        <div class="scroll-wrapper">
                            <table class="table table-striped table-responsive" v-if="closeDownActions.items && closeDownActions.items.length > 0">
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
                                    <tr v-for="action in closeDownActions.items">
                                        <td>{{ action.dueDate|moment('DD.MM.YYYY') }}</td>
                                        <td>{{ action.title }}</td>
                                        <td>
                                            <p class="action-description" v-html="action.description"></p>
                                        </td>
                                        <td class="text-center">
                                            <div class="avatar" v-tooltip.top-center="action.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + action.responsibilityAvatar + ')' }"></div>
                                        </td>
                                        <td>
                                            <div class="text-right" v-if="!projectCloseDown.frozen">
                                                <router-link class="btn-icon" :to="{name: 'project-close-down-report-view-remaining-action', params:{actionId: action.id}}" v-tooltip.top-center="translateText('message.view_remaining_action')">
                                                    <view-icon fill="second-fill"></view-icon>
                                                </router-link>
                                                <router-link class="btn-icon" :to="{name: 'project-close-down-report-edit-remaining-action', params:{actionId: action.id}}" v-tooltip.top-center="translateText('message.edit_remaining_action')">
                                                    <edit-icon fill="second-fill"></edit-icon>
                                                </router-link>
                                                <button @click="initDeleteModal(action)" data-toggle="modal" type="button" class="btn-icon" v-tooltip.top-center="translateText('message.delete_remaining_action')">
                                                    <delete-icon fill="danger-fill"></delete-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>{{ translateText('label.no_data') }}</div>
                        </div>
                    </scrollbar>
                    <div v-else>{{ translateText('label.no_data') }}</div>
                    <div class="flex flex-direction-reverse flex-v-center" v-if="pages > 0">
                        <div class="pagination">
                            <span v-if="pages > 1" v-for="page in pages" v-bind:class="{'active': page == activePage}" @click="changePage(page)" >{{ page }}</span>
                        </div>
                        <div>
                            <span class="pagination-info">{{ translateText('message.displaying') }} {{ closeDownActions.items.length }} {{ translateText('message.results_out_of') }} {{ closeDownActions.totalItems }}</span>
                        </div>
                    </div>
                </div>

                <!-- /// End Remaining Actions Table /// -->

                <!-- /// Remaining Actions Form /// -->
                <div class="col-md-6" id="addAction" v-if="!projectCloseDown.frozen">
                    <h3>{{ translateText('message.add_new_action') }}</h3>
                    <div class="form">
                        <!-- /// Title and Description /// -->
                        <div class="form-group">
                            <input-field type="text" v-model="actionTitle" :content="actionTitle" v-bind:label="translateText('placeholder.title')" />
                            <error
                                v-if="validationMessages.actionForm && validationMessages.title && validationMessages.title.length"
                                v-for="message in validationMessages.title"
                                :message="message" />
                        </div>

                        <div class="form-group">
                            <editor
                                height="200px"
                                :class="{disabledpicker: projectCloseDown.frozen}"
                                id="actionDescription"
                                v-model="actionDescription"
                                :label="'placeholder.description'" />
                        </div>
                        <!-- /// End Title and Description /// -->

                        <!-- /// Responsible and Due Date /// -->
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <member-search v-model="actionResponsible" v-bind:placeholder="translateText('placeholder.responsible')" v-bind:singleSelect="true"></member-search>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-holder right">
                                        <label class="active">{{ translateText('label.due_date') }}</label>
                                        <datepicker v-model="actionDueDate" format="dd-MM-yyyy" />
                                        <calendar-icon fill="middle-fill"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Responsible and Due Date /// -->

                        <!-- /// Actions /// -->
                        <div class="flex flex-direction-reverse">
                            <a @click="addCloseDownAction" class="btn-rounded btn-auto">{{ translateText('button.add_new_remaining_action') }} +</a>
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
                                    {{ translateText('label.project_sponsors') }}
                                </div>
                                <div class="member-name" v-for="sponsor in sponsors">
                                    {{ sponsor.userFullName }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="job-title">
                                    {{ translateText('label.project_managers') }}
                                </div>
                                <div class="member-name" v-for="manager in managers">
                                    {{ manager.userFullName }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="project-acceptance">
                                <div class="signature-holder"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-acceptance">
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
                        <a v-if="!projectCloseDown.frozen" @click="saveCloseDown" class="btn-rounded second-bg">{{ translateText('button.save') }}</a>
                        <a class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                            {{ translateText('button.download_pdf') }}<downloadbutton-icon fill="white-fill"></downloadbutton-icon>
                        </a>
                    </div>
                </div>
                <!-- /// End Footer Buttons /// -->
            </div>
        </div>
        <alert-modal v-if="showSavedComponent" @close="showSavedComponent = false" body="message.saved" />
        <alert-modal v-if="showFailedComponent" @close="showFailedComponent = false" body="message.saved" />
    </div>
</template>

<script>
import Vue from 'vue';
import {mapGetters, mapActions} from 'vuex';
import DragBox from './TaskManagement/DragBox.vue';
import InputField from '../_common/_form-components/InputField.vue';
import CalendarIcon from '../_common/_icons/CalendarIcon.vue';
import DownloadbuttonIcon from '../_common/_icons/DownloadbuttonIcon.vue';
import datepicker from '../_common/_form-components/Datepicker';
import moment from 'moment';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import MemberSearch from '../_common/MemberSearch';
import Error from '../_common/_messages/Error.vue';
import AlertModal from '../_common/AlertModal.vue';
import Modal from '../_common/Modal';
import Editor from '../_common/Editor';

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
        Error,
        AlertModal,
        Modal,
        Editor,
    },
    methods: {
        ...mapActions([
            'getProjectCloseDown', 'getProjectUsers', 'createProjectCloseDown',
            'editProjectCloseDown', 'createEvaluationObjective', 'createLesson',
            'editEvaluationObjective', 'editLesson', 'reorderEvaluationObjectives', 'reorderLessons',
            'getCloseDownActions', 'createCloseDownAction', 'deleteCloseDownAction',
            'getProjectById',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        changePage: function(page) {
            this.activePage = page;
            this.getCloseDownActions({
                closeDownId: this.projectCloseDown.id,
                queryParams: {
                    page: this.activePage,
                },
            });
        },
        reorderSequences: function(values, dragIndex, dropIndex) {
            let data = [];
            data.push({id: values[dragIndex].id, sequence: values[dropIndex].sequence});
            if (dropIndex > dragIndex) {
                for (let i = dragIndex + 1; i <= dropIndex; i++) {
                    data.push({
                        id: values[i].id,
                        sequence: values[i-1].sequence,
                    });
                }
            } else {
                for (let i = dragIndex - 1; i >= dropIndex; i--) {
                    data.push({
                        id: values[i].id,
                        sequence: values[i+1].sequence,
                    });
                }
            }
            return data;
        },
        saveCloseDown: function() {
            let data = {
                id: this.projectCloseDown.id,
                overallImpression: this.overallImpression,
                performanceSchedule: this.performanceSchedule,
                organizationContext: this.organizationContext,
                projectManagement: this.projectManagement,
                frozen: true,
            };
            this.editProjectCloseDown(data);
        },
        createObjective: function() {
            this.createEvaluationObjective({
                closeDownId: this.projectCloseDown.id,
                title: this.evaluationObjective,
                sequence: this.projectCloseDown.evaluationObjectives.length,
            }).then(
                (response) => {
                    this.evaluationObjective = null;
                },
            );
        },
        createCloseDownLesson: function() {
            this.createLesson({
                closeDownId: this.projectCloseDown.id,
                title: this.lesson,
                sequence: this.projectCloseDown.lessons.length,
            }).then(
                (response) => {
                    this.lesson = null;
                },
            );
        },
        addCloseDownAction: function() {
            let data = {
                closeDownId: this.projectCloseDown.id,
                title: this.actionTitle,
                description: this.actionDescription,
                responsibility: this.actionResponsible.length > 0 ? this.actionResponsible[0] : null,
                dueDate: moment(this.actionDueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
            };
            this.createCloseDownAction(data)
                .then(
                    (response) => {
                        this.actionTitle = null;
                        this.actionDescription = '';
                        this.actionResponsible = [];
                        this.dueDate = new Date();
                    },
                );
        },
        initDeleteModal: function(closeDownAction) {
            this.showDeleteModal = true;
            this.closeDownActionId = closeDownAction.id;
        },
        removeAction: function() {
            this.deleteCloseDownAction(this.closeDownActionId);
            this.showDeleteModal = false;
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getProjectCloseDown(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id});
        const service = Vue.$dragula.$service;
        let vm = this;
        service.eventBus.$on('dropModel', function(args) {
            if (!vm.projectCloseDown.frozen) {
                switch(args.name) {
                case 'evaluations':
                    vm
                        .reorderEvaluationObjectives(vm.reorderSequences(vm.projectCloseDown.evaluationObjectives, args.dragIndex, args.dropIndex))
                        .then(
                            () => {
                                this.showSavedComponent = true;
                                vm.getProjectCloseDown(vm.$route.params.id);
                            },
                            () => {
                                this.showFailedComponent = true;
                            }
                        )
                    ;
                    break;
                case 'lessons':
                    vm
                        .reorderLessons(vm.reorderSequences(vm.projectCloseDown.lessons, args.dragIndex, args.dropIndex))
                        .then(
                            () => {
                                this.showSavedComponent = true;
                                vm.getProjectCloseDown(vm.$route.params.id);
                            },
                            () => {
                                this.showFailedComponent = true;
                            }
                        )
                    ;
                    break;
                default:
                    break;
                }
            }
        });
    },
    computed: {
        ...mapGetters({
            project: 'project',
            projectCloseDown: 'projectCloseDown',
            managers: 'projectManagers',
            sponsors: 'projectSponsors',
            validationMessages: 'validationMessages',
            closeDownActions: 'closeDownActions',
        }),
        pages: function() {
            return Math.ceil(this.closeDownActions.totalItems / this.perPage);
        },
        perPage: function() {
            return this.closeDownActions.pageSize;
        },
        downloadPdf() {
            return Routing.generate('app_project_close_down_pdf', {id: this.projectCloseDown.id});
        },
    },
    watch: {
        projectCloseDown(value) {
            if (!this.projectCloseDown.id) {
                this.createProjectCloseDown({projectId: this.$route.params.id});
            } else {
                this.overallImpression = this.projectCloseDown.overallImpression;
                this.performanceSchedule = this.projectCloseDown.performanceSchedule;
                this.organizationContext = this.projectCloseDown.organizationContext;
                this.projectManagement = this.projectCloseDown.projectManagement;
                this.getCloseDownActions({
                    closeDownId: this.projectCloseDown.id,
                    queryParams: {
                        page: this.activePage,
                    },
                });
            }
        },
    },
    data() {
        return {
            actionTitle: '',
            actionResponsible: [],
            actionDueDate: new Date(),
            overallImpression: '',
            performanceSchedule: '',
            organizationContext: '',
            projectManagement: '',
            actionDescription: '',
            evaluationObjective: null,
            lesson: null,
            showSavedComponent: false,
            showFailedComponent: false,
            activePage: 1,
            showDeleteModal: false,
            closeDownActionId: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';
    @import '../../css/_mixins';
    @import '../../css/common';

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

    .project-info {
        text-align: center;
        margin-bottom: 2em;

        span {
            display: inline-block;
            font-size: 1.5em;
        }
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

    .disabledpicker {
        pointer-events: none;
        opacity: .5;
    }
</style>
