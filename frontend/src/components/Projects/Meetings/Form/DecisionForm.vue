<template>
    <div>
        <div class="row">
            <div class="form-group">
                <div class="col-md-12">
                    <input-field
                            type="text"
                            :label="translate('placeholder.decision_title')"
                            v-model="lazyValue.title"
                            @input="onInput"/>
                    <error :message="errorMessages.title"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-md-12">
                    <editor
                            height="200px"
                            label="placeholder.decision_description"
                            v-model="lazyValue.description"
                            @input="onInput"/>
                    <error :message="errorMessages.description"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-md-6">
                    <member-search
                            v-model="lazyValue.responsibility"
                            v-bind:selectedUser="lazyValue.responsibilityFullName"
                            :placeholder="translate('placeholder.responsible')"
                            :singleSelect="true"
                            @input="onInput"/>
                    <error :message="errorMessages.responsibility"/>
                </div>
                <div class="col-md-6">
                    <div class="input-holder right">
                        <label class="active">{{ translate('label.due_date') }}</label>
                        <date-field v-model="lazyValue.dueDate" @input="onInput"/>
                    </div>
                    <error :message="errorMessages.dueDate"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-md-6">
                    <select-field
                            :title="translate('message.status')"
                            :options="decisionsStatusesOptions"
                            v-model="lazyValue.done"
                            @input="onInput"/>
                    <error :message="errorMessages.done"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <div class="col-md-12">
                    <attachments
                            v-model="lazyValue.medias"
                            :max-file-size="projectMaxUploadFileSize"
                            @uploading="onUploading"
                            @input="onInput"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import InputField from '../../../_common/_form-components/InputField';
    import SelectField from '../../../_common/_form-components/SelectField';
    import MemberSearch from '../../../_common/MemberSearch';
    import MultiSelectField from '../../../_common/_form-components/MultiSelectField';
    import Error from '../../../_common/_messages/Error.vue';
    import Editor from '../../../_common/Editor';
    import moment from 'moment';
    import DateField from '../../../_common/_form-components/DateField';
    import Attachments from '../../../_common/Attachments';

    export default {
        name: 'meeting-decision-form',
        props: {
            value: {
                type: Object,
                required: false,
                default: () => {},
            },
            errorMessages: {
                type: Object,
                required: false,
                default: () => {
                    return {
                        title: [],
                        description: [],
                        dueDate: [],
                        reponsibility: [],
                        done: [],
                        medias: [],
                    };
                },
            },
        },
        components: {
            DateField,
            InputField,
            SelectField,
            MemberSearch,
            MultiSelectField,
            Error,
            Editor,
            Attachments,
        },
        computed: {
            ...mapGetters([
                'decisionsStatusesForSelect',
                'decisionStatusByValueForSelect',
                'projectMaxUploadFileSize',
                'validationMessagesFor',
                'project',
            ]),
            decisionsStatusesOptions() {
                return this.decisionsStatusesForSelect.map((option) => {
                    return Object.assign({}, option, {label: this.translate(option.label)});
                });
            },
        },
        methods: {
            onInput() {
                let responsibility = null;
                if (this.lazyValue.responsibility && this.lazyValue.responsibility.length > 0) {
                    responsibility = this.lazyValue.responsibility[0];
                }

                let value = Object.assign({}, this.lazyValue, {
                    done: this.lazyValue.done.key,
                    responsibility: responsibility,
                    dueDate: this.lazyValue.dueDate ? moment(this.lazyValue.dueDate)
                        .toDate() : null,
                    medias: this.lazyValue.medias,
                });

                this.$emit('input', value);
            },
            onUploading(uploading) {
                this.$emit('uploading', uploading);
            },
            toLazyValue(value) {
                return Object.assign({}, value, {
                    done: {
                        key: value.done,
                    },
                    responsibility: value.responsibility ? [value.responsibility] : [],
                    medias: value.medias ? value.medias : [],
                });
            },
        },
        watch: {
            value(val) {
                this.lazyValue = this.toLazyValue(val);
            },
        },
        data() {
            return {
                lazyValue: this.toLazyValue(this.value),
            };
        },
    };
</script>
