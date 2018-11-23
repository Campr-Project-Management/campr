<template>
    <div class="member-badge" v-bind:class="size">
        <user-avatar
                size="normal"
                :name="item.userFullName"
                :url="item.userAvatarUrl" />
        <div class="name">{{ item.userFullName }}</div>
        <div class="title" v-for="role in item.projectRoleNames">{{ translateText(role) }}</div>
        <div class="title" v-for="role in item.subteamRoleNames">{{ translateText(role) }}</div>
        <social-links size="16px"
            v-bind:facebook="item.userFacebook"
            v-bind:twitter="item.userTwitter"
            v-bind:linkedin="item.userLinkedIn"
            v-bind:gplus="item.userGplus"
            v-bind:email="item.userEmail"></social-links>
    </div>
</template>

<script>
import SocialLinks from '../_common/SocialLinks';
import UserAvatar from '../_common/UserAvatar';

export default {
    props: ['size', 'item'],
    components: {
        SocialLinks,
        UserAvatar,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '~theme/variables';
    @import '../../css/_mixins';

    .member-badge {
        text-align: center;
        display: inline-block;
        margin: 0 30px 30px;

        .social-links {
            margin-top: 5px;

            a {
                margin: 0 3px;
            }

        }

        .name {
            margin-top: 17px;
            text-transform: uppercase;
            letter-spacing: 2.1px;
            line-height: 1.1em;
        }

        .title {
            text-transform: uppercase;
            font-size: 10px;
            margin-top: 3px;
            color: $middleColor;
        }

        &.small {
            margin: 0 30px 10px;
            width: 112px;

            .social-links {
                a {
                    margin: 0 1px;
                }
            }
        }

        &.big {
            width: 252px;
        }

        &.first-member-badge {
            &:before {
                display: none;
            }
        }

        &:before {
            content: '';
            width: 1px;
            height: 30px;
            background-color: $middleColor;
            position: absolute;
            top: -40px;
            left: 50%;
        }
    }
</style>
