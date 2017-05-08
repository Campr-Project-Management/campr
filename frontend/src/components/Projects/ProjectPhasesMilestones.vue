<template>
    <div class="page-section">
        <!-- /// P&M Header /// -->
        <div class="header">
            <h1>Phases &amp; Milestones</h1>
        </div>  
        <!-- /// End P&M Header /// --> 

        <!-- /// P&M Timeline /// -->
        <vis-timeline></vis-timeline>
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
            <phase-filters></phase-filters>
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
                        <tbody v-if="projectPhases && projectPhases.length > 0">
                            <tr v-for='phase in projectPhases'>
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

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="active">1</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying 7 results out of 7</span>
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
            <milestone-filters></milestone-filters>
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
                        <tbody v-if="projectMilestones && projectMilestones.length">
                            <tr v-for="milestone in projectMilestones">
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

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="">1</span>
                    <span class="active">2</span>
                    <span class="">1</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying 10 results out of 21</span>
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
        this.getProjectPhases(this.$route.params.id);
        this.getProjectMilestones(this.$route.params.id);
        setTimeout(() => {
            console.log('pp', this.projectMilestones, this.projectMilestones.length);
        }, 5000);
    },
    methods: {
        ...mapActions(['getProjectPhases', 'getProjectMilestones']),
    },
    computed: mapGetters({
        projectPhases: 'projectPhases',
        projectMilestones: 'projectMilestones',
    }),
    data() {
        return {
            phasesActivePage: 0,
            milestonesActivePage: 0,
            // projectPhases: [
            //     {
            //         id: 1,
            //         name: 'phase 8',
            //         baseSchedule: {
            //             start: '11.12.2017',
            //             finish: '12.12.2018',
            //             duration: '89',
            //         },
            //         forecastSchedule: {
            //             start: '09.12.2017',
            //             finish: '12.12.2018',
            //             duration: '89',
            //         },
            //         actualSchedule: {
            //             start: '10.12.2017',
            //             finish: '11.12.2018',
            //             duration: '89',
            //         },
            //         status: 'Pending',
            //         responsible: {
            //             name: 'John',
            //             avatar: 'http://dev.campr.biz/uploads/avatars/60.jpg',
            //         },
            //     },
            // ],
            // projectMilestones: [
            //     {
            //         id: 2,
            //         name: 'Milestone 3',
            //         baseDueDate: '01.01.2018',
            //         forecastDueDate: '03.01.2018',
            //         actualDueDate: '10.01.2018',
            //         status: 'Reached',
            //         responsible: {
            //             name: 'John',
            //             avatar: 'http://dev.campr.biz/uploads/avatars/60.jpg',
            //         },
            //     },
            // ],
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
