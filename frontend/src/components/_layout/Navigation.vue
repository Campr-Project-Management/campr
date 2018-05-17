<template>
    <header>
        <div class="flex flex-direction-reverse">
            <div class="dropdown menu-toggler">
                <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17px" height="17px" viewBox="0 0 17.805 16.354" enable-background="new 0 0 17.805 16.354" xml:space="preserve">
                        <g>
                            <g>
                                <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="4.354" x2="2.848" y2="4.354"/>
                            </g>
                            <g>
                                <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="8.354" x2="2.848" y2="8.354"/>
                            </g>
                            <g>
                                <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="12.354" x2="2.848" y2="12.354"/>
                            </g>
                            <g>
                                <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="16.354" x2="2.848" y2="16.354"/>
                            </g>
                        </g>
                    </svg>
                </span>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a :href="routes.account">{{ message.account }}</a></li>
                    <li><a :href="routes.back_to_campr">{{ message.back_to_campr }}</a></li>
                    <li v-if="showDashboard"><a :href="routes.admin_dashboard">{{ message.admin_dashboard }}</a></li>
                    <li><a :href="routes.logout">{{ message.logout }}</a></li>
                </ul>
            </div>
            <p class="user-message">{{ message.hi }}, <span>{{ user.firstName }} {{ user.lastName }}</span></p>
            <user-avatar
                    :url="user.avatarUrl"
                    :name="user.fullName"/>
            <!--TODO: Further implementation of notifications-->
            <!--<a class="notifications" href="">-->
                <!--<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"-->
              <!--width="26.438px" height="24.469px" viewBox="0 0 26.438 24.469" enable-background="new 0 0 26.438 24.469" xml:space="preserve">-->
                    <!--<g>-->
                        <!--<defs>-->
                            <!--<rect id="SVGID_1_" x="-946.781" y="-565.752" width="1920" height="1156.462"/>-->
                        <!--</defs>-->
                        <!--<clipPath id="SVGID_2_">-->
                            <!--<use xlink:href="#SVGID_1_"  display="none" overflow="visible"/>-->
                        <!--</clipPath>-->
                        <!--<line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-531.752" x2="938.262" y2="-531.752"/>-->
                        <!--<line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-527.752" x2="938.262" y2="-527.752"/>-->
                        <!--<line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-523.752" x2="938.262" y2="-523.752"/>-->
                        <!--<line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-519.752" x2="938.262" y2="-519.752"/>-->
                        <!--<polyline clip-path="url(#SVGID_2_)" fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="-->
                        <!--677.566,-524.187 676.523,-524.187 672.35,-520.013 672.35,-524.187 669.219,-524.187 669.219,-536.709 689.045,-536.709-->
                        <!--689.045,-529.926 	"/>-->
                        <!--<polygon clip-path="url(#SVGID_2_)" fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="-->
                        <!--680.697,-518.97 686.436,-518.97 691.132,-514.796 691.132,-518.97 693.219,-518.97 693.219,-527.317 680.697,-527.317 	"/>-->
                        <!--<path display="none" clip-path="url(#SVGID_2_)" fill="#5EBCA2" d="M701.219-540.752c0,5.523-4.477,10-10,10s-10-4.477-10-10-->
                        <!--s4.477-10,10-10S701.219-546.275,701.219-540.752"/>-->
                    <!--</g>-->
                    <!--<polyline fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="-->
                    <!--9.566,13.813 8.523,13.813 4.35,17.987 4.35,13.813 1.219,13.813 1.219,1.291 21.045,1.291 21.045,8.074 "/>-->
                    <!--<polygon fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="-->
                    <!--12.697,19.03 18.436,19.03 23.132,23.204 23.132,19.03 25.219,19.03 25.219,10.683 12.697,10.683 "/>-->
                <!--</svg>-->
                <!--<span class="notification-balloon">5</span>-->
            <!--</a>-->
        </div>
        <div v-show="currentProjectName" class="project-title" v-if="showProjectName">
            <p>{{ message.project }}</p>
            <h4>{{ currentProjectName }}</h4>
        </div>
    </header>
</template>

<script>
import {mapGetters} from 'vuex';
import UserAvatar from '../_common/UserAvatar';

export default {
    name: 'navigation',
    props: ['user'],
    components: {
        UserAvatar,
    },
    computed: {
        ...mapGetters({
            currentProjectName: 'currentProjectName',
        }),
        userAvatar: function() {
            return this.user.avatarUrl;
        },
        showDashboard: function() {
            return this.user.isAdmin;
        },
        showProjectName: function() {
            let path = this.$route.fullPath;
            let regx = /^\/projects\//;
            return regx.exec(path);
        },
    },
    data: function() {
        return {
            message: {
                hi: Translator.trans('message.hi'),
                project: Translator.trans('message.project'),
                account: Translator.trans('link.account'),
                back_to_campr: Translator.trans('link.back_to_campr'),
                logout: Translator.trans('link.logout'),
                admin_dashboard: Translator.trans('link.admin_dashboard'),
            },
            routes: {
                logout: Routing.generate('app_logout'),
                back_to_campr: Routing.generate('main_homepage'),
                admin_dashboard: Routing.generate('app_admin_dashboard'),
                account: Routing.generate('main_user_account'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/_variables';

    svg {
        stroke: $lightColor;
    }

    header {
        height: 80px;
        border-bottom: 1px solid $darkColor;
        padding: 20px 0;
        display: flex;
        flex-direction: row-reverse;
        justify-content: space-between;
        color: $lightColor;
    }

    .project-title {
        p {
            text-transform: uppercase;
            color: $middleColor;
            font-size: 8px;
            line-height: 12px;
            margin: 4px 0 10px;
        }

        h4 {
            text-transform: uppercase;
            color: $lighterColor;
            font-size: 14px;
            margin-top: 0;
        }
    }

    .menu-toggler {
        width: 34px;
        cursor: pointer;
        text-align: right;
        padding-top: 10px;
    }

    .user-message {
        text-transform: uppercase;
        margin: 0 30px 0 0;
        line-height: 45px;
        font-size: 12px;
        letter-spacing: 1.2px;
    }

    .notifications {
        position: relative;
        display: block;
        width: 50px;
        margin-right: 7px;
        padding-top: 8px;

        .notification-balloon {
            right: 5px;
            top: -5px;
            font-size: 11px;
        }
    }
</style>
