<template>
    <div class="row">
        <div class="page-section">
            <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                <p class="modal-title">{{ translateText('message.delete_milestone') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deleteMilestone()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
                </div>
            </modal>
        </div>
        <div class="col-md-6 custom-col-md-6">
            <div class="view-milestone page-section">
                <!-- /// Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <div>
                            <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_phases_and_milestones') }}
                            </router-link>
                            <h1 class="title">{{ milestone.name }}</h1>

                            <router-link v-if="milestone.phaseName" :to="{name: 'project-phases-view-phase', params:{id: projectId, phaseId: milestone.phase}}" class="parent-phase router-link-active uppercase middle-color">
                                {{ translateText('message.parent_phase') }}
                                <span class="second-color">{{ milestone.phaseName }}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <!-- /// Content /// -->
                <div class="milestone-content">
                    <div v-html="milestone.content"></div>
                    <hr>
                    <h3>{{ translateText('message.schedule') }}</h3>

                    <div class="table-wrapper">
                        <schedule-dates-table
                                :base-start-at="milestone.scheduledStartAt"
                                :base-finish-at="milestone.scheduledFinishAt"
                                :base-duration-days="milestone.scheduledDurationDays"
                                :forecast-start-at="milestone.forecastStartAt"
                                :forecast-finish-at="milestone.forecastFinishAt"
                                :forecast-duration-days="milestone.forecastDurationDays"
                                :actual-start-at="milestone.actualStartAt"
                                :actual-finish-at="milestone.actualFinishAt"
                                :actual-duration-days="milestone.actualDurationDays"
                                :show-due-schedule="true"/>
                    </div>

                    <hr>

                    <div class="row responsible-status">
                        <div class="col-md-6">
                            <h3>{{ translateText('label.responsible') }}</h3>
                            <div class="user-info">
                                <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + milestone.responsibilityAvatar + ')' }"></div>
                                <span class="uppercase">
                                    {{ milestone.responsibilityFullName }}
                                    <router-link :to="{name: 'project-phases-and-milestones'}" class="second-color">
                                        @{{ milestone.responsibilityFullName }}
                                    </router-link>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>{{ translateText('message.status') }}</h3>
                            <div class="status uppercase">
                                <span>{{ translateText(milestone.workPackageStatusName) }}</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">
                            {{ translateText('button.cancel') }}
                        </router-link>
                        <div class="flex flex-v-center">
                            <router-link :to="{name: 'project-milestones-edit-milestone', params: { id: projectId, phaseId: milestone.id } }" class="btn-rounded btn-auto second-bg">
                                {{ translateText('button.edit_milestone') }}
                            </router-link>
                            <button @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_milestone') }}</button>
                        </div>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
                <!-- /// End Content /// -->
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import Modal from '../../_common/Modal';
import router from '../../../router';
import ScheduleDatesTable from '../../_common/ScheduleDatesTable';

export default {
    components: {
        ScheduleDatesTable,
        Modal,
        router,
    },
    methods: {
        ...mapActions(['getProjectMilestone', 'deleteProjectMilestone']),
        translateText(text) {
            return this.translate(text);
        },
        getDuration: function(startDate, endDate) {
            let end = moment(endDate);
            let start = moment(startDate);

            return !isNaN(end.diff(start, 'days')) ? end.diff(start, 'days') : '-';
        },
        deleteMilestone: function() {
            this.showDeleteModal = false;
            this.deleteProjectMilestone(this.$route.params.milestoneId);
            router.push({name: 'project-phases-and-milestones', params: {id: this.projectId}});
        },
    },
    computed: mapGetters({
        milestone: 'currentMilestone',
    }),
    created() {
        if (this.$route.params.milestoneId) {
            this.getProjectMilestone(this.$route.params.milestoneId);
        }
    },
    data() {
        return {
            projectId: this.$route.params.id,
            showDeleteModal: false,
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/common';

    .btn-rounded {
        margin-left: 20px;
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;        
        margin: 0 10px 0 0;  
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        vertical-align: middle;
        @include border-radius(50%);
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

        .main-list .member {
            border-top: 1px solid $darkColor;
        }

        .results {
            width: 600px;
        }
    }
</style>
