<template>
    <div class="member-badge" v-bind:class="size">
        <img v-bind:src="item.userAvatar" />
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

export default {
    props: ['size', 'item'],
    components: {
        SocialLinks,
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
    @import '../../css/_variables';

    .member-badge {
        text-align: center;

        .social-links {
            margin-top: 15px;

            a {
                margin: 0 3px;
            }

        }

        .name {
            margin-top: 17px;
            text-transform: uppercase;
            letter-spacing: 2.1px;
        }

        .title {
            text-transform: uppercase;
            font-size: 10px;
            margin-top: 3px;
            color: $middleColor;
        }

        img {
            border-radius: 50%;
        }

        &.small {
            margin: 0 15px 13px;
            width: 112px;

            img {
                width: 100%;
                height: 112px
            }

            .title {
                margin-top: 11px;
            }

            .social-links {
                margin-top: 10px;

                a {
                    margin: 0 1px;
                }
            }
        }

        &.big {
            margin: 0 14px 15px;
            width: 254px;

            img {
                width: 100%;
                height: 252px;
            }
        }
    }
</style>
