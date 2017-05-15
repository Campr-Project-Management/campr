<template>
    <div class="page-section">
        <!-- /// P&M Header /// -->
        <div class="header">
            <h1>Phases &amp; Milestones</h1>
        </div>  
        <!-- /// End P&M Header /// --> 

        <!-- /// P&M Timeline /// -->
        <vis-timeline :pmData="pmData"></vis-timeline>
        <!-- /// End P&M Timeline /// -->    

        <!-- /// Phases Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>Project Phases</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-phases-create-phase'}" class="btn-rounded btn-auto second-bg">Add New Phase</router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <phase-filters :selectEndDate="setPhaseFilterEndDate" :selectStartDate="setPhaseFilterStartDate" :selectResponsible="setPhaseFilterResponsible" :selectStatus="setPhasesFilterStatus"></phase-filters>
        </div>
        <!-- /// End Phases Header /// -->

        <!-- /// Phases List /// -->
        <div class="phases-list margintop20">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="small-cell">ID</th>
                                <th>Phase</th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Base Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Forecast Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Actual Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th>Status</th>
                                <th>Responsible</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody v-if="projectPhases.items && projectPhases.items.length > 0">
                            <tr v-for='phase in projectPhases.items'>
                                <td class="small-cell">{{phase.id}}</td>
                                <td>{{phase.name}}</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr v-if="phase.baseSchedule">
                                            <td class="text-center">{{phase.baseSchedule.start}}</td>
                                            <td class="text-center">{{phase.baseSchedule.finish}}</td>
                                            <td class="text-center">{{phase.baseSchedule.duration}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr v-if="phase.forecastSchedule">
                                            <td class="text-center">{{phase.forecastSchedule.start}}</td>
                                            <td class="text-center">{{phase.forecastSchedule.finish}}</td>
                                            <td class="text-center">{{phase.forecastSchedule.duration}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr v-if="phase.actualSchedule">
                                            <td class="text-center">{{phase.actualSchedule.start}}</td>
                                            <td class="text-center">{{phase.actualSchedule.finish}}</td>
                                            <td class="text-center">{{phase.actualSchedule.duration}}</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td>{{phase.status}}</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: ' + phase.responsibilityFullName"> 
                                        <img :src="phase.responsibilityAvatar"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div v-if="projectPhases && projectPhases.items" class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center" v-if="projectPhases && projectPhases.totalItems > 0">
                    <span v-for="page in phasesPages" v-bind:class="{'active': page == phasesActivePage}" @click="changePhasePage(page)">{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying {{projectPhases.items.length}} results out of {{projectPhases.totalItems}}</span>
                </div>
            </div>
        </div>
        <!-- /// End Phases List /// -->

        <!-- /// Milestones Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>Project Milestones</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-milestones-create-milestone'}" class="btn-rounded btn-auto second-bg">Add New Milestone</router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <milestone-filters :selectDueDate="setMilestonesFilterDueDue" :selectPhase="setMilestonesFilterPhase" :selectResponsible="setMilestonesFilterResponsible" :selectStatus="setMilestonesFilterStatus"></milestone-filters>
        </div>
        <!-- /// End Milestones Header /// -->

        <!-- /// Milestones List /// -->
        <div class="phases-list margintop20">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="small-cell">ID</th>
                                <th>Milestone</th>
                                <th>Base due date</th>
                                <th>Forecast due date</th>
                                <th>Actual due date</th>
                                <th>Status</th>
                                <th>Responsible</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody v-if="projectMilestones.items && projectMilestones.items.length">
                            <tr v-for="milestone in projectMilestones.items">
                                <td class="small-cell">{{milestone.id}}</td>
                                <td>{{milestone.name}}</td>
                                <td>{{milestone.baseDueDate}}</td>
                                <td>{{milestone.forecastDueDate}}</td>
                                <td>{{milestone.actualDueDate}}</td>
                                <td>{{milestone.status}}</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: ' + milestone.responsibilityFullName"> 
                                        <img :src="milestone.responsibilityAvatar">
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div v-if="projectMilestones && projectMilestones.items"  class="flex flex-direction-reverse flex-v-center">
                <div class="pagination flex flex-center" v-if="projectMilestones && projectMilestones.totalItems > 0">
                    <span v-for="page in milestonesPages" v-bind:class="{'active': page == milestoneActivePage}" @click="changeMilestonePage(page)">{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying {{projectMilestones.items.length}} results out of {{projectMilestones.totalItems}}</span>
                </div>
            </div>
        </div>
        <!-- /// End Milestones List /// -->
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import VueScrollbar from 'vue2-scrollbar';
import VisTimeline from '../_common/_phases-and-milestones-components/VisTimeline';
import PhaseFilters from '../_common/_phases-and-milestones-components/PhaseFilters';
import MilestoneFilters from '../_common/_phases-and-milestones-components/MilestoneFilters';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import ViewIcon from '../_common/_icons/ViewIcon';

export default {
    components: {
        VueScrollbar,
        VisTimeline,
        PhaseFilters,
        MilestoneFilters,
        EditIcon,
        DeleteIcon,
        ViewIcon,
    },
    created() {
        this.getProjectPhases({projectId: this.$route.params.id,
            apiParams: {
                page: 1,
            },
        });
        this.getProjectMilestones(this.$route.params.id, {page: 1});
    },
    methods: {
        ...mapActions(['getProjectPhases', 'getProjectMilestones', 'setPhasesFiters', 'setMilestonesFiters']),
        changePhasePage: function(page) {
            this.phasesActivePage = page;
            this.getProjectPhases({projectId: this.$route.params.id,
                apiParams: {
                    page: page,
                },
            });
        },
        changeMilestonesPage: function(page) {
            this.milestoneActivePage = page;
            this.getProjectMilestones(this.$route.params.id,
                {
                    page: page,
                },
            );
        },
        setPhasesFilterStatus: function(value) {
            this.setPhasesFiters({status: value});
        },
        setPhaseFilterResponsible: function(value) {
            this.setPhasesFiters({responsible: value});
        },
        setMilestonesFilterStatus: function(value) {
            this.setMilestonesFiters({status: value});
        },
        setMilestonesFilterResponsible: function(value) {
            this.setMilestonesFiters({responsible: value});
        },
        setMilestonesFilterPhase: function(value) {
            this.setMilestonesFiters({phase: value});
        },
        setMilestonesFilterDueDue: function(value) {
            this.setMilestonesFiters({dueDate: value});
        },
        setPhaseFilterStartDate: function(value) {
            this.setPhasesFiters({startDate: value});
        },
        setPhaseFilterEndDate: function(value) {
            this.setPhasesFiters({endDate: value});
        },
    },
    computed: {
        ...mapGetters({
            projectPhases: 'projectPhases',
            projectMilestones: 'projectMilestones',
        }),
        phasesPages: function() {
            return Math.ceil(this.projectPhases.totalItems / 4);
        },
        milestonesPages: function() {
            return Math.ceil(this.projectMilestones.totalItems / 4);
        },
        pmData: function() {
            let items = [];
            if (this.projectPhases && this.projectPhases.items) {
                items = items.concat(this.projectPhases.items.map((item) => {
                    return {
                        id: item.id,
                        group: 0,
                        content: item.name,
                        start: new Date(item.scheduledStartAt),
                        end: new Date(item.scheduledFinishAt),
                        value: item.workPackageStatus,
                    };
                }));
            }

            if (this.projectMilestones && this.projectMilestones.items) {
                items = items.concat(this.projectMilestones.items.map((item) => {
                    return {
                        id: item.id,
                        group: 1,
                        content: item.name,
                        start: new Date(item.scheduledFinishAt),
                        value: item.workPackageStatus,
                    };
                }));
            }
            return items;
        },
    },
    data() {
        return {
            phasesActivePage: 0,
            milestonesActivePage: 0,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';

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
</style>
