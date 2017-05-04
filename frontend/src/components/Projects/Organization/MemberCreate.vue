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
                        <h1>{{ translateText('message.create_new_team_member') }}</h1>
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
                    <div class="flex flex-center">
                        <a class="btn-rounded btn-empty btn-auto" @click="openAvatarFileSelection">{{ translateText('message.add_avatar_image') }}</a>
                    </div>
                    <!-- /// End Member Avatar /// -->

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field v-model="firstName" type="text" v-bind:label="translateText('placeholder.first_name')"></input-field>
                            </div>
                            <div class="col-md-6">
                                <input-field v-model="lastName" type="text" v-bind:label="translateText('placeholder.last_name')"></input-field>
                            </div>
                        </div>
                    </div>

                    <hr class="double">

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6"><input-field v-model="username" type="text" v-bind:label="translateText('placeholder.username')"></input-field></div>
                            <div class="col-md-6">
                                <multi-select-field
                                        v-bind:title="translateText('placeholder.role')"
                                        v-bind:options="projectRolesForSelect"
                                        v-bind:selectedOptions="selectedRoles"
                                        v-model="selectedRoles" />
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_role') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Member Name & Role /// --> 

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-4"><input-field v-model="company" type="text" v-bind:label="translateText('placeholder.company')"></input-field></div>
                            <div class="col-md-4">
                            <multi-select-field
                                        v-bind:title="translateText('placeholder.department')"
                                        v-bind:options="projectDepartmentsForSelect"
                                        v-bind:selectedOptions="departments"
                                        v-model="departments" />
                            <a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_department') }}</a>
                            </div>
                            <div class="col-md-4">
                            <multi-select-field
                                        v-bind:title="translateText('placeholder.subteam')"
                                        v-bind:options="subteamsForSelect"
                                        v-bind:selectedOptions="subteams"
                                        v-model="subteams" />
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">{{ translateText('button.add_another_subteam') }}</a>
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
                                <switches v-model="resource" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>{{ translateText('table_header_cell.raci') }}</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="raci" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>{{ translateText('table_header_cell.org') }}</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="org" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                    </div>
                    <!-- /// End Member Settings /// -->

                    <!-- /// Distribution Lists /// -->
                    <h3>{{ translateText('table_header_cell.distribution_lists') }}</h3>
                    <div class="row">
                        <div class="col-md-4" v-for="dl in project.distributionLists">
                            <h4>{{ dl.name }}</h4>
                            <div class="flex flex-v-center">
                                <switches v-model="distribution[dl.id]" :selected="false"></switches>
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
                                <input-field v-model="email" type="text" v-bind:label="translateText('placeholder.email')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="phone" type="text" v-bind:label="translateText('placeholder.phone')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="facebook" type="text" v-bind:label="translateText('placeholder.facebook')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="twitter" type="text" v-bind:label="translateText('placeholder.twitter')"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="linkedIn" type="text" v-bind:label="translateText('placeholder.linked_in')"></input-field>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field v-model="gplus" type="text" v-bind:label="translateText('placeholder.gplus')"></input-field>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Distribution Lists /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a @click="saveMember" class="btn-rounded btn-auto second-bg">{{ translateText('button.save_member') }}</a>
                    </div>
                    <!-- /// Actions /// -->
                </div> 
            </div>
        </div>               
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import MultiSelectField from '../../_common/_form-components/MultiSelectField';
import SelectField from '../../_common/_form-components/SelectField';
import Switches from '../../3rdparty/vue-switches';
import AvatarPlaceholder from '../../_common/_form-components/AvatarPlaceholder';

export default {
    components: {
        InputField,
        SelectField,
        Switches,
        AvatarPlaceholder,
        MultiSelectField,
    },
    methods: {
        ...mapActions(['createNewOrganizationMember', 'getProjectById', 'getProjectRoles', 'getProjectDepartments', 'saveProjectUser', 'getSubteams']),
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
                'showInRaci': this.raci,
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
            this.saveProjectUser(data);
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
        this.getProjectRoles();
        this.getProjectDepartments();
        this.getSubteams();
    },
    computed: mapGetters({
        project: 'project',
        projectRolesForSelect: 'projectRolesForSelect',
        projectDepartmentsForSelect: 'projectDepartmentsForSelect',
        subteamsForSelect: 'subteamsForSelect',
    }),
    data: function() {
        return {
            avatar: '',
            avatarFile: '',
            firstName: '',
            lastName: '',
            username: '',
            company: '',
            department: '',
            role: '',
            resource: '',
            raci: '',
            org: '',
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
