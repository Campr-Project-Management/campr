<template>
    <div class="create-task page-section">
        <div class="row">
            <div class="col-md-6">
                <!-- /// Header /// -->
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_organization') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_team_member') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_team_member') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Member Avatar /// -->
                    <input id="avatar" type="file" name="avatar" style="display: none;" accept="image/*" @change="updateAvatar">

                    <div v-if="!avatar">
                        <avatar-placeholder />
                    </div>
                    <div v-else>
                        <img :src="avatar" class="avatar" />
                    </div>
                    <div class="flex flex-center" v-if="!isEdit">
                        <a class="btn-rounded btn-empty btn-auto" @click="openAvatarFileSelection">{{ translateText('message.add_avatar_image') }}</a>
                    </div>
                    <!-- /// End Member Avatar /// -->

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="firstName" v-model="firstName" type="text" v-bind:label="translateText('placeholder.first_name')"></input-field>
                                <error
                                    v-if="validationMessages.firstName && validationMessages.firstName.length"
                                    v-for="message in validationMessages.firstName"
                                    :message="message" />
                            </div>
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="lastName" v-model="lastName" type="text" v-bind:label="translateText('placeholder.last_name')"></input-field>
                                <error
                                    v-if="validationMessages.lastName && validationMessages.lastName.length"
                                    v-for="message in validationMessages.lastName"
                                    :message="message" />
                            </div>
                        </div>
                    </div>

                    <hr class="double">

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="username" v-model="username" type="text" v-bind:label="translateText('placeholder.username')"></input-field>
                                <error
                                    v-if="validationMessages.username && validationMessages.username.length"
                                    v-for="message in validationMessages.username"
                                    :message="message" />
                            </div>
                            
                            <div class="col-md-6">
                                <multi-select-field
                                        v-bind:title="translateText('placeholder.role')"
                                        v-bind:options="projectRolesForMultiSelect"
                                        v-model="selectedRoles" />
                                <!--<a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_role') }}</a>-->
                            </div>
                        </div>
                    </div>
                    <!-- /// End Member Name & Role /// --> 

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-4">
                                <input-field :content="company" v-model="company" type="text" v-bind:label="translateText('placeholder.company')"></input-field>
                            </div>
                            <div class="col-md-4">
                            <multi-select-field
                                        v-bind:title="translateText('placeholder.department')"
                                        v-bind:options="projectDepartmentsForMultiSelect"
                                        v-model="departments" />
                            <!--<a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_department') }}</a>-->
                            </div>
                            <div class="col-md-4">
                            <multi-select-field
                                        v-bind:title="translateText('placeholder.subteam')"
                                        v-bind:options="subteamsForSelect"
                                        v-model="subteams" />
                                <!--<a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_subteam') }}</a>-->
                            </div>
                        </div>
                    </div> 
                    <!-- /// End Member Name & Role /// --> 

                    <hr class="double nomarginbottom">

                    <!-- /// Member Settings /// -->
                    <div class="row">
                        <div class="col-md-4">
                            <h3>{{ translateText('message.resources') }}</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="resource" :selected="resource"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>{{ translateText('table_header_cell.rasci') }}</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="rasci" :selected="rasci"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>{{ translateText('table_header_cell.org') }}</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="org" :selected="org"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                    </div>
                    <!-- /// End Member Settings /// -->

                    <!-- /// Distribution Lists /// -->
                    <h3>{{ translateText('table_header_cell.distribution_lists') }}</h3>
                    <div class="row">
                        <div class="col-md-4" v-for="(dl, index) in distributionLists">
                            <h4 v-if="dl.sequence === -1">{{ translateText(dl.name) }}</h4>
                            <h4 v-else>{{ dl.name }}</h4>
                            <div class="flex flex-v-center">
                                <switches v-model="distribution[dl.id]" :selected="distribution[dl.id]"></switches>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Distribution Lists /// -->

                    <hr class="double">

                    <!-- /// Member Contact Info /// -->
                    <h3>{{ translateText('table_header_cell.contact') }}</h3>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="email" v-model="email" type="text" v-bind:label="translateText('placeholder.email')"></input-field>
                                <error
                                    v-if="validationMessages.email && validationMessages.email.length"
                                    v-for="message in validationMessages.email"
                                    :message="message" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="phone" v-model="phone" type="text" v-bind:label="translateText('placeholder.phone')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="facebook" v-model="facebook" type="text" v-bind:label="translateText('placeholder.facebook')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="twitter" v-model="twitter" type="text" v-bind:label="translateText('placeholder.twitter')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="linekedIn" v-model="linkedIn" type="text" v-bind:label="translateText('placeholder.linked_in')"></input-field>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field :disabled="isEdit" :content="gplus" v-model="gplus" type="text" v-bind:label="translateText('placeholder.gplus')"></input-field>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Distribution Lists /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveMember" class="btn-rounded btn-auto second-bg">{{ translateText('button.save_member') }}</a>
                        <a v-else @click="editMember" class="btn-rounded btn-auto second-bg">{{ translateText('button.edit_member') }}</a>
                    </div>
                    <!-- /// Actions /// -->
                </div> 
            </div>
        </div>
        <alert-modal v-if="showFailed" @close="showFailed = false" body="message.unable_to_save" />               
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import SelectField from '../../_common/_form-components/SelectField';
import Switches from '../../3rdparty/vue-switches';
import AvatarPlaceholder from '../../_common/_form-components/AvatarPlaceholder';
import Error from '../../_common/_messages/Error.vue';
import router from '../../../router';
import AlertModal from '../../_common/AlertModal.vue';

