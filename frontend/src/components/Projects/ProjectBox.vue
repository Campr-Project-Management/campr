<template>
    <div class="project-box-wrapper">
        <div class="project-box box" v-bind:class="{ new: project.isNew }">
            <span class="tag" v-if="project.isNew">
              <span>{{ translateText('message.new_message') }}</span>
            </span>
            <div class="box-header">
                <h2><router-link class="simple-link" :to="{name: 'project-dashboard', params: { id: project.id }}">{{ project.name }}</router-link></h2>
                <div class="favourite">
                    <duplicate-icon
                            v-if="$can('ROLE_ADMIN|ROLE_SUPER_ADMIN', project)"
                            @click="openCopyProjectModal()"/>
                    <star-icon :item="project"></star-icon>
                </div>
            </div>
            <div class="content flex flex-space-between">
                <div class="info">
                    <p>
                        <span class="title">{{ translateText('message.started_on') }}:</span>
                        <span class="data">{{ baseDate(project) | moment('DD.MM.YYYY') }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.customer') }}:</span>
                        <span class="data">{{ project.companyName }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.programme') }}:</span>
                        <span class="data"> {{ project.programmeName }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.category') }}:</span>
                        <span class="data">{{ project.projectCategoryName || '-' }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.scope') }}:</span>
                        <span class="data">{{ project.projectScopeName || '-' }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.status') }}:</span>
                        <span v-bind:class="{ finished: project.statusName === 'Finished' }" class="status-label btn-rounded btn-auto">
                            {{ translateText(project.statusName) || '-' }}
                        </span>
                    </p>
                </div>
              </div>
            <bar-chart
                    :precision="0"
                    :percentage="project.progress"
                    :status="project.statusName"
                    title-right="Progress"/>
            <div class="content-bottom flex circles">
                <circle-chart
                        :bg-stroke-color="$theme.darker"
                        :percentage="project.progress"
                        :precision="0"
                        :title="translate('message.task_status')"
                        class="left"/>

                <circle-chart
                        :bg-stroke-color="$theme.darker"
                        :percentage="project.costProgress"
                        :precision="0"
                        :title="translate('message.cost_status')"
                        class="right"/>
            </div>
            <div class="flex flex-space-between notes-title">
                <span class="uppercase">{{ translateText('message.notes') }}</span>
                <span @click="showEditor()"><pencil-icon></pencil-icon></span>
            </div>
            <scrollbar class="note-wrapper customScrollbar">
                <p v-if="!showNoteEditor" v-html="project.shortNote" class="project-note"></p>
            </scrollbar>
            <div v-if="showNoteEditor">
                <editor
                    height="200px"
                    :id="`project-${project.id}`"
                    :toolbar="toolbar"
                    v-model="shortNote" />
                <div class="buttons">
                    <button @click="saveNote()" class="btn-rounded btn-auto btn-md">{{ translateText('button.save') }}</button>
                    <button @click="showNoteEditor = false" class="btn-rounded btn-auto danger-bg btn-md">
                        {{ translateText('button.cancel') }}
                    </button>
                </div>
            </div>
        </div>
        <alert-modal v-if="showFailed" body="message.unable_to_save" @close="showFailed = false;" />
        <alert-modal v-if="showCloned" body="message.project.clone.in_progress" @close="showCloned = false;" />
        <modal v-if="showCopyProjectModal" @close="showCopyProjectModal = false" v-bind:hasSpecificClass="true">
            <p class="modal-title">{{ translate('title.copy_project') }}</p>
            <div class="form-group">
                <input-field
                        v-model="projectName"
                        type="text"
                        :label="translate('message.project_name')"/>
                <error at-path="projectName" v-if="validationMessages"
                       v-for="message in validationMessages"
                       :message="message" />
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showCopyProjectModal = false"
                   class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="doCopyProjectModal()" v-if="projectName"
                   class="btn-rounded btn-auto second-bg">{{ translate('button.copy_project') }} +</a>
            </div>
        </modal>
    </div>
</template>

<script>
import CircleChart from '../_common/_charts/CircleChart';
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';
import DuplicateIcon from '../_common/_icons/DuplicateIcon';
import PencilIcon from '../_common/_icons/PencilIcon';
import Editor from '../_common/Editor';
import InputField from '../_common/_form-components/InputField.vue';
import Modal from '../_common/Modal.vue';
import AlertModal from '../_common/AlertModal.vue';
import Error from '../_common/_messages/Error.vue';
import {mapGetters, mapActions} from 'vuex';

export default {
    components: {
        CircleChart,
        BarChart,
        StarIcon,
        PencilIcon,
        Editor,
        InputField,
        Modal,
        AlertModal,
        Error,
        DuplicateIcon,
    },
    props: ['project'],
    created() {
        this.shortNote = this.project.shortNote;
    },
    computed: {
        ...mapGetters([
            'validationMessages',
        ]),
    },
    methods: {
        ...mapActions(['editProject', 'cloneProject']),
        baseDate(project) {
            return project && project.contracts && project.contracts.length
                ? project.contracts[0].proposedStartDate
                : '-'
            ;
        },
        showEditor: function() {
            this.showNoteEditor = true;
        },
        translateText: function(text) {
            return this.translate(text);
        },
        saveNote: function() {
            this.showNoteEditor = false;
            this.editProject({projectId: this.project.id, shortNote: this.shortNote});
        },
        openCopyProjectModal() {
            this.showCopyProjectModal = true;
        },
        doCopyProjectModal() {
            let data = {id: this.project.id, name: this.projectName};
            this
                .cloneProject(data)
                .then(
                    (response) => {
                        this.showCopyProjectModal = false;
                        this.projectName = null;
                        if(response.status === 200) {
                            this.showFailed = false;
                            this.showCloned = true;
                        }else{
                            this.showFailed = true;
                            this.showCloned = false;
                        }
                    }
                )
            ;
        },
    },
    data() {
        return {
            showNoteEditor: false,
            shortNote: '',
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{'list': 'ordered'}, {'list': 'bullet'}],
            ],
            showCopyProjectModal: false,
            projectName: '',
            cloneErrorMessages: null,
            showFailed: false,
            showCloned: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/box';
    @import '../../css/box-project';

    .circles {
        flex-wrap: wrap;

        .chart {
            width: calc(50% - 10px);
        }

        @media (max-width: 1024px) and (min-width: 768px){
            .chart {
                margin: 0 0 10px 0;
                width: 100%;

                &.last-child {
                    margin-bottom: 0;
                }
            }
        }

        @media (max-width: 767px) and (min-width: 500){
            .chart {
                width: calc(50% - 10px);

                &.left {
                    margin: 0 10px 0 0;
                }

                &.right {
                    margin: 0 0 0 10px;
                }
            }
        }

        @media (max-width: 499px) {
            .chart {
                margin: 0 0 10px 0;
                width: 100%;

                &.last-child {
                    margin-bottom: 0;
                }
            }
        }
    }
</style>
