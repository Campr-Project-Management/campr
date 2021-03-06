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
                        {{ translate('message.project_contract') }}
                    </div>

                    <div class="project-info">
                        <span>{{ translate('message.scope') }}: {{ project.projectScopeName || '-' }}</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span>{{ translate('message.category') }}: {{ project.projectCategoryName || '-' }}</span>
                    </div>

                    <div class="flex buttons flex-center" v-if="contract && contract.id">
                        <a class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                            {{ translate('button.download_pdf') }}<downloadbutton-icon fill="white-fill" />
                        </a>
                        <button v-if="!frozen" @click="freezeContract()" class="btn-rounded second-bg">{{ translate('button.freeze_contract') }}</button>
                        <h4 v-else>{{ translate('message.contract_frozen') }}</h4>
                    </div>
                </div>
                <!-- /// End Header /// -->
            </div>

            <div class="row">
                <!-- /// Project Description /// -->
                <div class="col-md-6">
                    <editor
                        id="description"
                        v-model="description"
                        :label="'message.project_description'"/>
                </div>
                <!-- End Project Description -->

                <!-- /// Project Start Event /// -->
                <div class="col-md-6">
                    <editor
                        id="projectStartEvent"
                        v-model="projectStartEvent"
                        :label="'label.project_start_event'"/>
                </div>
                <!-- /// End Project Start Event /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Project Schedule /// -->
                <div class="col-md-6 col-md-offset-3">
                    <h3 class="text-center">{{ translate('message.schedule') }}</h3>
                    <br>
                    <div class="flex flex-space-between dates">
                        <div class="input-holder left" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translate('label.proposed_start_date') }}</label>
                            <date-field v-model="proposedStartDate"/>
                            <error at-path="proposedStartDate"/>
                        </div>
                        <div class="input-holder right" :class="{disabledpicker: frozen }">
                            <label class="active">{{ translate('label.proposed_end_date') }}</label>
                            <date-field v-model="proposedEndDate"/>
                            <error at-path="proposedEndDate"/>
                        </div>
                    </div>
                </div>
                <!-- /// End Project Schedule /// -->
            </div>

            <div class="row">
                <!-- /// Project Sponsors & Managers /// -->
                <div class="col-md-12">
                    <div class="header">
                        <h3 class="clickable" @click="toggleSponsorsManagers()">{{ translate('message.sponsors_managers') }}
                            <i class="fa fa-angle-down" v-if="!showSponsorsManagers"></i>
                            <i class="fa fa-angle-up" v-if="showSponsorsManagers"></i>
                        </h3>
                    </div>
                    <div class="text-center">
                        <router-link class="btn-rounded btn-md btn-empty" :to="{name: 'project-organization'}">
                            {{ translate('message.view_team') }}
                        </router-link>
                    </div>
                    <div class="flex flex-row flex-center members-big" v-if="showSponsorsManagers">
                        <member-badge v-for="item in project.projectSponsors" v-bind:item="item" size="small" />
                        <member-badge v-for="item in project.projectManagers" v-bind:item="item" size="small" />
                        <p v-if="project.projectSponsors.length === 0 && project.projectManagers.length === 0">{{ translate('label.no_data') }}</p>
                    </div>
                </div>
                <!-- /// End Project Sponsors & Managers /// -->
            </div>

            <div class="row margintop40">
                <!-- /// Project Objectives /// -->
                <div class="col-md-4">
                    <h3>{{ translate('message.project_objectives') }}</h3>

                    <div v-dragula="colOne" drake="objectives" v-if="project.projectObjectives && project.projectObjectives.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectObjectives" v-bind:item="item" v-bind:index="index" type="objective" />
                    </div>
                    <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="objectiveTitle" :content="objectiveTitle" type="text" v-bind:label="translate('message.new_objective_title')" />
                    <error
                        v-if="validationMessages.createProjectObjectiveForm && validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <a v-on:click="createProjectObjective()" class="btn-rounded btn-auto">{{ translate('message.add_objective') }} +</a>
                    </div>
                </div>
                <!-- /// End Project Objectives /// -->

                <!-- /// Project Limitations /// -->
                <div class="col-md-4">
                    <h3>{{ translate('message.project_limitations') }}</h3>

                    <div v-dragula="colOne" drake="limitations" v-if="project.projectLimitations && project.projectLimitations.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectLimitations" v-bind:item="item" v-bind:index="index" type="limitation" />
                    </div>
                    <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="limitationDescription" :content="limitationDescription" type="text" v-bind:label="translate('message.new_project_limitation')" />
                    <error
                        v-if="validationMessages.createProjectLimitationForm && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <a v-on:click="createProjectLimitation()" class="btn-rounded btn-auto">{{ translate('message.add_limitation') }} +</a>
                    </div>
                </div>
                <!-- /// End Project Limitations /// -->

                <!-- /// Project Deliverables /// -->
                <div class="col-md-4">
                    <h3>{{ translate('message.project_deliverables') }}</h3>
                    <div v-dragula="colOne" drake="deliverables" v-if="project.projectDeliverables && project.projectDeliverables.length > 0">
                        <drag-box :disabled="frozen" v-for="(item, index) in project.projectDeliverables" v-bind:item="item" v-bind:index="index" type="deliverable" />
                    </div>
                    <p class="notice" v-else>{{ translate('label.no_data') }}</p>
                    <div class="hr small"></div>
                    <input-field v-if="!frozen" v-model="deliverableDescription" :content="deliverableDescription" type="text" v-bind:label="translate('message.new_project_deliverable')" />
                    <error
                        v-if="validationMessages.createProjectDeliverableForm && validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <div v-if="!frozen" class="flex flex-direction-reverse">
                        <button v-on:click="createProjectDeliverable()" class="btn-rounded btn-auto">{{ translate('message.add_deliverable') }} +</button>
                    </div>
                </div>
                <!-- /// End Project Deliverables /// -->
            </div>

            <div v-if="isInternalCostsModuleActive || isExternalCostsModuleActive"
                 class="row margintop40">
                <!-- /// Project Internal Costs /// -->
                <div v-if="isInternalCostsModuleActive" class="col-md-6">
                    <h3>{{ translate('message.internal_costs') }}</h3>
                    <chart :data="internalCostsGraphData.byPhase | graphData"/>
                </div>
                <!-- /// End Project Internal Costs /// -->

                <!-- /// Project External Costs /// -->
                <div v-if="isExternalCostsModuleActive" class="col-md-6">
                    <h3>{{ translate('message.external_costs') }}</h3>
                    <chart :data="externalCostsGraphData.byPhase | graphData"/>
                </div>
                <!-- /// End Project External Costs /// -->
            </div>

            <div class="row">
                <!-- /// Footer Buttons /// -->
                <div class="col-md-12">
                    <div class="hr"></div>
                    <div class="flex flex-space-between buttons">
                        <div class="flex">
                            <a v-if="!frozen" v-on:click="updateProjectContract()" class="btn-rounded second-bg">{{ translate('button.save') }}</a>

                            <switches
                                    :disabled="frozen"
                                    v-model="isApproved"
                            v-on:updateProjectContract="approveContract"/>

                            <div v-if="isApproved" class="toggle-approved">{{ translate('label.approved_and_started') }}</div>
                            <div v-else class="toggle-approved">{{ translate('label.approve_and_start') }}</div>
                        </div>

                        <div class="flex">
                            <a v-if="contract && contract.id" class="btn-rounded flex flex-center download-pdf" :href="downloadPdf">
                                {{ translate('button.download_pdf') }}<downloadbutton-icon fill="white-fill" />
                            </a>
                            <button v-if="!frozen" @click="freezeContract()" class="btn-rounded second-bg">{{ translate('button.freeze_contract') }}</button>
                        </div>
                    </div>
                </div>
                <!-- /// End Footer Buttons /// -->
            </div>
        </div>

        <!-- Contract stuff -->
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />
        <!-- Contract component stuff -->
        <alert-modal v-if="showFailedComponent" @close="showFailedComponent = false" body="message.saved" />
    </div>