export default {
    components: {
        InputField,
        SelectField,
        Switches,
        AvatarPlaceholder,
        MultiSelectField,
        Error,
        AlertModal,
    },
    methods: {
        ...mapActions([
            'getDistributionLists', 'getProjectRoles', 'getProjectDepartments',
            'saveProjectUser', 'getSubteams', 'getProjectUser', 'updateTeamMember', 'emptyValidationMessages',
        ]),
        openAvatarFileSelection() {
            document.getElementById('avatar').click();
        },
        updateAvatar(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.avatar = e.target.result;
            };
            reader.readAsDataURL(files[0]);
            this.avatarFile = files[0];
        },
        translateText(text) {
            return this.translate(text);
        },
        saveMember() {
            let list = [];
            this.distribution.forEach((item, index) => {
                if (item) {
                    list.push(index);
                }
            });

            const data = {
                'firstName': this.firstName,
                'lastName': this.lastName,
                'username': this.username,
                'company': this.company,
                'showInResources': this.resource,
                'showInRasci': this.rasci,
                'showInOrg': this.org,
                'email': this.email,
                'phone': this.phone,
                'facebook': this.facebook,
                'twitter': this.twitter,
                'linkedIn': this.linkedIn,
                'gplus': this.gplus,
                'avatarFile[file]': this.avatarFile instanceof window.File ? this.avatarFile : '',
                'project': this.$route.params.id,
                'distributionLists': list,
                'roles': this.selectedRoles.filter((item) => item.key).map((item) => item.key),
                'departments': this.departments.filter((item) => item.key).map((item) => item.key),
                'subteams': this.subteams.filter((item) => item.key).map((item) => item.key),
            };

            this.saveProjectUser(data)
                .then(
                    (data) => {
                        if (data && !data.error) {
                            router.push({
                                name: 'project-organization',
                            });
                        } else {
                            this.showFailed = true;
                        }
                    },
                    (error) => {
                        this.showFailed = true;
                    }
                );
        },
        editMember: function() {
            let list = [];
            this.distribution.forEach((item, index) => {
                if (item) {
                    list.push(index);
                }
            });
            const data = {
                'id': this.member.id,
                'company': this.company,
                'showInResources': this.resource,
                'showInRasci': this.rasci,
                'showInOrg': this.org,
                'distributionLists': list,
                'roles': this.selectedRoles.filter((item) => item.key).map((item) => item.key),
                'departments': this.departments.filter((item) => item.key).map((item) => item.key),
                'subteams': this.subteams.filter((item) => item.key).map((item) => item.key),
            };
            this.updateTeamMember(data)
                .then(
                    (data) => {
                        if (data && !data.error) {
                            router.push({
                                name: 'project-organization',
                            });
                        } else {
                            this.showFailed = true;
                        }
                    },
                    (error) => {
                        this.showFailed = true;
                    }
                );
        },
    },
    created() {
        this.getDistributionLists({projectId: this.$route.params.id});
        this.getProjectRoles(this.$route.params.id);
        this.getProjectDepartments({project: this.$route.params.id});
        this.getSubteams({project: this.$route.params.id});
        if (this.$route.params.userId) {
            this.getProjectUser(this.$route.params.userId);
            this.isEdit = true;
        }
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    computed: mapGetters({
        distributionLists: 'distributionLists',
        member: 'currentMember',
        projectRolesForMultiSelect: 'projectRolesForMultiSelect',
        projectDepartmentsForMultiSelect: 'projectDepartmentsForMultiSelect',
        subteamsForSelect: 'subteamsForSelect',
        validationMessages: 'validationMessages',
    }),
    watch: {
        member(value) {
            const names = this.member.userFullName.split(' ');
            this.avatar = this.member.userAvatar;
            this.firstName = names[0] ? names[0] : '';
            this.lastName = names[1] ? names[1] : '';
            this.username = this.member.userUsername;
            this.email = this.member.userEmail;
            this.facebook = this.member.userFacebook;
            this.twitter = this.member.userTwitter;
            this.phone = this.member.userPhone;
            this.linkedIn = this.member.userLinkedIn;
            this.gplus = this.member.userGplus;
            this.company = this.member.company;
            for (let i = 0; i < this.member.projectRoles.length; i++) {
                this.selectedRoles.push({key: this.member.projectRoles[i], label: this.member.projectRoleNames[i]});
            }
            for (let i = 0; i < this.member.projectDepartments.length; i++) {
                this.departments.push({key: this.member.projectDepartments[i], label: this.member.projectDepartmentNames[i]});
            }
            for (let i = 0; i < this.member.subteams.length; i++) {
                this.subteams.push({key: this.member.subteams[i], label: this.member.subteamNames[i]});
            }
            this.resource = this.member.showInResource;
            this.rasci = this.member.showInRasci;
            this.org = this.member.showInOrg;
        },
        distributionLists(value) {
            let distLength = 0;
            this.distributionLists.map((item) => {
                distLength = item.id > distLength ? item.id : distLength;
            });
            this.distribution.length = distLength;
            this.distributionLists.map((item) => {
                this.distribution[item.id] = false;
                if (this.isEdit) {
                    for (let i = 0; i < item.users.length; i++) {
                        this.distribution[item.id] = item.users[i].id === this.member.user;
                    }
                }
            });
        },
    },
    data: function() {
        return {
            showFailed: false,
            avatar: '',
            avatarFile: '',
            firstName: '',
            lastName: '',
            username: '',
            company: '',
            department: '',
            role: '',
            resource: false,
            rasci: false,
            org: false,
            email: '',
            phone: '',
            facebook: '',
            twitter: '',
            linkedIn: '',
            gplus: '',
            distribution: [],
            selectedRoles: [],
            departments: [],
            subteams: [],
            isEdit: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';

    .avatar {
        margin: 0 auto 20px;
        display: block;
        height: 255px;
        width: 255px;
        object-fit: cover;
        @include border-radius(50%);
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 1.6px;
        margin-bottom: 15px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }
</style>
