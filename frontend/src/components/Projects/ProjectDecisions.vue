<template>
    <div class="project-meetings page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_decisions') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-decisions-create-decision'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_decision') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <decisions-filters></decisions-filters>
            </div>
        </div>

        <div class="meetings-list">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive table-fixed">
                        <thead>
                            <tr>
                                <th class="cell-auto">{{ translateText('table_header_cell.id') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.event') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.category') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.status') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.due_date') }}</th>
                                <th>{{ translateText('table_header_cell.title') }}</th>
                                <th>{{ translateText('table_header_cell.responsible') }}</th>
                                <th class="cell-auto">{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>232</td>
                                <td>TP Meeting</td>
                                <td>Production</td>
                                <td class="danger-color">Undone</td>
                                <td>01.08.2017</td>
                                <td class="cell-wrap">Lorem ipsum dolor sit amet</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Andrea Sinclair'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>231</td>
                                <td>TP Meeting</td>
                                <td>Production</td>
                                <td class="danger-color">Undone</td>
                                <td>15.07.2017</td>
                                <td class="cell-wrap">Donec magna massa, tincidunt sit amet quam nec, pellentesque varius libero.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>230</td>
                                <td>EK Meeting</td>
                                <td>Production</td>
                                <td class="danger-color">Undone</td>
                                <td>01.11.2017</td>
                                <td class="cell-wrap">Donec id rhoncus justo, ac imperdiet purus. Fusce eleifend tortor vulputate leo interdum, eu malesuada ipsum ac imperdiet purus.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>229</td>
                                <td>Anlagenverwertung BTF</td>
                                <td>Quality Management</td>
                                <td class="second-color">Done</td>
                                <td>18.02.2017</td>
                                <td class="cell-wrap">Vestibulum vulputate mattis nibh vel iaculis.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Craig Pruess'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>228</td>
                                <td>Gebäudeplanung</td>
                                <td>Maintenance</td>
                                <td class="second-color">Done</td>
                                <td>15.07.2017</td>
                                <td class="cell-wrap">Etiam lectus orci.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'John Doe'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>228</td>
                                <td>EK Meeting</td>
                                <td>Logistics</td>
                                <td class="second-color">Done</td>
                                <td>07.03.2017</td>
                                <td class="cell-wrap">Fermentum vel lacinia sit amet.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Angella Patterstone'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>232</td>
                                <td>TP Meeting</td>
                                <td>Production</td>
                                <td class="second-color">Done</td>
                                <td>01.04.2017</td>
                                <td class="cell-wrap">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Andrea Sinclair'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>231</td>
                                <td>Gebäudeplanung</td>
                                <td>Logistics</td>
                                <td class="second-color">Done</td>
                                <td>15.06.2017</td>
                                <td class="cell-wrap">Donec magna massa, tincidunt sit amet quam nec, pellentesque varius libero.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>230</td>
                                <td>TP Meeting</td>
                                <td>Production</td>
                                <td class="danger-color">Undone</td>
                                <td>01.12.2017</td>
                                <td class="cell-wrap">Donec id rhoncus justo, ac imperdiet purus. Fusce eleifend tortor vulputate leo interdum, eu malesuada ipsum ac imperdiet purus.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Connan Wilder'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                            <tr>
                                <td>229</td>
                                <td>Gebäudeplanung</td>
                                <td>Quality Management</td>
                                <td class="second-color">Done</td>
                                <td>18.05.2017</td>
                                <td class="cell-wrap">Vestibulum vulputate mattis nibh vel iaculis.</td>
                                <td class="text-center">
                                    <div class="avatar" v-tooltip.top-center="'Craig Pruess'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_info')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_info')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_info')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="active">1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} 10 {{ translateText('message.results_out_of') }} 43</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DecisionsFilters from '../_common/_decisions-components/DecisionsFilters';
import VueScrollbar from 'vue2-scrollbar';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';

export default {
    components: {
        DecisionsFilters,
        VueScrollbar,
        ViewIcon,
        EditIcon,
        DeleteIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data: function() {
        return {
            pages: 4,
            page: 1,
            activePage: 1,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

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
