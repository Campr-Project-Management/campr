<template>
    <div class="project-meetings page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_infos') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-infos-new'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_info') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <infos-filters
                    v-on:set-user="setFiltersUser"
                    v-on:set-info-status="setFiltersInfoStatus"
                    v-on:set-info-category="setFiltersInfoCategory"
                    v-on:clear-filters="doClearFilters">
                </infos-filters>
            </div>
        </div>

        <div class="meetings-list">
            <scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive table-fixed">
                        <thead>
                            <tr>
                                <th class="cell-auto">{{ translateText('table_header_cell.id') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.category') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.status') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.due_date') }}</th>
                                <th>{{ translateText('table_header_cell.topic') }}</th>
                                <th>{{ translateText('table_header_cell.responsible') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!infos || !infos.length">
                                <td colspan="7">{{ translateText('label.no_data') }}</td>
                            </tr>
                            <tr v-if="infos && infos.length" v-for="info in infos">
                                <td>{{ info.id }}</td>
                                <td>{{ translateText(info.infoCategoryName) }}</td>
                                <td :style="{color: info.infoStatusColor}">{{ translateText(info.infoStatusName) }}</td>
                                <td>{{ displayDate(info) }}</td>
                                <td class="cell-wrap">{{ info.topic }}</td>
                                <td>
                                    <div
                                        v-if="info.responsibility"
                                        class="avatar"
                                        v-tooltip.top-center="info.responsibilityFullName"
                                        :style="{ backgroundImage: (info.responsibilityAvatar ? 'url(/uploads/avatars/' + info.responsibilityAvatar + ')' : 'url('+info.responsibilityGravatar+')') }">
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <router-link :to="{name: 'project-infos-view', params: {projectId: info.project, infoId: info.id}}" class="btn-icon" v-tooltip.top-center="translateText('button.view_info')">
                                            <view-icon fill="second-fill"></view-icon>
                                        </router-link>
                                        <router-link :to="{name: 'project-infos-edit', params: {projectId: info.project, infoId: info.id}}" class="btn-icon" v-tooltip.top-center="translateText('button.edit_info')">
                                            <edit-icon fill="second-fill"></edit-icon>
                                        </router-link>
                                        <a href="javascript:void(0)" @click="tryDeleteInfo(info.id)" class="btn-icon" v-tooltip.top-center="translateText('button.delete_info')">
                                            <delete-icon fill="danger-fill"></delete-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>

            <pagination
                :current-page="infosFilters.currentPage"
                :number-of-pages="infosFilters.numberOfPages"
                v-on:change-page="setFiltersInfoPage"/>
        </div>
    </div>
</template>

<script>
import InfosFilters from '../_common/_infos-components/InfosFilters';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import Pagination from '../_common/Pagination.vue';
import {mapActions, mapGetters} from 'vuex';
import _ from 'lodash';

export default {
    components: {
        InfosFilters,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        Pagination,
    },
    created() {
        this.getInfosByProject({id: this.$route.params.id});
    },
    methods: {
        ...mapActions([
            'getInfosByProject',
            'setInfoFiltersUser',
            'setInfoFiltersInfoStatus',
            'setInfoFiltersInfoCategory',
            'setInfoPage',
            'clearFilters',
            'deleteInfo',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        displayDate({dueDate}) {
            if (!dueDate) {
                return '-';
            }

            const dt = new Date(dueDate);
            if (isNaN(dt.getTime())) {
                return '-';
            }
            const out = [dt.getFullYear()];
            let month = dt.getMonth() + 1;
            let day = dt.getDay();

            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }

            out.push(month);
            out.push(day);

            return out.join('-');
        },
        setFiltersUser(val) {
            if (_.isArray(val) && val.length) {
                this.setInfoFiltersUser({user: val[0]});
            } else {
                this.setInfoFiltersUser({user: null});
            }
            this.getInfosByProject({id: this.$route.params.id});
        },
        setFiltersInfoStatus(infoStatus) {
            this.setInfoFiltersInfoStatus({infoStatus});
            this.getInfosByProject({id: this.$route.params.id});
        },
        setFiltersInfoCategory(infoCategory) {
            this.setInfoFiltersInfoCategory({infoCategory});
            this.getInfosByProject({id: this.$route.params.id});
        },
        setFiltersInfoCategory(infoCategory) {
            this.setInfoFiltersInfoCategory({infoCategory});
            this.getInfosByProject({id: this.$route.params.id});
        },
        setFiltersInfoPage(page) {
            this.setInfoPage({page});
            this.getInfosByProject({id: this.$route.params.id});
        },
        doClearFilters() {
            this.clearFilters();
            this.getInfosByProject({id: this.$route.params.id});
        },
        tryDeleteInfo(id) {
            if (confirm(this.translate('message.delete_info'))) {
                this
                    .deleteInfo(id)
                    .then(
                        () => {
                            this.getInfosByProject({id: this.$route.params.id});
                        },
                        () => {
                            this.getInfosByProject({id: this.$route.params.id});
                        }
                    )
                ;
            }
        },
    },
    computed: {
        ...mapGetters(['infos', 'infosFilters']),
    },
    data: function() {
        return {};
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

        .scroll-wrapper {
            width: 100%;

            tbody {
                tr {
                    height: 50px;
                }
            }
        }
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
