<template>
    <table class="table table-striped table-responsive table-fixed table-small" v-if="items.length > 0">
        <thead>
        <tr>
            <th style="width:14%">{{ translate('table_header_cell.due_date') }}</th>
            <th style="width:25%">{{ translate('table_header_cell.topic') }}</th>
            <th style="width:36%">{{ translate('table_header_cell.description') }}</th>
            <th style="width:14%">{{ translate('table_header_cell.responsible') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in items">
            <td>{{ item.dueDate | date }}</td>
            <td class="cell-wrap">{{ item.title }}</td>
            <td class="cell-wrap cell-large" v-html="item.description"></td>
            <td>
                <user-avatar
                        size="small"
                        :url="projectUserAvatarByUserId(item.responsibilityId)"
                        :name="item.responsibilityFullName"
                        :tooltip="item.responsibilityFullName"/>
            </td>
        </tr>
        </tbody>
    </table>
    <span v-else>{{ translate('label.no_data') }}</span>
</template>

<script>
    import UserAvatar from '../../../_common/UserAvatar';
    import {mapGetters} from 'vuex';

    export default {
        name: 'status-report-decisions',
        components: {
            UserAvatar,
        },
        props: {
            items: {
                type: Array,
                required: true,
                default: () => [],
            },
        },
        computed: {
            ...mapGetters([
                'projectUserAvatarByUserId',
            ]),
        },
    };
</script>
