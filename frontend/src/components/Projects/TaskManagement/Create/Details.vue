<template>
    <div>
        <h3>{{ translateText('message.task_details') }}</h3>
        <div class="row">
            <div class="form-group">
                <div class="col-md-4">
                    <select-field
                        v-bind:title="translateText('label.asignee')"
                        v-bind:options="projectUsersForSelect"
                        v-model="details.assignee"
                        v-bind:currentOption="details.assignee" />
                </div>
                <div class="col-md-4">
                    <select-field
                        v-bind:title="translateText('label.accountable')"
                        v-bind:options="projectUsersForSelectAccountable"
                        v-model="details.accountable"
                        v-bind:currentOption="details.accountable" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4">
                    <multi-select-field
                        v-bind:title="translateText('label.select_support_users')"
                        v-bind:options="projectUsersForSupportSelect"
                        v-bind:selectedOptions="details.supportUsers"
                        v-model="details.supportUsers" />
                </div>
                <div class="col-md-4">
                    <multi-select-field
                        v-bind:title="translateText('label.select_consulted_users')"
                        v-bind:options="projectUsersForConsultedSelect"
                        v-bind:selectedOptions="details.consultedUsers"
                        v-model="details.consultedUsers" />
                </div>
                <div class="col-md-4">
                    <multi-select-field
                        v-bind:title="translateText('label.select_informed_users')"
                        v-bind:options="projectUsersForInformedSelect"
                        v-bind:selectedOptions="details.informedUsers"
                        v-model="details.informedUsers" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <select-field
                        v-bind:title="translateText('label.status')"
                        v-bind:options="workPackageStatusesForSelect"
                        v-model="details.status"
                        v-bind:currentOption="details.status" />
                </div>
                <div class="col-md-4">
                    <select-field
                        v-bind:title="translateText('label.labels')"
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
import MultiSelectField from '../../../_common/_form-components/MultiSelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['editDetails'],
    components: {
        SelectField,
        MultiSelectField,
    },
    methods: {
        ...mapActions([
            'getProjectLabels',
            'getWorkPackageStatuses',
            'getWorkPackageStatusesForSelect',
            'getProjectUsers',
        ]),
        translateText(text) {
            return this.translate(text);
        },
    },
    computed: {
        ...mapGetters({
            labelsForSelect: 'labelsForChoice',
            workPackageStatusesForSelect: 'workPackageStatusesForSelect',
            projectUsersForSelect: 'projectUsersForSelect',
        }),
        projectUsersForSelectAccountable: function() {
            let rez = JSON.parse(JSON.stringify(this.projectUsersForSelect));
            return rez;
        },
        projectUsersForSupportSelect: function() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForSelect));

            let selectedIds = [];
            for( let i =0; i< this.details.supportUsers.length; i++) {
                selectedIds.push(this.details.supportUsers[i].key);
            }

            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
        projectUsersForConsultedSelect: function() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForSelect));

            let selectedIds = [];
            for( let i =0; i< this.details.consultedUsers.length; i++) {
                selectedIds.push(this.details.consultedUsers[i].key);
            }

            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
        projectUsersForInformedSelect: function() {
            let usersForSelect = JSON.parse(JSON.stringify(this.projectUsersForSelect));

            let selectedIds = [];
            for( let i =0; i< this.details.informedUsers.length; i++) {
                selectedIds.push(this.details.informedUsers[i].key);
            }

            return usersForSelect.filter((item) => {
                return selectedIds.indexOf(item.key) === -1;
            });
        },
    },
    created() {
        this.getProjectLabels(this.$route.params.id);
        this.getWorkPackageStatuses();
        this.getProjectUsers({id: this.$route.params.id});

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
            details: {
                status: null,
                assignee: null,
                label: null,
                accountable: null,
                supportUsers: [],
                consultedUsers: [],
                informedUsers: [],
            },
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../../css/_common';
</style>
