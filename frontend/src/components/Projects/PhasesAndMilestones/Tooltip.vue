<template>
    <div class="task-box box timeline-tooltip">
        <div class="box-header">
            <div class="user-info flex flex-v-center">
                <img
                        class="user-avatar"
                        :src="item.responsibilityAvatar"
                        :alt="translate('table_header_cell.responsible') + item.responsibilityFullName"/>
                <p>{{ item.responsibilityFullName }}</p>
            </div>
            <h2>
                <router-link to="" class="simple-link">{{ item.name }}</router-link>
            </h2>
        </div>
        <div class="content">
            <table class="table table-small">
                <thead>
                <tr>
                    <th>{{ translate('table_header_cell.schedule') }}</th>
                    <template v-if="isPhase">
                        <th>{{ translate('table_header_cell.start') }}</th>
                        <th>{{ translate('table_header_cell.finish') }}</th>
                        <th>{{ translate('table_header_cell.duration') }}</th>
                    </template>
                    <template v-else>
                        <th>{{ translate('table_header_cell.date') }}</th>
                    </template>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ translate('table_header_cell.base') }}</td>
                    <td v-if="isPhase">{{ item.scheduledStartAt | date }}</td>
                    <td>{{ item.scheduledFinishAt | date }}</td>
                    <td v-if="isPhase">{{ item.scheduledDurationDays > 0 ? $formatNumber(item.scheduledDurationDays) :
                        '-' }}
                    </td>
                </tr>
                <tr :class="forecastColorClass">
                    <td>{{ translate('table_header_cell.forecast') }}</td>
                    <td v-if="isPhase">{{ item.forecastStartAt | date }}</td>
                    <td>{{ item.forecastFinishAt | date }}</td>
                    <td v-if="isPhase">{{ item.forecastDurationDays > 0 ? $formatNumber(item.forecastDurationDays) : '-'
                        }}
                    </td>
                </tr>
                <tr :class="actualColorClass" v-if="isPhase">
                    <td>{{ translate('table_header_cell.actual') }}</td>
                    <td>{{ item.actualStartAt | date }}</td>
                    <td>{{ item.actualFinishAt | date }}</td>
                    <td>{{ item.actualDurationDays > 0 ? $formatNumber(item.actualDurationDays) : '-' }}</td>
                </tr>
                </tbody>
            </table>
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
    import moment from 'moment';

    export default {
        name: 'phases-and-milestones-tooltip',
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
            isPhase() {
                return this.type === 'phase';
            },
            isMilestone() {
                return this.type === 'milestone';
            },
            forecastColorClass() {
                let klass = 'column';
                if (moment(this.item.forecastFinishAt).diff(moment(this.item.scheduledFinishAt), 'days') > 0) {
                    klass = 'column-warning';
                }

                if (moment(this.item.actualFinishAt).diff(moment(this.item.forecastFinishAt), 'days') > 0 &&
                    isMilestone()) {
                    klass = 'column-alert';
                }

                return klass;
            },
            actualColorClass() {
                let klass = 'column';
                if (moment(this.item.actualFinishAt).diff(moment(this.item.forecastFinishAt), 'days') > 0) {
                    klass = 'column-alert';
                }

                return klass;
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/common';

    table {
        max-width: 400px;
    }
</style>
