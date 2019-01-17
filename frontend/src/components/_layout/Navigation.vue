<template>
    <header>
        <div class="flex">
            <div class="flex">
                <locale-switcher/>
                <theme-switcher/>
            </div>
            <div class="dropdown menu-toggler">
                <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user">
                        <user-avatar
                                :name="user.fullName"
                                :url="userAvatar"
                                size="small"/>
                        <p class="user-message">{{ 'message.hi'|trans }}, <span>{{ user.fullName }}</span></p>
                    </div>
                    <i class="fa fa-angle-down"></i>
                </span>

                <ul class="dropdown-menu dropdown-menu-right" aria-haspopup="true" aria-expanded="false">
                    <li><a :href="routes.account">{{ 'link.account'|trans }}</a></li>
                    <li><a :href="routes.back_to_campr">{{ 'link.back_to_campr'|trans }}</a></li>
                    <li v-if="showDashboard"><a :href="routes.admin_dashboard">{{ 'link.admin_dashboard'|trans }}</a>
                    </li>
                    <li><a :href="routes.logout">{{ 'link.logout'|trans }}</a></li>
                </ul>
            </div>
        </div>
        <div v-show="currentProjectName" class="project-title" v-if="showProjectName">
            <p>{{ 'message.project'|trans }}</p>
            <h4>{{ currentProjectName }}</h4>
        </div>
    </header>
</template>

<script>
    import {mapGetters} from 'vuex';
    import UserAvatar from '../_common/UserAvatar';
    import LocaleSwitcher from './LocaleSwitcher';
    import ThemeSwitcher from './ThemeSwitcher';

    export default {
        name: 'navigation',
        props: ['user'],
        components: {
            ThemeSwitcher,
            LocaleSwitcher,
            UserAvatar,
        },
        computed: {
            ...mapGetters([
                'currentProjectName',
            ]),
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
    @import '~theme/variables';

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
        margin: 5px 0;
    }

    .project-title {
        p {
            text-transform: uppercase;
            color: $middleColor;
            font-size: 10px;
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
        cursor: pointer;
        text-align: right;

        svg {
            stroke: $lightColor;
        }

        i {
            font-size: 20px;
        }

        .dropdown-toggle {
            user-select: none;
            display: flex;
            align-items: center;
        }
    }

    .user {
        display: flex;
        align-items: center;
    }

    .user-message {
        text-transform: uppercase;
        line-height: 45px;
        font-size: 12px;
        letter-spacing: 1.2px;
        margin: 0 10px 0 0;
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
