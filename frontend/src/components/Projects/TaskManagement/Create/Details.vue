<template>
    <div>
        <h3>{{ translate('message.task_details') }}</h3>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-6">
                    <select-field
                        :title="translate('label.status')"
                        :options="workPackageStatusesForSelect"
                        :value="value.status"
                        @input="onStatusUpdate"/>
                </div>
                <div class="col-md-6">
                    <select-field
                        :title="translate('label.labels')"
                        :options="labelsForChoice"
                        :value="value.label"
                        @input="onLabelUpdate"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from '../../../_common/_form-components/SelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: {
        value: {
            type: Object,
            required: true,
            default: () => {},
        },
    },
    components: {
        SelectField,
    },
    methods: {
        ...mapActions([
            'getProjectLabels',
            'getWorkPackageStatuses',
            'getWorkPackageStatusesForSelect',
        ]),
        onStatusUpdate(value) {
            this.$emit('input', Object.assign({}, this.value, {status: value}));
        },
        onLabelUpdate(value) {
            this.$emit('input', Object.assign({}, this.value, {label: value}));
        },
    },
    computed: {
        ...mapGetters([
            'labelsForChoice',
            'workPackageStatusesForSelect',
        ]),
    },
    created() {
        this.getProjectLabels(this.$route.params.id);
        this.getWorkPackageStatuses();
    },
};
</script>
