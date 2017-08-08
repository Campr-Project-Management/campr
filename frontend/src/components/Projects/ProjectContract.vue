<template>
    <div class="project-contract">
        <div class="page-section flex">
            <div class="page-side left">
                <div class="header">
                    <div class="flex">
                        <h1>{{ translateText('message.project_contract') }}</h1>
                        <a href="#" class="pdf">
                            <svg version="1.1" id="Layer_1" width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 23.1 23.5" style="enable-background:new 0 0 23.1 23.5;" xml:space="preserve">
                                <g id="XMLID_252_">
                                    <g id="XMLID_627_">
                                        <polyline id="XMLID_724_" class="st0" points="13.6,16 15.3,17.7 17.1,16 		"/>
                                        <circle id="XMLID_723_" class="st0" cx="15.3" cy="15.6" r="4.2"/>
                                        <line id="XMLID_722_" class="st0" x1="15.3" y1="13.6" x2="15.3" y2="17.7"/>
                                    </g>
                                    <g id="XMLID_253_">
                                        <g id="XMLID_429_">
                                            <polyline id="XMLID_614_" class="st0" points="10.4,18.4 3.5,18.4 3.5,3.8 11.1,3.8 14.6,7.3 14.6,10.1 			"/>
                                            <polyline id="XMLID_539_" class="st0" points="11.1,3.8 11.1,7.3 14.6,7.3 			"/>
                                        </g>
                                    </g>
                                    <g id="XMLID_621_">
                                        <path id="XMLID_619_" class="st0" d="M8.5,11.7V9.3h0.5c0.5,0,0.9,0.5,0.9,1.2c0,0.7-0.4,1.2-0.9,1.2H8.5z"/>
                                        <polyline id="XMLID_618_" class="st0" points="10.8,11.7 10.8,9.3 12,9.3 		"/>
                                        <line id="XMLID_616_" class="st0" x1="10.8" y1="10.3" x2="11.5" y2="10.3"/>
                                        <path id="XMLID_243_" class="st0" d="M6.1,11.7V9.3h0.6c0.3,0,0.6,0.3,0.6,0.6S7,10.5,6.7,10.5H6.1"/>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
                <input-field v-model="description" type="textarea" v-bind:label="translateText('message.project_description')" :content="contract.description"></input-field>
                <input-field v-model="projectStartEvent" type="textarea" v-bind:label="translateText('message.project_start_event')" :content="contract.projectStartEvent"></input-field>

                <div class="flex flex-space-between dates">
                    <div class="input-holder left">
                        <label class="active">{{ translateText('label.proposed_start_date') }}</label>
                        <datepicker v-model="proposedStartDate" format="dd - MM - yyyy" :value="contract.proposedStartDate"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                    <div class="input-holder right">
                        <label class="active">{{ translateText('label.proposed_end_date') }}</label>
                        <datepicker v-model="proposedEndDate" format="dd - MM - yyyy" :value="contract.proposedEndDate"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="flex flex-space-between dates right">
                    <div class="input-holder left">
                        <label class="active">{{ translateText('label.forecast_start_date') }}</label>
                        <datepicker v-model="forecastStartDate" format="dd - MM - yyyy" :value="contract.forecastStartDate"></datepicker>
                        <calendar-icon fill="middle-fill" stroke="middle-stroke"></calendar-icon>
                    </div>
                    <div class="input-holder right">
                        <label class="active">{{ translateText('label.forecast_end_date') }}</label>
                        <datepicker v-model="forecastEndDate" format="dd - MM - yyyy" :value="contract.forecastEndDate"></datepicker>
                        <calendar-icon fill="lighter-fill" stroke="lighter-stroke"></calendar-icon>
                    </div>
                </div>

                <div class="header">
                    <div class="flex">
                        <h1>{{ translateText('message.project_objectives') }}</h1>
                    </div>
                </div>

                <div v-dragula="colOne" drake="objectives">
                    <drag-box v-for="(item, index) in project.projectObjectives" v-bind:item="item" v-bind:index="index" type="objective"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field v-model="objectiveTitle" type="text" v-bind:label="translateText('message.new_objective_title')"></input-field>
                <error
                    v-if="validationMessages.createProjectObjectiveForm && validationMessages.title && validationMessages.title.length"
                    v-for="message in validationMessages.title"
                    :message="message" />
                <input-field v-model="objectiveDescription" type="textarea" v-bind:label="translateText('message.new_objective_description')"></input-field>
                <error
                    v-if="validationMessages.createProjectObjectiveForm && validationMessages.description && validationMessages.description.length"
                    v-for="message in validationMessages.description"
                    :message="message" />
                <div class="flex flex-direction-reverse">
                    <a v-on:click="createProjectObjective()" class="btn-rounded">{{ translateText('message.add_objective') }} +</a>
                </div>
                <div class="header">
                    <div class="flex">
                        <h1>{{ translateText('message.project_deliverables') }}</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="deliverables">
                    <drag-box v-for="(item, index) in project.projectDeliverables" v-bind:item="item" v-bind:index="index" type="deliverable"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field v-model="deliverableDescription" type="text" v-bind:label="translateText('message.new_project_deliverable')"></input-field>
                <error
                    v-if="validationMessages.createProjectDeliverableForm && validationMessages.description && validationMessages.description.length"
                    v-for="message in validationMessages.description"
                    :message="message" />
                <div class="flex flex-direction-reverse">
                    <a v-on:click="createProjectDeliverable()" class="btn-rounded">{{ translateText('message.add_deliverable') }} +</a>
                </div>
            </div>

            <div class="page-side right">
                <div class="header">
                    <h2 class="clickable" @click="toggleSponsorsManagers()">{{ translateText('message.sponsors_managers') }}
                        <i class="fa fa-angle-up" v-if="showSponsorsManagers"></i>
                        <i class="fa fa-angle-down" v-if="!showSponsorsManagers"></i>
                    </h2>
                    <router-link :to="{name: 'project-organization'}">
                        <a class="btn-rounded btn-md btn-empty">{{ translateText('message.view_team') }}</a>
                    </router-link>
                </div>
                <div class="flex flex-row flex-center members-big" v-if="showSponsorsManagers">
                    <member-badge v-for="item in projectSponsors" v-bind:item="item" size="small"></member-badge>
                    <member-badge v-for="item in projectManagers" v-bind:item="item" size="small"></member-badge>
                </div>
                <div class="header">
                    <div class="flex">
                        <h1>{{ translateText('message.project_limitations') }}</h1>
                    </div>
                </div>
                <div v-dragula="colOne" drake="limitations">
                    <drag-box v-for="(item, index) in project.projectLimitations" v-bind:item="item" v-bind:index="index" type="limitation"></drag-box>
                </div>
                <div class="hr small"></div>
                <input-field v-model="limitationDescription" type="text" v-bind:label="translateText('message.new_project_limitation')"></input-field>
                <error
                    v-if="validationMessages.createProjectLimitationForm && validationMessages.description && validationMessages.description.length"
                    v-for="message in validationMessages.description"
                    :message="message" />
                <div class="flex flex-direction-reverse">
                    <a v-on:click="createProjectLimitation()" class="btn-rounded">{{ translateText('message.add_limitation') }} +</a>
                </div>
                <div class="header">
                    <div class="flex">
                        <h2>{{ translateText('message.internal_resources') }}</h2>
                    </div>
                </div>
                <vue-chart
                        chart-type="ColumnChart"
                        :columns="columns"
                        :rows="rowsInternal"
                        :options="options"
                ></vue-chart>

                <div class="header">
                    <div class="flex">
                        <h2>{{ translateText('message.external_resources') }}</h2>
                    </div>
                </div>
                <vue-chart
                        chart-type="ColumnChart"
                        :columns="columns"
                        :rows="rowsExternal"
                        :options="options"
                ></vue-chart>
                <div class="hr"></div>
                <div class="flex buttons flex-center">
                    <a v-on:click="updateProjectContract()" class="btn-rounded second-bg">{{ translateText('button.save') }}</a>
                    <a v-if="contract && contract.id" class="btn-rounded second-bg flex flex-center download-pdf" :href="downloadPdf">
                        {{ translateText('button.download_pdf') }}<downloadbutton-icon fill="white-fill"></downloadbutton-icon>
                    </a>
                </div>
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
import InputField from '../_common/_form-components/InputField.vue';
import CalendarIcon from '../_common/_icons/CalendarIcon.vue';
import DownloadbuttonIcon from '../_common/_icons/DownloadbuttonIcon.vue';
import EyeIcon from '../_common/_icons/EyeIcon.vue';
import MemberBadge from '../_common/MemberBadge.vue';
import AlertModal from '../_common/AlertModal.vue';
import datepicker from 'vuejs-datepicker';
import moment from 'moment';
import router from '../../router';
import Error from '../_common/_messages/Error.vue';

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
            this.proposedStartDate = this.contract.proposedStartDate ? new Date(this.contract.proposedStartDate) : new Date();
            this.proposedEndDate = this.contract.proposedEndDate ? new Date(this.contract.proposedEndDate) : new Date();
            this.forecastStartDate = this.contract.forecastStartDate ? new Date(this.contract.forecastStartDate) : new Date();
            this.forecastEndDate = this.contract.forecastEndDate ? new Date(this.contract.forecastEndDate) : new Date();
        },
        project(value) {
            this.rowsInternal = [
                [
                    this.translateText('message.total'),
                    parseInt(this.projectResourcesForGraph.internal.base || 0, 10),
                    parseInt(this.projectResourcesForGraph.internal.change || 0, 10),
                    parseInt(this.projectResourcesForGraph.internal.actual || 0, 10),
                    parseInt(this.projectResourcesForGraph.internal.remaining || 0, 10),
                    parseInt(this.projectResourcesForGraph.internal.forecast || 0, 10),
                ],
            ];
            this.rowsExternal = [
                [
                    this.translateText('message.total'),
                    parseInt(this.projectResourcesForGraph.external.base || 0, 10),
                    parseInt(this.projectResourcesForGraph.external.change || 0, 10),
                    parseInt(this.projectResourcesForGraph.external.actual || 0, 10),
                    parseInt(this.projectResourcesForGraph.external.remaining || 0, 10),
                    parseInt(this.projectResourcesForGraph.external.forecast || 0, 10),
                ],
            ];
            this.options.vAxis.maxValue = Math.max(
                parseInt(this.projectResourcesForGraph.internal.base || 0, 10),
                parseInt(this.projectResourcesForGraph.internal.change || 0, 10),
                parseInt(this.projectResourcesForGraph.internal.actual || 0, 10),
                parseInt(this.projectResourcesForGraph.internal.remaining || 0, 10),
                parseInt(this.projectResourcesForGraph.internal.forecast || 0, 10),
                parseInt(this.projectResourcesForGraph.external.base || 0, 10),
                parseInt(this.projectResourcesForGraph.external.change || 0, 10),
                parseInt(this.projectResourcesForGraph.external.actual || 0, 10),
                parseInt(this.projectResourcesForGraph.external.remaining || 0, 10),
                parseInt(this.projectResourcesForGraph.external.forecast || 0, 10)
            );
        },
    },
    methods: {
        ...mapActions(['getProjectById', 'getContractByProjectId', 'updateContract',
            'createContract', 'createObjective', 'createLimitation', 'createDeliverable',
            'editObjective', 'editLimitation', 'editDeliverable', 'reorderObjectives',
            'reorderLimitations', 'reorderDeliverables', 'getProjectResourcesForGraph',
            'getProjectUsers',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        toggleSponsorsManagers: function() {
            this.showSponsorsManagers = !this.showSponsorsManagers;
        },
        updateProjectContract: function() {
            let data = {
                projectId: this.$route.params.id,
                name: this.project.name + '-contract',
                description: this.description,
                projectStartEvent: this.projectStartEvent,
                proposedStartDate: moment(this.proposedStartDate).format('DD-MM-YYYY'),
                proposedEndDate: moment(this.proposedEndDate).format('DD-MM-YYYY'),
                forecastStartDate: moment(this.forecastStartDate).format('DD-MM-YYYY'),
                forecastEndDate: moment(this.forecastEndDate).format('DD-MM-YYYY'),
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
                description: this.objectiveDescription,
                sequence: this.project.projectObjectives.length,
            };
            this
                .createObjective(data)
                .then(
                    () => {
                        this.showSavedComponent = true;
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
        this.getProjectResourcesForGraph(this.$route.params.id);
        this.getProjectUsers({id: this.$route.params.id});
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
    computed: {
        ...mapGetters({
            project: 'project',
            contract: 'currentContract',
            projectResourcesForGraph: 'projectResourcesForGraph',
            projectSponsors: 'projectSponsors',
            projectManagers: 'projectManagers',
            validationMessages: 'validationMessages',
        }),
        downloadPdf() {
            return Routing.generate('app_contract_pdf', {id: this.contract.id});
        },
    },
    data: function() {
        return {
            showSaved: false,
            showFailed: false,
            showSavedComponent: false,
            showFailedComponent: false,
            columns: [
                {
                    'type': 'string',
                    'label': Translator.trans('message.total'),
                },
                {
                    'type': 'number',
                    'label': Translator.trans('label.base'),
                },
                {
                    'type': 'number',
                    'label': Translator.trans('label.change'),
                },
                {
                    'type': 'number',
                    'label': Translator.trans('label.actual'),
                },
                {
                    'type': 'number',
                    'label': Translator.trans('label.remaining'),
                },
                {
                    'type': 'number',
                    'label': Translator.trans('label.forecast'),
                },
            ],
            rowsInternal: [
                [
                    Translator.trans('message.total'), 0, 0, 0, 0, 0,
                ],
            ],
            rowsExternal: [
                [Translator.trans('message.total'), 0, 0, 0, 0, 0],
            ],
            options: {
                title: Translator.trans('message.resource_chart'),
                hAxis: {
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                vAxis: {
                    title: '',
                    minValue: 0,
                    maxValue: 0,
                    textStyle: {
                        color: '#D8DAE5',
                    },
                },
                width: '100%',
                height: 350,
                curveType: 'function',
                colors: ['#5FC3A5', '#A05555', '#646EA0', '#2E3D60', '#D8DAE5'],
                backgroundColor: '#191E37',
                titleTextStyle: {
                    color: '#D8DAE5',
                },
                legend: {
                    position: 'bottom',
                    maxLines: 5,
                },
                legendTextStyle: {
                    color: '#D8DAE5',
                },
            },
            proposedStartDate: new Date(),
            proposedEndDate: new Date(),
            forecastStartDate: new Date(),
            forecastEndDate: new Date(),
            showSponsorsManagers: false,
            objectiveDescription: null,
            limitationDescription: null,
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

    .header .btn-md {
        margin-top: 24px;
    }

    .members-big {
        margin: 0 -15px;
    }

    .members-small {
        margin: 0 -16px;
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

    .project-contract {
        padding-bottom: 50px;
    }

    .page-side {
        width: 50%;

        &.left {
            padding-right: 15px;
        }

        &.right {
            padding-top: 55px;
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
        margin: 30px 13px;
    }

    .download-pdf {
        padding-top: 2px;
    }

    .hr {
        margin: 67px 0;

        &.small {
            margin: 20px 0;
        }
    }

    .buttons {
        a {
            margin: 0 10px;
        }
    }

    .input-holder {
        margin-bottom: 20px;
    }
</style>
