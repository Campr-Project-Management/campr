<template>
    <div class="ro-list">
        <div class="ro-list-item" v-for="item in listData">
            <div class="avatar" :style="{ backgroundImage: 'url(' + item.responsibilityAvatar + ')' }" v-tooltip.top="item.responsibilityFullName"></div>
            <router-link :to="{name: 'project-opportunities-view-opportunity', params:{opportunityId: item.id}}">
                {{ item.title }}
            </router-link>
            <p>{{ translateText('message.potential_savings') }}: <b v-if="item.potentialCostSavings">{{ item.potentialCostSavings | money({symbol: projectCurrencySymbol}) }}</b><b v-else>-</b></p>
            <p>{{ translateText('message.potential_time_savings') }}: <b v-if="item.timeSavings">{{ item.potentialTimeSavingsHours | humanizeHours({ units: ['d', 'h'] }) }}</b><b v-else>-</b></p>
            <p>{{ translateText('message.priority') }}: <b v-if="item.priority">{{ item.priority }}</b><b v-else>-</b></p>
            <p>{{ translateText('message.strategy') }}: <b v-if="item.opportunityStrategyName">{{ item.opportunityStrategyName }}</b><b v-else>-</b></p>
            <p>{{ translateText('message.status') }}: <b v-if="item.opportunityStatusName">{{ item.opportunityStatusName }}</b><b v-else>-</b></p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    props: ['listData'],
    methods: {
        translateText: function(text) {
            return this.translate(text);
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
    @import '../../../css/page-section';
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .ro-list {
        width: 35%;
        float: left;
    }
    .ro-list-item {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .avatar {
            height: 20px;
            width: 20px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            right: 0;
            top: 0;
            @include border-radius(50%);
        }

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
