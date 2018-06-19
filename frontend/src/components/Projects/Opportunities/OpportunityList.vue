<template>
    <div class="ro-list">
        <div class="ro-list-item" v-for="item in list">
            <div class="flex">
                <router-link :to="{name: 'project-opportunities-view-opportunity', params:{opportunityId: item.id}}">
                    {{ item.title }}
                </router-link>
                <user-avatar
                        size="very-small"
                        :name="item.responsibilityFullName"
                        :url="item.responsibilityAvatar"
                        :tooltip="item.responsibilityFullName"/>
            </div>
            <p>{{ translate('message.potential_savings') }}: <b v-if="item.potentialCostSavings">{{ item.potentialCostSavings | money({symbol: projectCurrencySymbol}) }}</b><b v-else>-</b></p>
            <p>{{ translate('message.potential_time_savings') }}: <b v-if="item.timeSavings">{{ item.potentialTimeSavingsHours | humanizeHours({ units: ['d', 'h'] }) }}</b><b v-else>-</b></p>
            <p>{{ translate('message.priority') }}: <b v-if="item.priority">{{ item.priority }}</b><b v-else>-</b></p>
            <p>{{ translate('message.strategy') }}: <b v-if="item.opportunityStrategyName">{{ item.opportunityStrategyName }}</b><b v-else>-</b></p>
            <p>{{ translate('message.status') }}: <b v-if="item.opportunityStatusName">{{ item.opportunityStatusName }}</b><b v-else>-</b></p>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import UserAvatar from '../../_common/UserAvatar';

    export default {
        components: {UserAvatar},
        props: {
            list: {
                type: Array,
                required: false,
                default: () => [],
            },
        },
        computed: {
            ...mapGetters([
                'projectCurrencySymbol',
            ]),
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../css/_variables';

    .ro-list-item {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        a {
            font-size: 1.166em;
            color: $secondColor;
            font-weight: 600;
            padding-right: 25px;
            display: block;
        }

        p {
            font-size: 0.833em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }
</style>
