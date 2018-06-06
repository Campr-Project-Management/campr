<template>
    <div class="task-box box timeline-tooltip">
        <div class="box-header">
            <div class="user-info flex flex-v-center">
                <user-avatar
                        size="small"
                        :url="avatarUrl"
                        :name="item.responsibilityFullName"/>
                <div class="user-name">{{ item.responsibilityFullName }}</div>
            </div>
            <h2>
                <router-link to="" class="simple-link">{{ item.name }}</router-link>
            </h2>
        </div>
        <div class="content">
            <schedule-dates-table
                    :base-start-at="item.scheduledStartAt"
                    :base-finish-at="item.scheduledFinishAt"
                    :base-duration-days="item.scheduledDurationDays"
                    :forecast-start-at="item.forecastStartAt"
                    :forecast-finish-at="item.forecastFinishAt"
                    :forecast-duration-days="item.forecastDurationDays"
                    :actual-start-at="item.actualStartAt"
                    :actual-finish-at="item.actualFinishAt"
                    :actual-duration-days="item.actualDurationDays"
                    :show-due-schedule="showDueSchedule"/>
        </div>
        <div class="status">
            <p>
                <span>{{ translate('table_header_cell.status') }}:</span>
                {{ translate(item.workPackageStatusName) }}
            </p>
        </div>
    </div>
</template>

<script>
    import ScheduleDatesTable from '../../_common/ScheduleDatesTable';
    import UserAvatar from '../../_common/UserAvatar';
    import {mapGetters} from 'vuex';

    export default {
        name: 'phases-and-milestones-tooltip',
        components: {
            UserAvatar,
            ScheduleDatesTable,
        },
        props: {
            item: {
                type: Object,
                required: true,
            },
            type: {
                type: String,
                required: false,
                default: 'phase',
                validation: function(value) {
                    return ['phase', 'milestone'].indexOf(value) >= 0;
                },
            },
        },
        computed: {
            ...mapGetters([
                'projectUserAvatarByUserId',
                'projectUserByUserId',
            ]),
            avatarUrl() {
                return this.projectUserAvatarByUserId(this.item.responsibility);
            },
            showDueSchedule() {
                return this.type === 'milestone';
            },
        },
    };
</script>

<style scoped lang="scss">
    .content {
        max-width: 500px;
    }
</style>
