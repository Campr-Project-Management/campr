<template>
    <div>
        <h3>{{ message.task_details }}</h3>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <select-field
                        v-bind:title="label.assignee"
                        v-bind:options="assigneesForSelect"
                        v-model="details.assignee"
                        v-bind:currentOption="details.assignee" />
                </div>
                <div class="col-md-4">
                    <select-field
                        v-bind:title="label.status"
                        v-bind:options="statusesForSelect"
                        v-model="details.status"
                        v-bind:currentOption="details.status" />
                </div>
                <div class="col-md-4">
                    <select-field
                        v-bind:title="label.labels"
                        v-bind:options="labelsForChoice"
                        v-model="details.label"
                        v-bind:currentOption="details.label" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from '../../../_common/_form-components/SelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        SelectField,
    },
    methods: {
        ...mapActions(['getProjectLabels', 'getProjectStatuses', 'getProjectUsers']),
    },
    computed: {
        ...mapGetters({
            labelsForChoice: 'labelsForChoice',
            statusesForSelect: 'projectStatusesForSelect',
            assigneesForSelect: 'projectUsersForSelect',
        }),
    },
    created() {
        this.getProjectLabels(this.$route.params.id);
        this.getProjectStatuses();
        this.getProjectUsers(this.$route.params.id);
    },
    watch: {
        details: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
    },
    data: function() {
        return {
            message: {
                task_details: this.translate('message.task_details'),
            },
            label: {
                assignee: this.translate('label.asignee'),
                labels: this.translate('label.labels'),
                status: this.translate('label.status'),
            },
            details: {
                status: '',
                assignee: '',
                label: '',
            },
        };
    },
};
</script>