</template>

<script>
import Vue from 'vue';
import {mapGetters, mapActions} from 'vuex';
import DragBox from './TaskManagement/DragBox.vue';
import Chart from './Charts/CostsChart.vue';
import InputField from '../_common/_form-components/InputField.vue';
import DownloadbuttonIcon from '../_common/_icons/DownloadbuttonIcon.vue';
import EyeIcon from '../_common/_icons/EyeIcon.vue';
import MemberBadge from '../_common/MemberBadge.vue';
import AlertModal from '../_common/AlertModal.vue';
import router from '../../router';
import Error from '../_common/_messages/Error.vue';
import Editor from '../_common/Editor.vue';
import moment from 'moment';
import Switches from '../3rdparty/vue-switches';
import DateField from '../_common/_form-components/DateField';
import {
    MODULE_INTERNAL_COSTS,
    MODULE_EXTERNAL_COSTS,
} from '../../helpers/project-module';

export default {
    components: {
        DateField,
        DragBox,
        InputField,
        DownloadbuttonIcon,
        EyeIcon,
        MemberBadge,
        AlertModal,
        Error,
        Editor,
        Chart,
        Switches,
    },
    watch: {
        showSaved(value) {
            if (value === true) {
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
            this.description = this.contract.description;
            this.projectStartEvent = this.contract.projectStartEvent;
            this.proposedStartDate = this.contract.proposedStartDate
                ? moment(this.contract.proposedStartDate, 'YYYY-MM-DD').format()
                : null
            ;
            this.proposedEndDate = this.contract.proposedEndDate
                ? moment(this.contract.proposedEndDate, 'YYYY-MM-DD').format()
                : null
            ;
            this.approvedAt = this.contract.approvedAt || '';
        },
    },
    methods: {
        ...mapActions([
            'getProjectById', 'getContractByProjectId', 'updateContract',
            'createContract', 'createObjective', 'createLimitation', 'createDeliverable',
            'editObjective', 'editLimitation', 'editDeliverable', 'reorderObjectives',
            'reorderLimitations', 'reorderDeliverables', 'getProjectExternalCostsGraphData',
            'getProjectUsers', 'getProjectInternalCostsGraphData', 'emptyValidationMessages',
            'editProject',
        ]),
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
        approveContract() {
            let setApprovedAt = null;
            let projectStatusId = 1; // set project as "not started"
            if ($('label.vue-switcher input').is(':checked')) {
                setApprovedAt = moment(new Date()).format('YYYY-MM-DD HH:mm:ss');
                projectStatusId = 2; // set project as "in progress"
            }

            let data = {
                approvedAt: setApprovedAt,
            };

            if (this.contract.id) {
                data.id = this.contract.id;
                this.updateContract(data);
            } else {
                this.createContract(data);
            }

            // update project status
            this.editProject({
                projectId: this.$route.params.id,
                status: projectStatusId,
            });
        },
        updateProjectContract() {
            let data = {
                projectId: this.$route.params.id,
                name: this.project.name + ' - contract',
                description: this.description,
                projectStartEvent: this.projectStartEvent,
                proposedStartDate: moment(this.proposedStartDate).format('DD-MM-YYYY'),
                proposedEndDate: moment(this.proposedEndDate).format('DD-MM-YYYY'),
                approvedAt: this.approvedAt,
            };

            localStorage.removeItem('contract');

            if (this.contract.id) {
                data.id = this.contract.id;
                this
                    .updateContract(data)
                    .then(
                        (response) => {
                            if (response.body && response.body.error) {
                                this.showFailed = true;
                            } else {
                                this.showSaved = true;
                            }
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
                        (response) => {
                            if (response.body && response.body.error) {
                                this.showFailed = true;
                            } else {
                                this.showSaved = true;
                            }
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
        isModuleActive(module) {
            return this.modules.indexOf(module) >= 0;
        },
        getLocalStorageContract: function() {
            let contract = localStorage.getItem('contract');
            if(!contract) {
                contract = {};
            } else {
                contract = JSON.parse(localStorage.getItem('contract'));
            }

            return contract;
        },
        setLocalStorageContract: function(contractObj) {
            localStorage.setItem('contract', JSON.stringify(contractObj));
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
    },
    mounted() {
        let vm = this;

        document.getElementById('description').onkeyup = function() {
            let contract = vm.getLocalStorageContract();
            contract.description = document.getElementById('description').children[0].innerHTML;
            vm.setLocalStorageContract(contract);
        };

        document.getElementById('projectStartEvent').onkeyup = function() {
            let contract = vm.getLocalStorageContract();
            contract.startEvent = document.getElementById('projectStartEvent').children[0].innerHTML;
            vm.setLocalStorageContract(contract);
        };

        let contract = vm.getLocalStorageContract();

        if (contract.description) {
            setTimeout(function() {
                document.getElementById('description').children[0].innerHTML = contract.description;
            }, 1000);
        }

        if (contract.startEvent) {
            setTimeout(function() {
                document.getElementById('projectStartEvent').children[0].innerHTML = contract.startEvent;
            }, 2000);
        }
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
            'validationMessages',
        ]),
        downloadPdf() {
            return Routing.generate('app_contract_pdf', {id: this.contract.id});
        },
        isApproved: {
            set(value) {
                if (value) {
                    if (this.approvedAt === '') {
                        this.approvedAt = moment(new Date()).format('YYYY-MM-DD HH:mm:ss');
                    }
                } else {
                    this.approvedAt = '';
                }
            },
            get() {
                return (this.approvedAt !== '');
            },
        },
        modules() {
            if (!this.project) {
                return [];
            }

            return this.project.projectModules || [];
        },
        isInternalCostsModuleActive() {
            return this.isModuleActive(MODULE_INTERNAL_COSTS);
        },
        isExternalCostsModuleActive() {
            return this.isModuleActive(MODULE_EXTERNAL_COSTS);
        },
    },
    filters: {
        graphData(value) {
            if (!value) {
                return {};
            }

            let data = {};
            value.forEach((row) => {
                data[row.name] = row.values;
            });

            return data;
        },
    },
    data() {
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
            description: '',
            projectStartEvent: '',
            approvedAt: '',
            proposedStartDate: moment(new Date()).format('DD-MM-YYYY'),
            proposedEndDate: moment(new Date()).format('DD-MM-YYYY'),
        };
    },
    beforeRouteLeave(to, from, next) {
        if (localStorage.getItem('contract')) {
            if (!window.confirm('Changes you made may not be saved.')) {
                return;
            }
        }
        localStorage.removeItem('contract');
        next();
    },
};

window.onbeforeunload = function(event) {
    if (localStorage.getItem('contract')) {
        if (!confirm('Changes you made may not be saved.')) {
            localStorage.removeItem('contract');
            return false;
        }
    }
};

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
    @import '../../css/_common';
</style>

<style scoped lang="scss">
    @import '../../css/page-section';

    .toggle-approved {
        line-height: 3em;
        margin-left: 1em;
    }

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
