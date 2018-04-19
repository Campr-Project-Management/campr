<template>
    <modal @close="onCancel">
        <p class="modal-title">{{ translate('title.status.edit') }}</p>
        <select-field
                :title="translate('title.status.edit')"
                :options="options"
                v-model="selection"
                @input="onStatusChange"/>
        <br />
        <div class="flex flex-space-between">
            <a
                    href="javascript:void(0)"
                    @click="onCancel"
                    class="btn-rounded btn-empty danger-color danger-border">{{ translate('button.cancel') }}</a>
            <a
                    href="javascript:void(0)"
                    @click="onSave"
                    class="btn-rounded">{{ translate('title.status.edit') }} +</a>
        </div>
    </modal>
</template>

<script>
    import SelectField from '../../../_common/_form-components/SelectField';
    import Modal from '../../../_common/Modal';
    import {mapGetters} from 'vuex';

    export default {
        name: 'task-view-edit-status-modal',
        props: {
            value: {
                type: Object,
                required: false,
                default: null,
            },
        },
        components: {
            SelectField,
            Modal,
        },
        computed: {
            ...mapGetters([
                'workPackageStatusesForSelect',
            ]),
            options() {
                return this.workPackageStatusesForSelect;
            },
        },
        methods: {
            onSave() {
                this.$emit('input', this.selection);
            },
            onStatusChange(value) {
                this.selection = value;
            },
            onCancel() {
                this.$emit('cancel', true);
            },
        },
        data() {
            return {
                selection: this.value,
            };
        },
    };
</script>
