<template>
    <div>
        <div class="overflow-hidden">
            <scrollbar class="table-wrapper customScrollbar">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>{{ translate('table_header_cell.team_member') }}</th>
                            <th>{{ translate('table_header_cell.department') }}</th>
                            <th>{{ translate('table_header_cell.present') }}</th>
                            <th>{{ translate('table_header_cell.distribution_list') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for='(participant, index) in value' :key="participant.id+'-'+participant.isPresent">
                            <td>
                                <div class="avatars flex flex-v-center">
                                    <div>
                                        <div class="avatar" v-tooltip.top-center="participant.userFullName" :style="{ backgroundImage: 'url('+participant.userAvatar+')' }"></div>
                                    </div>
                                    <span>{{ participant.userFullName }}</span>
                                </div>
                            </td>
                            <td>
                                <span v-for="(department, index) in participant.departments">{{ department }}<span v-if="index < participant.departments.length - 1">,</span></span>
                            </td>
                            <td class="text-center switchers">
                                <switches :selected="value[index].isPresent" v-model="value[index].isPresent" />
                            </td>
                            <td class="text-center switchers">
                                <switches :selected="value[index].inDistributionList" v-model="value[index].inDistributionList" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>
        </div>

        <!--<div v-if="participants && participants.length > 0" class="flex flex-direction-reverse flex-v-center">-->
            <!--<div class="pagination flex flex-center" v-if="participants && participants.length > 0">-->
                <!--<span v-if="participantsPages > 1" v-for="page in participantsPages" v-bind:class="{'active': page == participantsActivePage}" @click="changeParticipantsPage(page)">{{ page }}</span>-->
            <!--</div>-->
            <!--<div>-->
                <!--<span class="pagination-info">{{ translate('message.displaying') }} {{ displayedParticipants.length }} {{ translate('message.results_out_of') }} {{ participants.length }}</span>-->
            <!--</div>-->
        <!--</div>-->
    </div>
</template>
<script>
import Switches from '../../3rdparty/vue-switches';

export default {
    props: {
        value: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    components: {
        Switches,
    },
};
</script>
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .avatars {
        > div {
            height: 34px;
            padding: 2px 0;
            display: inline-block;
        }

        span {
            margin-left: 10px;
            line-height: 34px;
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
</style>
