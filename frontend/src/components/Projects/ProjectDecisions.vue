<template>
    <div class="project-meetings page-section">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_decision') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="removeDecision()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>

        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_decisions') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-decisions-create-decision'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_decision') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <decisions-filters :updateFilters="applyFilters"></decisions-filters>
            </div>
        </div>

        <div class="meetings-list">
            <scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive table-fixed">
                        <thead>
                            <tr>
                                <th class="cell-auto">{{ translateText('table_header_cell.id') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.event') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.category') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.due_date') }}</th>
                                <th>{{ translateText('table_header_cell.title') }}</th>
                                <th>{{ translateText('table_header_cell.responsible') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="decision in decisions.items">
                                <td>{{ decision.id }}</td>
                                <td>{{ decision.meetingName }}</td>
                                <td>{{ decision.decisionCategoryName }}</td>
                                <td>{{ decision.dueDate }}</td>
                                <td class="cell-wrap">{{ decision.title }}</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="decision.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + decision.responsibilityAvatar + ')' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link class="btn-icon" :to="{name: 'project-decisions-view-decision', params:{decisionId: decision.id}}" v-tooltip.top-center="translateText('message.view_decision')">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link class="btn-icon" :to="{name: 'project-decisions-edit-decision', params:{decisionId: decision.id}}" v-tooltip.top-center="translateText('message.edit_decision')">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>
                                        <a href="javascript:void(0)" @click="initDeleteModal(decision)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
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
                    <span class="pagination-info">{{ translateText('message.displaying') }} {{ decisions.items.length }} {{ translateText('message.results_out_of') }} {{ decisions.totalItems }}</span>
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

export default {
    components: {
        DecisionsFilters,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        Modal,
    },
    methods: {
        ...mapActions(['getProjectDecisions', 'setDecisionsFilters', 'deleteDecision']),
        translateText: function(text) {
            return this.translate(text);
        },
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
