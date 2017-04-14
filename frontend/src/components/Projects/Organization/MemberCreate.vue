<template>
    <div class="create-task page-section">
        <div class="row">
            <div class="col-md-6">
                <!-- /// Header /// -->
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            Back to Project Organization
                        </router-link>
                        <h1>Create new team member</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Member Avatar /// -->
                    <input id="avatar" type="file" name="avatar" style="display: none;" accept="image/*" v-on:change="updateAvatar"> 

                    <div v-if="!avatar">
                        <avatar-placeholder />
                    </div>
                    <div v-else>
                        <img :src="avatar" class="avatar" />
                    </div>
                    <div class="flex flex-center">
                        <a class="btn-rounded btn-empty btn-auto" v-on:click="openAvatarFileSelection">Add avatar image</a>
                    </div>
                    <!-- /// End Member Avatar /// -->

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-6"><input-field v-model="name" type="text" v-bind:label="label.name"></input-field></div>
                            <div class="col-md-6">
                                <select-field v-bind:title="label.role"></select-field>
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">Add another role</a>
                            </div>
                        </div>
                    </div> 
                    <!-- /// End Member Name & Role /// --> 

                    <hr class="double">

                    <!-- /// Member Name & Role /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6"><input-field v-model="name" type="text" v-bind:label="label.name"></input-field></div>
                            <div class="col-md-6">
                                <select-field v-bind:title="label.role"></select-field>
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">Add another role</a>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="col-md-4"><input-field v-model="name" type="text" v-bind:label="label.company"></input-field></div>
                            <div class="col-md-4">
                                <select-field v-bind:title="label.department"></select-field>
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">Add another department</a>
                            </div>
                            <div class="col-md-4">
                                <select-field v-bind:title="label.subteam"></select-field>
                                <a class="btn-rounded btn-empty btn-md btn-auto margintop20">Add another subteam</a>
                            </div>
                        </div>
                    </div> 
                    <!-- /// End Member Name & Role /// --> 

                    <hr class="double nomarginbottom">

                    <!-- /// Member Settings /// -->
                    <div class="row">
                        <div class="col-md-4">
                            <h3>Resources</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="test" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>Raci</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="test" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                        <div class="col-md-4">
                            <h3>Org</h3>
                            <div class="flex flex-v-center">
                                <switches v-model="test" :selected="false"></switches>
                            </div>
                            <hr class="nomarginbottom">
                        </div>
                    </div>
                    <!-- /// End Member Settings /// -->

                    <!-- /// Distribution Lists /// -->
                    <h3>Distribution Lists</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <h4>TP Meeting</h4>
                            <div class="flex flex-v-center">
                                <switches v-model="test" :selected="false"></switches>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4>EK Meeting</h4>
                            <div class="flex flex-v-center">
                                <switches v-model="test" :selected="false"></switches>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Distribution Lists /// -->

                    <hr class="double">

                    <!-- /// Member Contact Info /// -->
                    <h3>Contact</h3>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.email"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.phone"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.facebook"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.twitter"></input-field>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.linkedIn"></input-field>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="col-md-6">
                                <input-field v-model="name" type="text" v-bind:label="label.googlePlus"></input-field>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Distribution Lists /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-organization'}" class="btn-rounded btn-auto disable-bg">{{ button.cancel }}</router-link>
                        <a v-on:click="createTask" class="btn-rounded btn-auto second-bg">{{ button.save_member }}</a>
                    </div>
                    <!-- /// Actions /// -->
                </div> 
            </div>
        </div>               
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import SelectField from '../../_common/_form-components/SelectField';
import Switches from '../../3rdparty/vue-switches';
import AvatarPlaceholder from '../../_common/_form-components/AvatarPlaceholder';

export default {
    components: {
        InputField,
        SelectField,
        Switches,
        AvatarPlaceholder,
    },
    methods: {
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
        },
    },
    data: function() {
        return {
            label: {
                name: 'Name',
                role: 'Role',
                company: 'Company',
                department: 'Department',
                subteam: 'Subteam',
                email: 'Email',
                phone: 'Phone',
                facebook: 'Facebook',
                twitter: 'Twitter',
                linkedIn: 'LinkedIn',
                googlePlus: 'Google Plus',
            },
            button: {
                cancel: 'Cancel',
                save_member: 'Save Member',
            },
            avatar: '',
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
