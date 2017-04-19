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
                        v-bind:options="workPackageStatusesForSelect"
                        v-model="details.status"
                        v-bind:currentOption="details.status" />
                </div>
                <div class="col-md-4">
                    <select-field
                        v-bind:title="label.labels"
                        v-bind:options="labelsForSelect"
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
    props: ['editDetails'],
    components: {
        SelectField,
    },
    methods: {
        ...mapActions([
            'getProjectLabels',
            'getWorkPackageStatuses',
            'getWorkPackageStatusesForSelect',
            'getProjectUsers',
        ]),
    },
    computed: {
        ...mapGetters({
            labelsForSelect: 'labelsForChoice',
            workPackageStatusesForSelect: 'workPackageStatusesForSelect',
            assigneesForSelect: 'projectUsersForSelect',
        }),
    },
    created() {
        this.getProjectLabels(this.$route.params.id);
        this.getWorkPackageStatuses();
        this.getProjectUsers(this.$route.params.id);

        this.details = this.editDetails;
    },
    watch: {
        details: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
        editDetails(value) {
            this.details = this.editDetails;
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
                status: null,
                assignee: null,
                label: null,
            },
        };
    },
};
</script>
