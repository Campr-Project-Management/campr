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
                        <h1>{{member.userFullName}}<br/>
                            <span class="small">
                                <b class="second-color">
                                    <a :href="'mailto:' + member.userEmail">
                                        {{ member.userEmail }}
                                    </a>
                                </b>
                            </span>
                        </h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="row flex flex-v-center">
                    <div class="col-md-6">
                        <!-- /// Member Avatar /// -->
                        <div class="avatar">
                            <img :src="member.userAvatar" :alt="member.userFullName">
                        </div>
                        <!-- /// End Member Avatar /// -->
                    </div>
                    
                    <div class="col-md-6">
                        <!-- /// Member Name & Role /// -->
                        <div class="row member-details">
                            <div class="col-md-12">
                                <div class="member-info">
                                    <p>{{ translateText('message.role') }}: <b v-if="member.projectRoleNames && member.projectRoleNames.length > 0" v-for="(role, index) in member.projectRoleNames">{{ translateText(role) }}<span v-if="index < member.projectRoleNames.length - 1">,</span></b><b v-else>-</b></p>
                                    <p>{{ translateText('message.company') }}: <b v-if="member.company">{{ member.company }}</b><b v-else>-</b></p>
                                    <p>{{ translateText('message.department') }}: <b v-if="member.projectDepartmentNames && member.projectDepartmentNames.length > 0" v-for="(department, index) in member.projectDepartmentNames">{{ department }}<span v-if="index < member.projectDepartmentNames.length - 1">,</span></b><b v-else>-</b></p>
                                    <p>{{ translateText('message.subteam') }}: <b v-if="member.subteamNames && member.subteamNames.length > 0" v-for="(subteam, index) in member.subteamNames">{{ subteam }}<span v-if="index < member.subteamNames.length - 1">,</span></b><b v-else></b></p>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Member Name & Role /// -->
                    </div>
                </div>

                <hr class="double nomarginbottom">

                <!-- /// Member Settings /// -->
                <div class="row">
                    <div class="col-md-4">
                        <h3>{{ translateText('message.resources') }}</h3>
                        <div class="flex flex-v-center">
                            <switches :selected="member.showInResources" :disabled="true"></switches>
                        </div>
                        <hr class="nomarginbottom">
                    </div>
                    <div class="col-md-4">
                        <h3>{{ translateText('table_header_cell.rasci') }}</h3>
                        <div class="flex flex-v-center">
                            <switches :selected="member.showInRasci" :disabled="true"></switches>
                        </div>
                        <hr class="nomarginbottom">
                    </div>
                    <div class="col-md-4">
                        <h3>{{ translateText('table_header_cell.org') }}</h3>
                        <div class="flex flex-v-center">
                            <switches :selected="member.showInOrg" :disabled="true"></switches>
                        </div>
                        <hr class="nomarginbottom">
                    </div>
                </div>
                <!-- /// End Member Settings /// -->

                <!-- /// Distribution Lists /// -->
                <h3>{{ translateText('table_header_cell.distribution_lists') }}</h3>
                <div class="row">
                    <div class="col-md-4" v-for="dl in distributionLists">
                        <h4>{{ dl.name }}</h4>
                        <div class="flex flex-v-center">
                            <switches :disabled="true" :selected="inDistributionList(member, dl)"></switches>
                        </div>
                    </div>
                </div>
                <!-- /// End Distribution Lists /// -->

                <hr class="double">

                <!-- /// Member Contact Info /// -->
                <h3>{{ translateText('table_header_cell.contact') }}</h3>
                <div class="row marginbottom20">
                    <div class="col-md-12">
                        <div class="member-contact">
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                                    <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M10.4,8.1H9c0,2,0,4.7,0,4.7H7 c0,0,0-2.6,0-4.7H5.7V6.7H7V6c0-0.7,0.2-1.9,1.8-1.9l1.6,0v1.5c0,0-0.8,0-0.9,0C9.2,5.6,9,5.7,9,6.1v0.7h1.6L10.4,8.1z"/>
                                </svg>
                                {{ translateText('label.facebook') }}: <a :href="member.userFacebook" v-if="member.userFacebook">{{ member.userFacebook }}</a><span v-else>-</span>
                            </div>
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                                    <path class="st0" d="M8,0.2c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.2,8,0.2z M11.5,6.5c0,0.1,0,0.2,0,0.2 c0,2.3-1.8,5-5,5c-1,0-1.9-0.3-2.7-0.8c0.9,0.1,1.8-0.1,2.6-0.7c-0.8,0-1.4-0.5-1.6-1.2c0.3,0,0.5,0,0.8,0C4.8,8.8,4.2,8,4.2,7.2 c0,0,0,0,0,0C4.4,7.3,4.7,7.4,5,7.4C4.5,7.1,4.2,6.5,4.2,5.9c0-0.3,0.1-0.6,0.2-0.9C5.3,6.1,6.6,6.8,8,6.9c0-0.1,0-0.3,0-0.4 c0-1,0.8-1.8,1.8-1.8c0.5,0,1,0.2,1.3,0.6c0.4-0.1,1.3-0.2,1.3-0.2C12.1,5.5,11.9,6.2,11.5,6.5z"/>
                                </svg>
                                {{ translateText('label.twitter') }}: <a :href="member.userTwitter" v-if="member.userTwitter">{{ member.userTwitter }}</a><span v-else>-</span>
                            </div>
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                                    <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M6.3,11.4H5V6.7h1.3V11.4z M5.7,5.4C5.3,5.4,5,5.1,5,4.7c0-0.4,0.3-0.7,0.7-0.7s0.7,0.3,0.7,0.7C6.3,5.1,6,5.4,5.7,5.4z M12.3,11.4h-2V9.1 c0-0.2-0.1-0.3-0.3-0.3c-0.2,0-0.3,0.1-0.3,0.3v2.3h-2c0,0,0-4.3,0-4.7h2v0.6c0,0,0.3-0.5,1.1-0.5c1,0,1.5,0.7,1.5,2.2V11.4z"/>
                                </svg>
                                {{ translateText('label.linked_in') }}: <a :href="member.userLinkedIn" v-if="member.userLinkedIn">{{ member.userLinkedIn }}</a><span v-else>-</span>
                            </div>
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                                    <g>
                                        <path class="st0" d="M8.4,6.1c0-0.4-0.2-0.9-0.5-1.2C7.6,4.6,7.1,4.4,6.7,4.4c-0.4,0-0.8,0.2-1.1,0.4 C5.3,5.2,5.1,5.6,5.1,6c0,0.4,0.2,0.9,0.5,1.2C6.3,7.8,7.4,7.9,8,7.3C8.3,7,8.4,6.6,8.4,6.1z"/>
                                        <path class="st0" d="M6.8,9.9c-1.4,0-2.7,0.6-2.7,1.4c0,0.8,1.2,1.4,2.7,1.4c1.4,0,2.7-0.6,2.7-1.4 C9.4,10.5,8.2,9.9,6.8,9.9z"/>
                                        <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M6.8,13.3 c-1.9,0-3.3-0.9-3.3-2.1c0-1.2,1.5-2.1,3.3-2.1c0.6,0,1.1,0.1,1.5,0.2C7.9,9.2,7.5,8.9,7.4,8.3c-0.2,0-0.3,0.1-0.5,0.1 c-0.6,0-1.3-0.3-1.7-0.7C4.7,7.2,4.5,6.6,4.5,6c0-0.6,0.2-1.2,0.6-1.6c0.4-0.4,1-0.6,1.6-0.6v0h2.6v0.7h-1c0,0,0.1,0,0.1,0.1 c0.4,0.4,0.7,1,0.7,1.6c0,0.6-0.2,1.2-0.6,1.6C8.4,7.9,8.2,8,8.1,8C8,8.4,8.2,8.6,8.7,8.9c0.6,0.4,1.4,0.9,1.4,2.4 C10.1,12.4,8.6,13.3,6.8,13.3z M13.3,7.1H12v1.3h-0.7V7.1H10V6.4h1.3V5.1H12v1.3h1.3V7.1z"/>
                                    </g>
                                </svg>
                                {{ translateText('label.gplus') }}: <a :href="member.userGplus" v-if="member.userGplus">{{ member.userGplus }}</a><span v-else></span>
                            </div>
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                                    <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M3.7,7.4l2.9,1.5l-2.9,1.7V7.4z M12.3,12.1H3.7v-0.7l3.6-2.1L8,9.6l0.8-0.4l3.6,2.1V12.1z M12.3,10.6L9.5,8.9l2.9-1.5V10.6z M12.3,5.9L8,8.1L3.7,5.9V4.7h8.7V5.9z"
                                      />
                                </svg>
                                {{ translateText('label.email') }}: 
                                <a :href="'mailto:' + member.userEmail">
                                    {{ member.userEmail }}
                                </a>
                            </div>
                            <div class="social-contanct">
                                <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20.5 20.8">
                                <path class="st0" d="M10.3,0.5c-5.5,0-10,4.5-10,10c0,5.5,4.5,10,10,10s10-4.5,10-10C20.3,5,15.9,0.5,10.3,0.5z M15.9,15.2l-0.7,0.7c-0.7,0.7-1.7,0.8-2.5,0.3c-3.2-2.1-6-4.9-8.1-8.1C4.1,7.3,4.2,6.3,4.9,5.6l0.7-0.7c0.5-0.5,1.4-0.5,1.9,0 l1.3,1.3c0.5,0.5,0.5,1.4,0,1.9L8.5,8.5c1.1,1.4,2.4,2.7,3.8,3.8l0.4-0.4c0.5-0.5,1.4-0.5,1.9,0l1.3,1.3 C16.5,13.8,16.5,14.7,15.9,15.2z"/>
                                </svg>
                                {{ translateText('label.phone') }}: <a :href="'tel:' + member.userPhone" v-if="member.userPhone">{{ member.userPhone }}</a><span v-else></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Distribution Lists /// -->
            </div> 
        </div>               
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Switches from '../../3rdparty/vue-switches';

