<template>
    <modal @close="onCancel" v-if="value">
        <p class="modal-title">{{ translate('title.schedule.edit') }}</p>
        <schedule v-model="scheduleModel" />
        <div class="flex flex-space-between">
            <a
                    href="javascript:void(0)"
                    @click="onCancel"
                    class="btn-rounded btn-empty danger-color danger-border">{{ translate('button.cancel') }}</a>
            <a
                    href="javascript:void(0)"
                    @click="onSave"
                    class="btn-rounded">{{ translate('button.edit_schedule') }} +</a>
        </div>
    </modal>
</template>

<script>
    import Schedule from '../Create/Schedule';
    import Modal from '../../../_common/Modal';

    export default {
        name: 'edit-schedule-modal',
        props: {
            value: {
                type: Boolean,
                required: false,
                default: false,
            },
            schedule: {
                type: Object,
                required: true,
            },
        },
        components: {
            Schedule,
            Modal,
        },
        watch: {
            schedule(value) {
                this.scheduleModel = value;
            },
        },
        methods: {
            onSave() {
                this.$emit('save', this.scheduleModel);
            },
            onCancel() {
                this.$emit('input', false);
                this.$emit('cancel');
            },
        },
        data() {
            return {
                scheduleModel: {},
            };
        },
    };
</script>
