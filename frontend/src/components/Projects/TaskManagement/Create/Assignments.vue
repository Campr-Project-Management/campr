<template>
    <div>
        <h3>{{ translate('message.task_assignments') }}</h3>
        <div class="row">
            <div class="form-group">
                <div class="col-md-4">
                    <select-field
                            :allow-clear="true"
                            :title="translate('label.asignee')"
                            :options="responsibilityOptions"
                            :value="value.responsibility"
                            :disabled="disabled"
                            @input="onResponsibilityChange"/>
                    <error at-path="responsibility" />
                </div>
                <div class="col-md-4">
                    <select-field
                            :allow-clear="true"
                            :title="translate('label.accountable')"
                            :options="accountabilityOptions"
                            :value="value.accountability"
                            :disabled="disabled"
                            @input="onAccountabilityChange"/>
                    <error at-path="accountability" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4">
                    <multi-select-field
                            :title="translate('label.select_support_users')"
                            :options="supportUsersOptions"
                            :value="value.supportUsers"
                            :disabled="disabled"
                            @input="onSupportUsersChange"/>
                    <error at-path="supportUsers" />
                </div>
                <div class="col-md-4">
                    <multi-select-field
                            :title="translate('label.select_consulted_users')"
                            :options="consultedUsersOptions"
                            :value="value.consultedUsers"
                            :disabled="disabled"
                            @input="onConsultedUsersChange"/>
                    <error at-path="consultedUsers" />
                </div>
                <div class="col-md-4">
                    <multi-select-field
                            :title="translate('label.select_informed_users')"
                            :options="informedUsersOptions"
                            :value="value.informedUsers"
                            :disabled="disabled"
                            @input="onInformedUsersChange"/>
                    <error at-path="informedUsers" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectField from '../../../_common/_form-components/SelectField';
    import MultiSelectField from '../../../_common/_form-components/MultiSelectField';
    import Error from '../../../_common/_messages/Error.vue';
    import {mapActions, mapGetters} from 'vuex';
    import _ from 'lodash';

    export default {
        name: 'task-create-assignments',
        props: {
            value: {
                type: Object,
                required: true,
                default: () => ({
                    responsibility: null,
                    accountability: null,
                    supportUsers: [],
                    consultedUsers: [],
                    informedUsers: [],
                }),
            },
            disabled: false,
        },
        components: {
            SelectField,
            MultiSelectField,
            Error,
        },
        methods: {
            ...mapActions([
                'getProjectUsers',
            ]),
            onResponsibilityChange(value) {
                this.$emit('input', Object.assign({}, this.value, {responsibility: value}));
            },
            onAccountabilityChange(value) {
                this.$emit('input', Object.assign({}, this.value, {accountability: value}));
            },
            onSupportUsersChange(value) {
                this.$emit('input', Object.assign({}, this.value, {supportUsers: value}));
            },
            onConsultedUsersChange(value) {
                this.$emit('input', Object.assign({}, this.value, {consultedUsers: value}));
            },
            onInformedUsersChange(value) {
                this.$emit('input', Object.assign({}, this.value, {informedUsers: value}));
            },
        },
        computed: {
            ...mapGetters([
                'projectUsersForSelect',
                'validationMessages',
            ]),
            assignments() {
                let ids = [
                    ...this.value.supportUsers.map(user => user.key),
                    ...this.value.consultedUsers.map(user => user.key),
                    ...this.value.informedUsers.map(user => user.key),
                ];

                if (this.value.responsibility) {
                    ids.push(this.value.responsibility.key);
                }

                if (this.value.accountability) {
                    ids.push(this.value.accountability.key);
                }

                return _.uniq(ids);
            },
            responsibilityOptions() {
                return this.projectUsersForSelect.filter((user) => {
                    if (this.value.responsibility && user.key === this.value.responsibility.key) {
                        return true;
                    }

                    return this.assignments.indexOf(user.key) < 0;
                });
            },
            accountabilityOptions() {
                return this.projectUsersForSelect.filter((user) => {
                    if (this.value.accountability && user.key === this.value.accountability.key) {
                        return true;
                    }

                    return this.assignments.indexOf(user.key) < 0;
                });
            },
            supportUsersOptions() {
                return this.projectUsersForSelect.filter((user) => {
                    if (_.find(this.value.supportUsers, ['key', user.key])) {
                        return true;
                    }

                    return this.assignments.indexOf(user.key) < 0;
                });
            },
            consultedUsersOptions() {
                return this.projectUsersForSelect.filter((user) => {
                    if (_.find(this.value.consultedUsers, ['key', user.key])) {
                        return true;
                    }

                    return this.assignments.indexOf(user.key) < 0;
                });
            },
            informedUsersOptions() {
                return this.projectUsersForSelect.filter((user) => {
                    if (_.find(this.value.informedUsers, ['key', user.key])) {
                        return true;
                    }

                    return this.assignments.indexOf(user.key) < 0;
                });
            },
        },
        created() {
            this.getProjectUsers({id: this.$route.params.id});
        },
    };
</script>
