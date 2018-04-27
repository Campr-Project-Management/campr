<template>
    <div>
        <!-- /// Task Assignee /// -->
        <h3>{{ translate('message.asignee')}}</h3>
        <div class="row">
            <div class="col-lg-7">
                <div class="flex flex-v-center" v-if="responsibilityUser">
                    <div class="user-avatar flex flex-v-center" v-bind:style="{ backgroundImage: 'url(' + responsibilityUser.userAvatar + ')' }"></div>
                    <div>
                        <b class="uppercase"> {{ responsibilityUser.userFullName }}</b><br/>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: responsibilityUser.user} }"
                                class="simple-link">
                            {{ responsibilityUser.userEmail }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <select-field
                        :title="translate('message.change_assignee')"
                        :options="responsibilityOptions"
                        :value="value.responsibility"
                        :disabled="disabled"
                        @input="onResponsibilityChange"/>
                <error at-path="responsibility" />
            </div>
        </div>
        <!-- /// End Task Assignee /// -->

        <br/>
        <!-- /// Task Accountable /// -->
        <h3>{{ translate('label.accountable')}}</h3>
        <div class="row">
            <div class="col-md-8">
                <div class="flex flex-v-center" v-if="accountabilityUser">
                    <div class="user-avatar flex flex-v-center" v-bind:style="{ backgroundImage: 'url(' + accountabilityUser.userAvatar + ')' }"></div>
                    <div>
                        <b class="uppercase"> {{accountabilityUser.userFullName}}</b><br/>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: accountabilityUser.user} }"
                                class="simple-link">
                            {{ accountabilityUser.userEmail }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <select-field
                        :title="translate('message.change_accountable')"
                        :options="accountabilityOptions"
                        :value="value.accountability"
                        :disabled="disabled"
                        @input="onAccountabilityChange"/>
                <error at-path="accountability" />
            </div>
        </div>

        <hr class="double">

        <h3>{{ translate('label.select_sci_users')}}</h3>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <multi-select-field
                        :title="translate('label.select_support_users')"
                        :options="supportUsersOptions"
                        :value="value.supportUsers"
                        :disabled="disabled"
                        @input="onSupportUsersChange"/>
                <error at-path="supportUsers" />
            </div>
            <div class="col-lg-4 col-md-6">
                <multi-select-field
                        :title="translate('label.select_consulted_users')"
                        :options="consultedUsersOptions"
                        :value="value.consultedUsers"
                        :disabled="disabled"
                        @input="onConsultedUsersChange"/>
                <error at-path="consultedUsers" />
            </div>
            <div class="col-lg-4 col-md-6">
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
</template>

<script>
    import Assignments from '../Create/Assignments';
    import {mapGetters} from 'vuex';

    export default {
        name: 'task-view-assignments',
        extends: Assignments,
        computed: {
            ...mapGetters([
                'projectUserByUserId',
            ]),
            responsibilityUser() {
                if (!this.value.responsibility) {
                    return;
                }

                return this.projectUserByUserId(this.value.responsibility.key);
            },
            accountabilityUser() {
                if (!this.value.accountability) {
                    return;
                }

                return this.projectUserByUserId(this.value.accountability.key);
            },
        },
    };
</script>
