<template>
    <div class="row">
        <div class="page-section">
            <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                <p class="modal-title">{{ translateText('message.delete_phase') }}</p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                    <a href="javascript:void(0)" @click="deletePhase()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
                </div>
            </modal>
        </div>
        <div class="col-md-6 custom-col-md-6">
            <div class="view-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <div>
                            <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_phases_and_milestones') }}
                            </router-link>
                            <h1 class="title">{{ phase.name }}</h1>

                            <router-link v-if="phase.parent" :to="{name: 'project-phases-view-phase', params:{id: projectId, phaseId: phase.parent}}" class="parent-phase router-link-active uppercase middle-color">
                                {{ translateText('message.parent_phase') }}
                                <span class="second-color">{{ phase.parentName }}</span>
                            </router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <!-- /// Content /// -->
                <div class="phase-content">
                    <div v-html="phase.content"></div>
                    <hr>
                    <h3>{{ translateText('message.schedule') }}</h3>

                    <div class="table-wrapper">
                        <schedule-dates-table
                                :base-start-at="phase.scheduledStartAt"
                                :base-finish-at="phase.scheduledFinishAt"
                                :base-duration-days="phase.scheduledDurationDays"
                                :forecast-start-at="phase.forecastStartAt"
                                :forecast-finish-at="phase.forecastFinishAt"
                                :forecast-duration-days="phase.forecastDurationDays"
                                :actual-start-at="phase.actualStartAt"
                                :actual-finish-at="phase.actualFinishAt"
                                :actual-duration-days="phase.actualDurationDays"/>
                    </div>

                    <h3 v-if="phaseWorkPackages.length > 0">
                        {{ translateText('message.first_task') }}:
                        <router-link :to="{name: 'project-task-management-view', params: { id: projectId, taskId: phaseWorkPackages[0].id }}" class="second-color uppercase">
                            <strong>{{ phaseWorkPackages[0].name }}</strong>
                        </router-link>
                    </h3>
                    <h3 v-if="phaseWorkPackages.length > 1">
                        {{ translateText('message.last_task') }}:
                        <router-link :to="{name: 'project-task-management-view', params: { id: projectId, taskId: phaseWorkPackages[phaseWorkPackages.length-1].id }}" class="second-color uppercase">
                            <strong>{{ phaseWorkPackages[phaseWorkPackages.length-1].name }}</strong>
                        </router-link>
                    </h3>

                    <hr>

                    <div class="row responsible-status">
                        <div class="col-md-6">
                            <h3>{{ translateText('label.responsible') }}</h3>
                            <div class="user-info">
                                <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + phase.responsibilityAvatar + ')' }"></div>
                                <span class="uppercase">
                                    {{ phase.responsibilityFullName }}
                                    <router-link :to="{name: 'project-phases-and-milestones'}" class="second-color">
                                        @{{ phase.responsibilityFullName }}
                                    </router-link>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>{{ translateText('message.status') }}</h3>
                            <div class="status uppercase">
                                <span>{{ translateText(phase.workPackageStatusName) }}</span>
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
                            <router-link :to="{name: 'project-phases-edit-phase', params: { id: projectId, phaseId: phase.id } }" class="btn-rounded btn-auto second-bg">
                                {{ translateText('button.edit_phase') }}
                            </router-link>
                            <button @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_phase') }}</button>
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
        ...mapActions(['getProjectPhase', 'deleteProjectPhase', 'getPhaseWorkpackages']),
        translateText(text) {
            return this.translate(text);
        },
        deletePhase: function() {
            this.showDeleteModal = false;
            this.deleteProjectPhase(this.$route.params.phaseId);
            router.push({name: 'project-phases-and-milestones', params: {id: this.projectId}});
        },
    },
    computed: mapGetters({
        phase: 'currentPhase',
        phaseWorkPackages: 'phaseWorkPackages',
    }),
    created() {
        if (this.$route.params.phaseId) {
            this.getProjectPhase(this.$route.params.phaseId);
            this.getPhaseWorkpackages({
                id: this.$route.params.phaseId,
                type: 2,
                orderBy: 'scheduledStartAt',
                order: 'ASC',
            });
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
