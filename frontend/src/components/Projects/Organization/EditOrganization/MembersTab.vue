<template>
    <div class="team-list">
        <scrollbar class="customScrollbar">
            <div class="scroll-wrapper">
                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th class="avatar"></th>
                        <th>{{ translate('table_header_cell.name') }}</th>
                        <th>{{ translate('table_header_cell.email') }}</th>
                        <th>{{ translate('table_header_cell.rate') }}</th>
                        <th>{{ translate('table_header_cell.project_member') }}</th>
                        <th>{{ translate('table_header_cell.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="member in members" :key="member.id">
                        <td class="avatar text-center">
                            <user-avatar
                                    size="small"
                                    :url="member.avatarUrl"
                                    :name="member.fullName"
                                    :tooltip="member.fullName"/>
                        </td>
                        <td>{{ member.fullName }}</td>
                        <td>{{ member.email }}</td>
                        <td>
                            <template v-if="member.rate === null">{{ translate('ui.n_a') }}</template>
                            <template>{{ member.rate | money({symbol: projectCurrencySymbol}) }}</template>
                        </td>
                        <td>
                            <switches
                                    v-if="member.isToggleable"
                                    @input="onToggleMembership(member.userId, $event)"
                                    :value="member.isMember"/>
                        </td>
                        <td>
                            <button
                                    @click="onEdit(member)"
                                    type="button"
                                    class="btn-icon">
                                <edit-icon fill="second-fill"/>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </scrollbar>

        <div v-if="nbPages > 1" class="flex flex-direction-reverse flex-v-center">
            <pagination v-model="page" :number-of-pages="nbPages"/>
        </div>

        <modal v-model="showModal" :hasSpecificClass="true">
            <form @submit.prevent="onSave(member)" novalidate>
                <p class="modal-title">{{ translate('ui.edit_member') }}</p>
                <money-field
                        v-model="form.rate"
                        :currency="projectCurrencySymbol"
                        :label="translate('label.rate')"/>
                <error :message="errorMessages.rate"/>
                <br/>

                <div class="flex flex-space-between">
                    <button @click="onCancel" type="button" class="btn-rounded btn-auto">{{ translate('button.cancel')
                        }}
                    </button>
                    <button type="submit" class="btn-rounded btn-auto second-bg">{{ translate('button.edit_member') }} +
                    </button>
                </div>
            </form>
        </modal>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import UserAvatar from '../../../_common/UserAvatar';
    import Switches from '../../../3rdparty/vue-switches';
    import Pagination from '../../../_common/Pagination';
    import EditIcon from '../../../_common/_icons/EditIcon';
    import Modal from '../../../_common/Modal';
    import MoneyField from '../../../_common/_form-components/MoneyField';
    import Error from '../../../_common/_messages/Error';

    export default {
        name: 'MembersTab',
        props: {
            project: {
                type: Object,
                required: true,
                default: () => {},
            },
            users: {
                type: Array,
                required: true,
                default: () => [],
            },
        },
        components: {Error, MoneyField, Modal, EditIcon, Pagination, Switches, UserAvatar},
        computed: {
            ...mapGetters([
                'projectUserByUserId',
                'projectCurrencySymbol',
            ]),
            nbPages() {
                if (!this.users || !this.users.length) {
                    return 1;
                }

                return Math.ceil(this.users.length / 10);
            },
            currentUsers() {
                if (!this.users || !this.users.length) {
                    return [];
                }
                return this.users.slice((this.page - 1) * 10, this.page * 10);
            },
            members() {
                return this.currentUsers.map(user => {
                    let projectUser = this.projectUserByUserId(user.id);
                    if (!projectUser) {
                        projectUser = {rate: null, id: null};
                    }

                    const isMember = this.isMember(user);
                    const isToggleable = !this.isManager(user) && !this.isSponsor(user);

                    return {
                        ...projectUser,
                        userId: user.id,
                        fullName: user.fullName,
                        email: user.email,
                        avatarUrl: user.avatarUrl,
                        isToggleable,
                        isMember,
                    };
                });
            },
        },
        methods: {
            ...mapActions([
                'createProjectUser',
                'deleteProjectUser',
                'updateProjectUser',
            ]),
            isManager(user) {
                return this.project.projectManager === user.id;
            },
            isSponsor(user) {
                return this.project.projectSponsor === user.id;
            },
            isMember({id}) {
                return this.membership[id] || !!this.projectUserByUserId(id);
            },
            onToggleMembership(id, value) {
                const data = {
                    projectId: this.project.id,
                    userId: id,
                };

                if (value) {
                    this.createProjectUser(data);
                    this.membership[id] = value;
                    return;
                }

                this.deleteProjectUser(data);
                this.membership[id] = value;
            },
            onEdit(member) {
                this.showModal = true;
                this.form = Object.assign({}, this.form, {id: member.id, rate: member.rate});
            },
            onCancel() {
                this.showModal = false;
                this.resetForm();
            },
            async onSave() {
                try {
                    await this.updateProjectUser(this.form);
                    this.showModal = false;
                    this.resetForm();
                } catch (e) {
                    this.resetErrors();
                    this.errorMessages = Object.assign({}, this.errorMessages, e.data.messages);
                }
            },
            resetForm() {
                this.form = Object.assign({}, this.form, {rate: 0, id: null});
                this.resetErrors();
            },
            resetErrors() {
                this.errorMessages = Object.assign({}, this.errorMessages, {rate: []});
            },
        },
        data() {
            return {
                page: 1,
                membership: {},
                showModal: false,
                form: {
                    id: null,
                    rate: 0,
                },
                errorMessages: {
                    rate: [],
                },
            };
        },
    };
</script>
