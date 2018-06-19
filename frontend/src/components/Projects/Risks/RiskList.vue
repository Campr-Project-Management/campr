<template>
    <div class="ro-list">
        <div class="ro-list-item" v-for="item in list">
            <div class="flex">
                <router-link :to="{name: 'project-risks-view-risk', params:{riskId: item.id}}">
                    {{ item.title }}
                </router-link>
                <user-avatar
                        size="very-small"
                        :name="item.responsibilityFullName"
                        :url="item.responsibilityAvatar"
                        :tooltip="item.responsibilityFullName"/>
            </div>
            <p>{{ translate('message.potential_cost') }}: <b v-if="item.cost">{{ item.potentialCost | money({symbol: projectCurrencySymbol}) }}</b><b v-else>-</b></p>
            <p>{{ translate('message.potential_time_delay') }}: <b v-if="item.delay">{{ item.potentialDelayHours | humanizeHours({ units: ['d', 'h'] }) }}</b><b v-else>-</b></p>
            <p>{{ translate('message.priority') }}: <b :style="{color: getPriorityColor(item.priority)}">{{ translate(`message.${item.priorityName}`) }}</b></p>
            <p>{{ translate('message.strategy') }}: <b v-if="item.riskStrategyName">{{ translate(item.riskStrategyName) }}</b><b v-else>-</b></p>
            <p>{{ translate('message.status') }}: <b>{{ item.statusName ? translate(item.statusName) : '-' }}</b></p>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import UserAvatar from '../../_common/UserAvatar';
    import {riskManagement} from '../../../util/colors';

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
        methods: {
            getPriorityColor(priority) {
                return riskManagement.risk.getColorByPriority(priority);
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_mixins';
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
