<template>
    <div class="project-contract">
        <div class="page-section">
            <div class="row">
                <!-- /// Header /// -->
                <div class="col-md-12">
                    <div class="header">
                        <div class="text-center">
                            <h1>{{ contract.projectName }}</h1>
                        </div>
                    </div>

                    <div class="hero-text">
                        {{ translateText('message.project_contract') }}
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
                        <button v-if="!frozen" @click="freezeContract()" class="btn-rounded second-bg">{{ translateText('button.freeze_contract') }}</button>
                        <h4 v-else>{{ translateText('message.contract_frozen') }}</h4>
                    </div>
                </div>
                <!-- /// End Header /// -->
            </div>

            <div class="row">
                <!-- /// Project Description /// -->
                <div class="col-md-6">
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('message.project_description') }}</div>
                        <div :class="{disabledpicker: frozen }">
                            <Vueditor id="descriptionEditor" :ref="'contract.description'"/>
                        </div>
                    </div>
                </div>
                <!-- End Project Description -->

                <!-- /// Project Start Event /// -->
                <div class="col-md-6">
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('message.project_start_event') }}</div>
                        <div :class="{disabledpicker: frozen }">
                            <Vueditor id="eventEditor" :ref="'contract.projectStartEvent'"/>
                        </div>
                    </div>
                </div>
                <!-- /// End Project Start Event /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Project Schedule /// -->
                <div class="col-md-6 col-md-offset-3">
                    <h3 class="text-center">{{ translateText('message.schedule') }}</h3>
                    <div class="flex flex-space-between dates">
                        <div class="input-holder left" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translateText('label.proposed_start_date') }}</label>
                            <datepicker
                                :value="proposedStartDate"
                                format="dd - MM - yyyy"
                                :disabled-picker="true"/>
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <div class="input-holder right" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translateText('label.proposed_end_date') }}</label>
                            <datepicker
                                :value="proposedEndDate"
                                format="dd - MM - yyyy"
                                :disabled-picker="true"/>
                            <calendar-icon fill="middle-fill"/>
                        </div>
                    </div>
                    <div class="flex flex-space-between dates right">
                        <div class="input-holder left" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translateText('label.forecast_start_date') }}</label>
                            <datepicker
                                :value="forecastStartDate"
                                format="dd - MM - yyyy"
                                :disabled-picker="true"/>
                            <calendar-icon fill="middle-fill"/>
                        </div>
                        <div class="input-holder right" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translateText('label.forecast_end_date') }}</label>
                            <datepicker
                                :value="forecastEndDate"
                                format="dd - MM - yyyy"
                                :disabled-picker="true"/>
                            <calendar-icon fill="middle-fill"/>
                        </div>
                    </div>
                </div>
                <!-- /// End Project Schedule /// -->
            </div>

            <div class="row">
                <!-- /// Project Sponsors & Managers /// -->
                <div class="col-md-12">
                    <div class="header">
                        <h3 class="clickable" @click="toggleSponsorsManagers()">{{ translateText('message.sponsors_managers') }}
                            <i class="fa fa-angle-down" v-if="!showSponsorsManagers"></i>
                            <i class="fa fa-angle-up" v-if="showSponsorsManagers"></i>
                        </h3>
                    </div>
                    <div class="text-center">
                        <router-link class="btn-rounded btn-md btn-empty" :to="{name: 'project-organization'}">
                            {{ translateText('message.view_team') }}
                        </router-link>
                    </div>
                    <div class="flex flex-row flex-center members-big" v-if="showSponsorsManagers">
                        <member-badge v-for="item in projectSponsors" v-bind:item="item" size="small"></member-badge>
                        <member-badge v-for="item in projectManagers" v-bind:item="item" size="small"></member-badge>
                        <p v-if="projectSponsors.length === 0 && projectManagers.length === 0">{{ translateText('label.no_data') }}</p>
                    </div>
                </div>
                <!-- /// End Project Sponsors & Managers /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Project Objectives /// -->
                <div class="col-md-4">
                    <h3>{{ translateText('message.project_objectives') }}</h3>

                    <div v-dragula="colOne" drake="objectives" v-if="project.projectObjectives && project.projectObjectives.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectObjectives" v-bind:item="item" v-bind:index="index" type="objective"></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="objectiveTitle" :content="objectiveTitle" type="text" v-bind:label="translateText('message.new_objective_title')"></input-field>
                    <error
                            v-if="validationMessages.createProjectObjectiveForm && validationMessages.title && validationMessages.title.length"
                            v-for="message in validationMessages.title"
                            :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <a v-on:click="createProjectObjective()" class="btn-rounded btn-auto">{{ translateText('message.add_objective') }} +</a>
                    </div>
                </div>
                <!-- /// End Project Objectives /// -->

                <!-- /// Project Limitations /// -->
                <div class="col-md-4">
                    <h3>{{ translateText('message.project_limitations') }}</h3>

                    <div v-dragula="colOne" drake="limitations" v-if="project.projectLimitations && project.projectLimitations.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectLimitations" v-bind:item="item" v-bind:index="index" type="limitation"></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="limitationDescription" :content="limitationDescription" type="text" v-bind:label="translateText('message.new_project_limitation')"></input-field>
                    <error
                        v-if="validationMessages.createProjectLimitationForm && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <a v-on:click="createProjectLimitation()" class="btn-rounded btn-auto">{{ translateText('message.add_limitation') }} +</a>
                    </div>
                </div>
                <!-- /// End Project Limitations /// -->

                <!-- /// Project Deliverables /// -->
                <div class="col-md-4">
                    <h3>{{ translateText('message.project_deliverables') }}</h3>
                    <div v-dragula="colOne" drake="deliverables" v-if="project.projectDeliverables && project.projectDeliverables.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectDeliverables" v-bind:item="item" v-bind:index="index" type="deliverable"></drag-box>
                    </div>
                    <p class="notice" v-else>{{ translateText('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="deliverableDescription" :content="deliverableDescription" type="text" v-bind:label="translateText('message.new_project_deliverable')"></input-field>
                    <error
                        v-if="validationMessages.createProjectDeliverableForm && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <button v-on:click="createProjectDeliverable()" class="btn-rounded btn-auto">{{ translateText('message.add_deliverable') }} +</button>
                    </div>
                </div>
                <!-- /// End Project Deliverables /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Project Internal Costs /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.internal_resources') }}</h3>
                    <chart :data="internalCostsGraphData.byPhase"/>
                </div>
                <!-- /// End Project Internal Costs /// -->

                <!-- /// Project External Costs /// -->
                <div class="col-md-6">
                    <h3>{{ translateText('message.external_resources') }}</h3>
                    <chart :data="externalCostsGraphData.byPhase"/>
                </div>
                <!-- /// End Project External Costs /// -->
            </div>

            <div class="row">
                <!-- /// Footer Buttons /// -->
                <div class="col-md-12">
                    <div class="hr"></div>
                    <div class="flex flex-space-between buttons">
                        <a v-if="!frozen" v-on:click="updateProjectContract()" class="btn-rounded second-bg">{{ translateText('button.save') }}</a>
                        <div class="flex">
                            <a v-if="contract && contract.id" class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                                {{ translateText('button.download_pdf') }}<downloadbutton-icon fill="white-fill"></downloadbutton-icon>
                            </a>
                            <button v-if="!frozen" @click="freezeContract()" class="btn-rounded second-bg">{{ translateText('button.freeze_contract') }}</button>
                        </div>
                    </div>
                </div>
                <!-- /// End Footer Buttons /// -->
            </div>
        </div>

        <!-- Contract stuff -->
        <alert-modal v-if="showSaved" @close="showSaved = false" body="message.saved" />
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
        <!-- Contract component stuff -->
        <alert-modal v-if="showSavedComponent" @close="showSavedComponent = false" body="message.saved" />
        <alert-modal v-if="showFailedComponent" @close="showFailedComponent = false" body="message.saved" />
    </div>
</template>

<script>
import Vue from 'vue';
import {mapGetters, mapActions} from 'vuex';
import DragBox from './TaskManagement/DragBox.vue';
import Chart from './Charts/CostsChart.vue';
import InputField from '../_common/_form-components/InputField.vue';
import CalendarIcon from '../_common/_icons/CalendarIcon.vue';
import DownloadbuttonIcon from '../_common/_icons/DownloadbuttonIcon.vue';
import EyeIcon from '../_common/_icons/EyeIcon.vue';
import MemberBadge from '../_common/MemberBadge.vue';
import AlertModal from '../_common/AlertModal.vue';
import datepicker from '../_common/_form-components/Datepicker';
import router from '../../router';
import Error from '../_common/_messages/Error.vue';
import {createEditor} from 'vueditor';
import vueditorConfig from '../_common/vueditorConfig';
import moment from 'moment';

export default {
    components: {
        DragBox,
        datepicker,
        InputField,
        CalendarIcon,
        DownloadbuttonIcon,
        EyeIcon,
        MemberBadge,
        AlertModal,
        Error,
        Chart,
    },
    watch: {
        showSaved(value) {
            if (value === false) {
                router.push({
                    name: 'project-dashboard',
                    params: {
                        id: this.$route.params.id,
                    },
                });
            }
        },
        contract(value) {
            this.frozen = this.contract.frozen;
            setTimeout(() => {
                this.descriptionEditor.setContent(this.contract.description ? this.contract.description : '');
                this.eventEditor.setContent(this.contract.projectStartEvent ? this.contract.projectStartEvent : '');
            }, 1500);
        },
    },
    methods: {
        ...mapActions([
            'getProjectById', 'getContractByProjectId', 'updateContract',
            'createContract', 'createObjective', 'createLimitation', 'createDeliverable',
            'editObjective', 'editLimitation', 'editDeliverable', 'reorderObjectives',
            'reorderLimitations', 'reorderDeliverables', 'getProjectExternalCostsGraphData',
            'getProjectUsers', 'getProjectInternalCostsGraphData', 'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        toggleSponsorsManagers: function() {
            this.showSponsorsManagers = !this.showSponsorsManagers;
        },
        freezeContract: function() {
            if (this.contract.id) {
                let data = {
                    id: this.contract.id,
                    frozen: true,
                };
                this
                    .updateContract(data)
                    .then(
                        () => {
                            this.showSavedComponent = true;
                            this.frozen = true;
                        },
                        (response) => {
                            this.showFailed = response.status === 200;
                        }
                    )
                ;
            }
        },
        updateProjectContract() {
            let data = {
                projectId: this.$route.params.id,
                name: this.project.name + '-contract',
                description: this.descriptionEditor.getContent(),
                projectStartEvent: this.eventEditor.getContent(),
                proposedStartDate: moment(this.project.scheduledStartAt).format('DD-MM-YYYY'),
                proposedEndDate: moment(this.project.scheduledFinishAt).format('DD-MM-YYYY'),
                forecastStartDate: moment(this.project.forecastStartAt).format('DD-MM-YYYY'),
                forecastEndDate: moment(this.project.forecastFinishAt).format('DD-MM-YYYY'),
            };

            if (this.contract.id) {
                data.id = this.contract.id;
                this
                    .updateContract(data)
                    .then(
                        () => {
                            this.showSaved = true;
                        },
                        (response) => {
                            this.showFailed = response.status === 200;
                        }
                    )
                ;
            } else {
                this
                    .createContract(data)
                    .then(
                        (reponse) => {
                            this.showSaved = true;
                        },
                        (reponse) => {
                            this.showFailed = true;
                        }
                    )
                ;
            }
        },
        createProjectObjective: function() {
            let data = {
                projectId: this.$route.params.id,
                title: this.objectiveTitle,
                sequence: this.project.projectObjectives.length,
            };
            this
                .createObjective(data)
                .then(
                    () => {
                        this.showSavedComponent = true;
                        this.objectiveTitle = null;
                    },
                    () => {
                        this.showFailedComponent = true;
                    }
                )
            ;
        },
        createProjectLimitation: function() {
            let data = {
                projectId: this.$route.params.id,
                description: this.limitationDescription,
                sequence: this.project.projectLimitations.length,
            };
            this
                .createLimitation(data)
                .then(
                    () => {
                        this.showSavedComponent = true;
                        this.limitationDescription = null;
                    },
                    () => {
                        this.showFailedComponent = true;
                    }
                )
            ;
        },
        createProjectDeliverable: function() {
            let data = {
                projectId: this.$route.params.id,
                description: this.deliverableDescription,
                sequence: this.project.projectDeliverables.length,
            };
            this
                .createDeliverable(data)
                .then(
                    () => {
                        this.showSavedComponent = true;
                        this.deliverableDescription = null;
                    },
                    () => {
                        this.showFailedComponent = true;
                    }
                )
            ;
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
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getContractByProjectId(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id});
        this.getProjectInternalCostsGraphData({id: this.$route.params.id});
        this.getProjectExternalCostsGraphData({id: this.$route.params.id});
        const service = Vue.$dragula.$service;
        let vm = this;
        service.eventBus.$on('dropModel', function(args) {
            switch(args.name) {
            case 'objectives':
                vm
                    .reorderObjectives(vm.reorderSequences(vm.project.projectObjectives, args.dragIndex, args.dropIndex))
                    .then(
                        () => {
                            this.showSavedComponent = true;
                        },
                        () => {
                            this.showFailedComponent = true;
                        }
                    )
                ;
                break;
            case 'limitations':
                vm
                    .reorderLimitations(vm.reorderSequences(vm.project.projectLimitations, args.dragIndex, args.dropIndex))
                    .then(
                        () => {
                            this.showSavedComponent = true;
                        },
                        () => {
                            this.showFailedComponent = true;
                        }
                    )
                ;
                break;
            case 'deliverables':
                vm
                    .reorderDeliverables(vm.reorderSequences(vm.project.projectDeliverables, args.dragIndex, args.dropIndex))
                    .then(
                        () => {
                            this.showSavedComponent = true;
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
            vm.getProjectById(vm.$route.params.id);
        });
        setTimeout(() => {
            this.descriptionEditor = createEditor(document.getElementById('descriptionEditor'), {...vueditorConfig, id: 'descriptionEditor'});
            this.eventEditor = createEditor(document.getElementById('eventEditor'), {...vueditorConfig, id: 'eventEditor'});
        }, 1000);
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    computed: {
        ...mapGetters({
            contract: 'currentContract',
        }),
        ...mapGetters([
            'project',
            'externalCostsGraphData',
            'internalCostsGraphData',
            'projectSponsors',
            'projectManagers',
            'validationMessages',
        ]),
        downloadPdf() {
            return Routing.generate('app_contract_pdf', {id: this.contract.id});
        },
        proposedStartDate() {
            if (this.contract.frozen) {
                return this.contract.proposedStartDate;
            }

            return this.project.scheduledStartAt;
        },
        proposedEndDate() {
            if (this.contract.frozen) {
                return this.contract.proposedEndDate;
            }

            return this.project.scheduledFinishAt;
        },
        forecastStartDate() {
            if (this.contract.frozen) {
                return this.contract.forecastStartDate;
            }

            return this.project.forecastStartAt;
        },
        forecastEndDate() {
            if (this.contract.frozen) {
                return this.contract.forecastEndDate;
            }

            return this.project.forecastFinishAt;
        },
    },
    data: function() {
        return {
            descriptionEditor: null,
            eventEditor: null,
            showSaved: false,
            showFailed: false,
            showSavedComponent: false,
            showFailedComponent: false,
            showSponsorsManagers: false,
            objectiveDescription: null,
            limitationDescription: null,
            frozen: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../css/_common';
</style>

<style scoped lang="scss">
    @import '../../css/page-section';

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 1.25em;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 0.6em;
                }
            }
        }
    }

    .project-info {
        text-align: center;
        margin-bottom: 2em;

        span {
            display: inline-block;
            font-size: 1.5em;
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1.9em;
    }

    .header .btn-md {
        margin-top: 1.5em;
    }

    .members-big {
        margin: 1.9em 0 0;
    }

    .member-badge {
        &:before {
            display: none;
        }
    }

    .dates {
        .input-holder {
            width: 50%;

            &.left {
                margin-right: 15px;
            }

            &.right {
                margin-left: 15px;
            }
        }
    }

    .page-side {
        width: 50%;

        &.left {
            padding-right: 15px;
        }

        &.right {
            padding-top: 20px;
            padding-left: 15px;
        }
    }

    .btn-rounded {
        width: 220px;

        &.btn-empty {
            width: auto;
            font-size: 9px;
            padding: 0 26px;
        }
    }

    .st0{
        fill:none;
        stroke:#65BEA3;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
    }

    .pdf {
        margin: 1.9em 13px;
    }

    .download-pdf {
        padding-top: 2px;
    }

    .hr {
        margin: 1.9em 0;

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

    .input-holder {
        margin-bottom: 20px;
    }

    .disabledpicker {
        pointer-events: none;
        opacity: .5;
    }
</style>
