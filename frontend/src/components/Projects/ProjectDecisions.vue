<template>
    <div class="project-meetings page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translate('message.delete_decision') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="removeDecision()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translate('message.project_decisions') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-decisions-create-decision'}" class="btn-rounded btn-auto second-bg">{{ translate('message.create_new_decision') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <decisions-filters :updateFilters="applyFilters"></decisions-filters>
            </div>
        </div>

        <div class="meetings-list">
            <scrollbar class="table-wrapper customScrollbar">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive table-fixed">
                        <thead>
                            <tr>
                                <th class="cell-auto">{{ translate('table_header_cell.id') }}</th>
                                <th class="cell-auto">{{ translate('table_header_cell.event') }}</th>
                                <th class="cell-auto">{{ translate('table_header_cell.category') }}</th>
                                <th class="cell-auto">{{ translate('table_header_cell.status') }}</th>
                                <th class="cell-auto">{{ translate('table_header_cell.due_date') }}</th>
                                <th>{{ translate('table_header_cell.title') }}</th>
                                <th>{{ translate('table_header_cell.responsible') }}</th>
                                <th class="cell-auto">{{ translate('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="decision in decisions.items">
                                <td>{{ decision.id }}</td>
                                <td>{{ decision.meetingName }}</td>
                                <td>{{ decision.decisionCategoryName }}</td>
                                <td>
                                    <span v-if="decision.isDone" class="success-color">{{ translate('choices.done') }}</span>
                                    <span v-else class="danger-color">{{ translate('choices.undone') }}</span>
                                </td>
                                <td>{{ decision.dueDate | date }}</td>
                                <td class="cell-wrap">{{ decision.title }}</td>
                                <td class="text-center">
                                    <user-avatar
                                            size="small"
                                            :name="decision.responsibilityFullName"
                                            :tooltip="decision.responsibilityFullName"
                                            :url="decision.responsibilityAvatarUrl"/>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link class="btn-icon" :to="{name: 'project-decisions-view-decision', params:{decisionId: decision.id}}" v-tooltip.top-center="translate('message.view_decision')">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link class="btn-icon" :to="{name: 'project-decisions-edit-decision', params:{decisionId: decision.id}}" v-tooltip.top-center="translate('message.edit_decision')">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>
                                        <a href="javascript:void(0)" @click="initDeleteModal(decision)" class="btn-icon" v-tooltip.top-center="translate('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <div class="flex flex-direction-reverse flex-v-center" v-if="pages > 1">
                <div class="pagination">
                    <span v-if="pages > 1" v-for="page in pages" v-bind:class="{'active': page == activePage}" @click="changePage(page)" >{{ page }}</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translate('message.displaying') }} {{ decisions.items.length }} {{ translate('message.results_out_of') }} {{ decisions.totalItems }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DecisionsFilters from '../_common/_decisions-components/DecisionsFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import {mapActions, mapGetters} from 'vuex';
import Modal from '../_common/Modal';
import UserAvatar from '../_common/UserAvatar';

export default {
    components: {
        UserAvatar,
        DecisionsFilters,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        Modal,
    },
    methods: {
        ...mapActions(['getProjectDecisions', 'setDecisionsFilters', 'deleteDecision']),
        changePage: function(page) {
            this.activePage = page;
            this.getData();
        },
        getData: function() {
            this.getProjectDecisions({
                projectId: this.$route.params.id,
                queryParams: {
                    page: this.activePage,
                },
            });
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setDecisionsFilters(filterObj);
            this.activePage = 1;
            this.getData();
        },
        initDeleteModal: function(decision) {
            this.showDeleteModal = true;
            this.decisionId = decision.id;
        },
        removeDecision: function() {
            if (this.decisionId) {
                this.deleteDecision({id: this.decisionId});
                this.showDeleteModal = false;
                this.decisionId = null;
            }
        },
    },
    computed: {
        ...mapGetters(['decisions']),
        pages: function() {
            return Math.ceil(this.decisions.totalItems / this.perPage);
        },
        perPage: function() {
            return this.decisions.pageSize;
        },
    },
    created() {
        this.getData();
    },
    data: function() {
        return {
            activePage: 1,
            showDeleteModal: false,
            decisionId: null,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/variables';
    @import '../../css/mixins';
    @import '../../css/common';

    .full-filters {
        margin: 20px 0;
    }

    .meetings-list {
        overflow: hidden;
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    } 

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    } 

    .cell-wrap {
        white-space: normal;
    }
</style>
