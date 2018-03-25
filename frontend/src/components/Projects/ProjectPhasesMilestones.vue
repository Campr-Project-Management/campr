<template>
    <div class="page-section">
        <modal v-if="showDeletePhaseModal" @close="showDeletePhaseModal = false">
            <p class="modal-title">{{ translateText('message.delete_phase') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeletePhaseModal = false"
                   class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteSelectedPhase()" class="btn-rounded">{{
                    translateText('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showDeleteMilestoneModal" @close="showDeleteMilestoneModal = false">
            <p class="modal-title">{{ translateText('message.delete_milestone') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteMilestoneModal = false"
                   class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteSelectedMilestone()" class="btn-rounded">{{
                    translateText('message.yes') }}</a>
            </div>
        </modal>

        <template v-if="pmData.length > 0">
            <!-- /// P&M Header /// -->
            <div class="header">
                <h1>{{ translateText('message.phases_milestones') }}</h1>
            </div>
            <!-- /// End P&M Header /// -->

            <!-- /// P&M Timeline /// -->
            <vis-timeline :items="pmData" :with-phases="true"/>
            <!-- /// End P&M Timeline /// -->
        </template>

        <!-- /// Phases Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>{{ translateText('message.project_phases') }}</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-phases-create-phase'}" class="btn-rounded btn-auto second-bg">{{
                    translateText('button.add_new_phase') }}
                </router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <phase-filters
                    :clearAllFilters="clearPhaseFilters"
                    :selectStartDate="setPhaseFilterStart"
                    :selectEndDate="setPhaseFilterEnd"
                    :selectResponsible="setPhaseFilterResponsible"
                    :selectStatus="setPhasesFilterStatus"/>
        </div>
        <!-- /// End Phases Header /// -->

        <!-- /// Phases List /// -->
        <div class="phases-list margintop20">
            <scrollbar>
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>{{ translateText('table_header_cell.phase') }}</th>
                            <th class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <th class="text-center" colspan="3">{{
                                            translateText('table_header_cell.base_schedule') }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ translateText('table_header_cell.start') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.finish') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </table>
                            </th>
                            <th class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <th class="text-center" colspan="3">{{
                                            translateText('table_header_cell.forecast_schedule') }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ translateText('table_header_cell.start') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.finish') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </table>
                            </th>
                            <th class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <th class="text-center" colspan="3">{{
                                            translateText('table_header_cell.actual_schedule') }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">{{ translateText('table_header_cell.start') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.finish') }}</th>
                                        <th class="text-center">{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </table>
                            </th>
                            <th>{{ translateText('table_header_cell.status') }}</th>
                            <th>{{ translateText('table_header_cell.responsible') }}</th>
                            <th>{{ translateText('table_header_cell.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody v-if="hasPhases">
                        <tr v-for='phase in projectPhases.items'>
                            <td>{{ phase.name }}</td>
                            <td class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <td class="text-center">{{ phase.scheduledStartAt | date }}</td>
                                        <td class="text-center">{{ phase.scheduledFinishAt | date }}</td>
                                        <td class="text-center">{{ phase.scheduledDurationDays > 0 ?
                                            $formatNumber(phase.scheduledDurationDays) : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <td class="text-center">{{ phase.forecastStartAt | date }}</td>
                                        <td class="text-center">{{ phase.forecastFinishAt | date }}</td>
                                        <td class="text-center">{{ phase.forecastDurationDays ?
                                            $formatNumber(phase.forecastDurationDays) : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="no-padding">
                                <table class="table inner-table">
                                    <tr>
                                        <td class="text-center">{{ phase.actualStartAt | date }}</td>
                                        <td class="text-center">{{ phase.actualFinishAt | date }}</td>
                                        <td class="text-center">{{ phase.actualDurationDays ?
                                            $formatNumber(phase.actualDurationDays) : '-' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>{{ translateText(phase.workPackageStatusName) }}</td>
                            <td class="small-avatar text-center">
                                <div class="user-avatar"
                                     v-tooltip.top-center="translateText('message.phase_responsible') + phase.responsibilityFullName">
                                    <img :src="phase.responsibilityAvatar"/>
                                </div>
                            </td>
                            <td>
                                <router-link
                                        :to="{name: 'project-phases-view-phase', params: { id: projectId, phaseId: phase.id } }"
                                        class="btn-icon">
                                    <view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <router-link
                                        :to="{name: 'project-phases-edit-phase', params: { id: projectId, phaseId: phase.id } }"
                                        class="btn-icon">
                                    <edit-icon fill="second-fill"></edit-icon>
                                </router-link>
                                <button @click="initDeletePhaseModal(phase)" data-target="#phase-1-delete-modal"
                                        data-toggle="modal" type="button" class="btn-icon">
                                    <delete-icon fill="danger-fill"></delete-icon>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                        <tr>
                            <td colspan="7">{{ translate('label.no_data') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <div v-if="projectPhases && projectPhases.items" class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center" v-if="projectPhases && projectPhases.totalItems > 0">
                    <span v-if="phasesPages > 1" v-for="page in phasesPages"
                          v-bind:class="{'active': page == phasesActivePage}"
                          @click="changePhasePage(page)">{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ projectPhases.items.length }} {{ translateText('message.results_out_of') }} {{ projectPhases.totalItems }}</span>
                </div>
            </div>
        </div>
        <!-- /// End Phases List /// -->

        <!-- /// Milestones Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>{{ translate('message.project_milestones') }}</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-milestones-create-milestone'}" class="btn-rounded btn-auto second-bg">
                    {{ translateText('button.add_new_milestone') }}
                </router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <milestone-filters
                    :clearAllFilters="clearMilestoneFilters"
                    :selectDueDate="setMilestonesFilterDueDate"
                    :selectPhase="setMilestonesFilterPhase"
                    :selectResponsible="setMilestonesFilterResponsible"
                    :selectStatus="setMilestonesFilterStatus"/>
        </div>
        <!-- /// End Milestones Header /// -->

        <!-- /// Milestones List /// -->
        <div class="phases-list margintop20">
            <scrollbar>
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>{{ translateText('table_header_cell.milestone') }}</th>
                            <th>{{ translateText('table_header_cell.base_due_date') }}</th>
                            <th>{{ translateText('table_header_cell.forecast_due_date') }}</th>
                            <th>{{ translateText('table_header_cell.actual_due_date') }}</th>
                            <th>{{ translateText('table_header_cell.status') }}</th>
                            <th>{{ translateText('table_header_cell.responsible') }}</th>
                            <th>{{ translateText('table_header_cell.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody v-if="hasMilestones">
                        <tr v-for="milestone in projectMilestones.items">
                            <td>{{ milestone.name }}</td>
                            <td>{{ milestone.scheduledFinishAt | date }}</td>
                            <td>{{ milestone.forecastFinishAt | date }}</td>
                            <td>{{ milestone.actualFinishAt | date }}</td>
                            <td>{{ translateText(milestone.workPackageStatusName) }}</td>
                            <td class="small-avatar text-center">
                                <div class="user-avatar"
                                     v-tooltip.top-center="translateText('message.milestone_responsible') + milestone.responsibilityFullName">
                                    <img :src="milestone.responsibilityAvatar">
                                </div>
                            </td>
                            <td>
                                <router-link
                                        :to="{name: 'project-phases-view-milestone', params: { id: projectId, milestoneId: milestone.id } }"
                                        class="btn-icon">
                                    <view-icon fill="second-fill"></view-icon>
                                </router-link>
                                <router-link
                                        :to="{name: 'project-milestones-edit-milestone', params: { id: projectId, milestoneId: milestone.id } }"
                                        class="btn-icon">
                                    <edit-icon fill="second-fill"></edit-icon>
                                </router-link>
                                <button @click="initDeleteMilestoneModal(milestone)" data-target="#phase-1-delete-modal"
                                        data-toggle="modal" type="button" class="btn-icon">
                                    <delete-icon fill="danger-fill"></delete-icon>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                        <tr>
                            <td colspan="7">{{ translate('label.no_data') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <div v-if="hasMilestones" class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center" v-if="projectMilestones && projectMilestones.totalItems > 0">
                    <span v-if="milestonesPages > 1" v-for="page in milestonesPages"
                          v-bind:class="{'active': page == milestoneActivePage}" @click="changeMilestonesPage(page)">{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ projectMilestones.items.length }} {{ translateText('message.results_out_of') }} {{ projectMilestones.totalItems }}</span>
                </div>
            </div>
        </div>
        <!-- /// End Milestones List /// -->

        <alert-modal v-if="showAlertModal" @close="showAlertModal = false" :body="validationMessages.dependency"/>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import VisTimeline from '../_common/_phases-and-milestones-components/VisTimeline';
    import PhaseFilters from '../_common/_phases-and-milestones-components/PhaseFilters';
    import MilestoneFilters from '../_common/_phases-and-milestones-components/MilestoneFilters';
    import EditIcon from '../_common/_icons/EditIcon';
    import DeleteIcon from '../_common/_icons/DeleteIcon';
    import ViewIcon from '../_common/_icons/ViewIcon';
    import moment from 'moment';
    import Modal from '../_common/Modal';
    import AlertModal from '../_common/AlertModal.vue';

    export default {
        components: {
            VisTimeline,
            PhaseFilters,
            MilestoneFilters,
            EditIcon,
            DeleteIcon,
            ViewIcon,
            Modal,
            AlertModal,
        },
        watch: {
            validationMessages(value) {
                if (this.validationMessages && this.validationMessages.dependency) {
                    this.showAlertModal = true;
                }
            },
        },
        created() {
            this.getProjectPhases({
                projectId: this.$route.params.id,
                apiParams: {
                    page: 1,
                },
            });
            this.getProjectMilestones({
                projectId: this.$route.params.id,
                apiParams: {
                    page: 1,
                },
            });
            this.getProjectMilestones({
                projectId: this.$route.params.id,
            });
        },
        methods: {
            ...mapActions([
                'getProjectPhases', 'getProjectMilestones',
                'setPhasesFilters', 'setMilestonesFilters', 'deleteProjectPhase',
                'deleteProjectMilestone',
            ]),
            translateText: function(text) {
                return this.translate(text);
            },
            changePhasePage: function(page) {
                this.phasesActivePage = page;
                this.refreshPhasesData();
            },
            changeMilestonesPage: function(page) {
                this.milestonesActivePage = page;
                this.refreshMilestonesData();
            },
            initDeletePhaseModal(phase) {
                this.showDeletePhaseModal = true;
                this.phaseId = phase.id;
            },
            initDeleteMilestoneModal(milestone) {
                this.showDeleteMilestoneModal = true;
                this.milestoneId = milestone.id;
            },
            deleteSelectedPhase() {
                this.showDeletePhaseModal = false;
                this.deleteProjectPhase(this.phaseId);
            },
            deleteSelectedMilestone() {
                this.showDeleteMilestoneModal = false;
                this.deleteProjectMilestone(this.milestoneId);
            },
            clearPhaseFilters: function(value) {
                this.setPhasesFilters({clear: value});
                this.refreshPhasesData();
            },
            setPhasesFilterStatus: function(value) {
                this.setPhasesFilters({status: value});
                this.refreshPhasesData();
            },
            setPhaseFilterResponsible: function(value) {
                this.setPhasesFilters({responsible: value});
                this.refreshPhasesData();
            },
            clearMilestoneFilters: function(value) {
                this.setMilestonesFilters({clear: value});
                this.refreshMilestonesData();
            },
            setMilestonesFilterStatus: function(value) {
                this.setMilestonesFilters({status: value});
                this.refreshMilestonesData();
            },
            setMilestonesFilterResponsible: function(value) {
                this.setMilestonesFilters({responsible: value});
                this.refreshMilestonesData();
            },
            setMilestonesFilterPhase: function(value) {
                this.setMilestonesFilters({phase: value});
                this.refreshMilestonesData();
            },
            setMilestonesFilterDueDate: function(value) {
                this.setMilestonesFilters({dueDate: value ? moment(value).format('YYYY-MM-DD') : null});
                this.refreshMilestonesData();
            },
            setPhaseFilterStart: function(value) {
                this.setPhasesFilters({startDate: value ? moment(value).format('YYYY-MM-DD') : null});
                this.refreshPhasesData();
            },
            setPhaseFilterEnd: function(value) {
                this.setPhasesFilters({endDate: value ? moment(value).format('YYYY-MM-DD') : null});
                this.refreshPhasesData();
            },
            refreshPhasesData: function() {
                this.getProjectPhases({
                    projectId: this.$route.params.id,
                    apiParams: {
                        page: this.phasesActivePage,
                    },
                });
            },
            refreshMilestonesData: function() {
                this.getProjectMilestones({
                    projectId: this.$route.params.id,
                    apiParams: {
                        page: this.milestonesActivePage,
                    },
                });
            },
        },
        computed: {
            ...mapGetters({
                projectPhases: 'projectPhases',
                projectMilestones: 'projectMilestones',
                allProjectMilestones: 'allProjectMilestones',
                allProjectPhases: 'allProjectPhases',
                validationMessages: 'validationMessages',
            }),
            hasMilestones() {
                return this.projectMilestones.items && this.projectMilestones.items.length;
            },
            hasPhases() {
                return this.projectPhases.items && this.projectPhases.items.length > 0;
            },
            phasesPages: function() {
                return Math.ceil(this.projectPhases.totalItems / this.phasesPerPage);
            },
            phasesPerPage: function() {
                return this.projectPhases.pageSize;
            },
            milestonesPages: function() {
                return Math.ceil(this.projectMilestones.totalItems / this.milestonesPerPage);
            },
            milestonesPerPage: function() {
                return this.projectMilestones.pageSize;
            },
            pmData: function() {
                let items = [];
                if (this.allProjectPhases && this.allProjectPhases.items) {
                    items = items.concat(this.allProjectPhases.items.map((item) => {
                        return {
                            id: item.id,
                            group: 0,
                            content: item.name,
                            start: new Date(item.actualStartAt || item.scheduledStartAt || item.forecastStartAt),
                            end: new Date(item.actualFinishAt || item.scheduledFinishAt || item.forecastFinishAt),
                            value: item.workPackageStatus,
                            data: item,
                        };
                    }));
                }

                if (this.allProjectMilestones && this.allProjectMilestones.items) {
                    items = items.concat(this.allProjectMilestones.items.map((item) => {
                        return {
                            id: item.id,
                            group: 1,
                            content: item.name,
                            start: new Date(item.actualFinishAt || item.scheduledFinishAt || item.forecastFinishAt),
                            value: item.workPackageStatus,
                            className: item.isKeyMilestone ? 'key-milestone' : '',
                            data: item,
                        };
                    }));
                }

                return items;
            },
        },
        data() {
            return {
                phasesActivePage: 1,
                milestonesActivePage: 1,
                projectId: this.$route.params.id,
                showDeleteMilestoneModal: false,
                showDeletePhaseModal: false,
                milestoneId: '',
                phaseId: '',
                showAlertModal: false,
            };
        },
    };
</script>

<style lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';
    @import '../../css/common';

    .datepicker-clear-button {
        position: absolute;
        right: 0;
        top: -14px;
        color: $dangerColor;
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';
    @import '../../css/common';

    .table {
        .table {
            background-color: transparent;
            width: 100%;

            &.inner-table {
                width: 370px;
                table-layout: fixed;

                td, th {
                    width: 33.333333% !important;
                }
            }
        }

        .small-cell {
            width: 90px;
            padding-right: 0;
        }
    }

    .modal {
        .modal-title {
            text-transform: uppercase;
            text-align: center;
            font-size: 18px;
            letter-spacing: 1.8px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .input-holder {
            margin-bottom: 30px;
        }

        .main-list .member {
            border-top: 1px solid $darkColor;
        }

        .results {
            width: 600px;
        }
    }
</style>
