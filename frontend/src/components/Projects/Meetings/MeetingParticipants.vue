<template>
    <div>
        <div class="overflow-hidden">
            <scrollbar class="table-wrapper customScrollbar">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th>{{ translateText('table_header_cell.team_member') }}</th>
                            <th>{{ translateText('table_header_cell.department') }}</th>
                            <th>{{ translateText('table_header_cell.present') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for='participant in displayedParticipants' :key="participant.id">
                            <td>
                                <div class="avatars flex flex-v-center">
                                    <div>
                                        <div class="avatar" v-tooltip.top-center="participant.fullName" :style="{ backgroundImage: 'url('+participant.avatar+')' }"></div>
                                    </div>
                                    <span>{{ participant.fullName }}</span>
                                </div>
                            </td>
                            <td>
                                <span v-for="(department, index) in participant.departments">{{ department }}<span v-if="index < participant.departments.length - 1">,</span></span>
                            </td>
                            <td class="text-center switchers">
                                <switches @click.native="updateIsPresent(participant)" v-model="participant.isPresent" :selected="participant.isPresent" v-if="!createMeeting"></switches>
                                <switches @click.native="addIsPresent(participant)" v-model="participant.isPresent" :selected="participant.isPresent" v-if="createMeeting"></switches>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </scrollbar>
        </div>

        <div v-if="participants && participants.length > 0" class="flex flex-direction-reverse flex-v-center">
            <div class="pagination flex flex-center" v-if="participants && participants.length > 0">
                <span v-if="participantsPages > 1" v-for="page in participantsPages" v-bind:class="{'active': page == participantsActivePage}" @click="changeParticipantsPage(page)">{{ page }}</span>
            </div>
            <div>
                <span class="pagination-info">{{ translateText('message.displaying') }} {{ displayedParticipants.length }} {{ translateText('message.results_out_of') }} {{ participants.length }}</span>
            </div>
        </div>
    </div>
</template>
<script>
import Switches from '../../3rdparty/vue-switches';
import {mapActions} from 'vuex';

export default {
    props: ['meetingParticipants', 'participants', 'participantsPerPage', 'participantsPages', 'createMeeting', 'participantsActivePage'],
    components: {
        Switches,
    },
    watch: {
        meetingParticipants(value) {
            this.displayedParticipants = this.meetingParticipants;
        },
    },
    methods: {
        ...mapActions(['updateParticipantPresent']),
        translateText: function(text) {
            return this.translate(text);
        },
        changeParticipantsPage: function(page) {
            this.$emit('change-active-page', page);
        },
        updateIsPresent(participant) {
            this.updateParticipantPresent({
                meeting: this.$route.params.meetingId,
                user: participant.id,
                isPresent: !participant.isPresent,
            });
        },
        addIsPresent(participant) {
            let meetingParticipant = {
                user: participant.id,
                isPresent: !participant.isPresent,
            };
            this.$emit('input', meetingParticipant);
        },
    },
    data() {
        return {
            showPresent: null,
        };
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