export default {
    components: {
        Switches,
    },
    methods: {
        ...mapActions(['getDistributionLists', 'getProjectUser']),
        translateText: function(text) {
            return this.translate(text);
        },
        inDistributionList: function(member, dl) {
            for (let i = 0; i < dl.users.length; i++) {
                if (dl.users[i].id == member.user) {
                    return true;
                }
            }
            return false;
        },
    },
    created() {
        if (this.$route.params.userId) {
            this.getProjectUser(this.$route.params.userId);
        }
        this.getDistributionLists({projectId: this.$route.params.id});
    },
    computed: {
        ...mapGetters({
            member: 'currentMember',
            distributionLists: 'distributionLists',
        }),
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
        text-align: center;

        img {
            display: inline-block;
            height: 225px;
            @include border-radius(50%);
        }
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

    .member-details {
        .member-info {
            border-right: 1px solid $darkerColor;
            padding: 0 30px;
            float: left;

            p {
                margin-bottom: 5px;
                text-transform: uppercase;
                color: $lightColor;

                b {
                    color: $lighterColor;
                }

                &:last-child {
                    margin-bottom: 0;
                }
            }

            &:first-child {
                padding-left: 0;
            }

            &:last-child {
                padding-right: 0;
                border-right: none;
            }
        }
    }

    .social-contanct {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid $darkerColor;
        text-transform: uppercase;
        letter-spacing: 0.1em;

        svg {
            fill: $secondColor;
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            position: relative;
            top: 5px;
        }

        a {
            color: $secondColor;
            text-transform: none;
            letter-spacing: 0;
            @include transition(color, 0.2s, ease);

            &:hover {
                color: $secondDarkColor;
            }
        }

        &:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
    }
</style>
