<template>
    <table class="table table-small">
        <thead>
        <tr>
            <th>{{ translate('table_header_cell.schedule') }}</th>
            <th>{{ translate('table_header_cell.start') }}</th>
            <th>{{ translate('table_header_cell.finish') }}</th>
            <th>{{ translate('table_header_cell.duration') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ translate('table_header_cell.base') }}</td>
            <td>{{ baseStartAt | date }}</td>
            <td>{{ baseFinishAt | date }}</td>
            <td>{{ baseDurationDays > 0 ? (baseDurationDays | formatNumber) : '-' }}
            </td>
        </tr>
        <tr :style="{color: forecastRowColor}">
            <td>{{ translate('table_header_cell.forecast') }}</td>
            <td>{{ forecastStartAt | date }}</td>
            <td>{{ forecastFinishAt | date }}</td>
            <td>{{ forecastDurationDays > 0 ? (forecastDurationDays | formatNumber) : '-' }}
            </td>
        </tr>
        <tr :style="{color: actualRowColor}">
            <td>{{ translate('table_header_cell.actual') }}</td>
            <td>{{ actualStartAt | date }}</td>
            <td>{{ actualFinishAt | date }}</td>
            <td>{{ actualDurationDays > 0 ? (actualDurationDays | formatNumber) : '-' }}
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import colors from '../../util/colors';

    export default {
        name: 'schedule-dates-table',
        props: {
            baseStartAt: {
                type: [String, Date],
            },
            baseFinishAt: {
                type: [String, Date],
            },
            baseDurationDays: {
                type: Number,
                required: true,
                default: 0,
            },
            forecastStartAt: {
                type: [String, Date],
            },
            forecastFinishAt: {
                type: [String, Date],
            },
            forecastDurationDays: {
                type: Number,
                default: 0,
            },
            actualStartAt: {
                type: [String, Date],
            },
            actualFinishAt: {
                type: [String, Date],
            },
            actualDurationDays: {
                type: Number,
                required: true,
                default: 0,
            },
            activityCompleted: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        computed: {
            forecastRowColor() {
                return colors.schedule.getForecastColor({
                    startAt: this.baseStartAt,
                    finishAt: this.baseFinishAt,
                }, {
                    startAt: this.forecastStartAt,
                    finishAt: this.forecastFinishAt,
                });
            },
            actualRowColor() {
                return colors.schedule.getActualColor({
                    startAt: this.forecastStartAt,
                    finishAt: this.forecastFinishAt,
                }, {
                    startAt: this.actualStartAt,
                    finishAt: this.actualFinishAt,
                }, this.activityCompleted);
            },
        },
    };
</script>
