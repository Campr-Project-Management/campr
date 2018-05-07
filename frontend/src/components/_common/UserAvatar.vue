<template>
    <div
            class="user-avatar"
            :class="size"
            v-tooltip.top-center="this.tooltip">
        <letter-avatar
                v-if="name"
                :name="name"
                :size="letterAvatarSize"/>
        <span
                v-if="url"
                class="avatar-image"
                :style="{ backgroundImage: 'url(' + url + ')' }"></span>
    </div>
</template>

<script>
    import LetterAvatar from './LetterAvatar';

    export default {
        name: 'user-avatar',
        props: {
            url: {
                type: String,
                required: false,
            },
            name: {
                required: true,
            },
            size: {
                type: String,
                required: false,
                default: 'normal',
                validate(value) {
                    return ['small', 'normal', 'medium', 'large'].indexOf(value) >= 0;
                },
            },
            tooltip: {
                type: String,
                required: false,
            },
        },
        components: {
            LetterAvatar,
        },
        computed: {
            letterAvatarSize() {
                return this.letterAvatarSizes[this.size];
            },
        },
        data() {
            return {
                letterAvatarSizes: {
                    'small': 30,
                    'normal': 40,
                    'medium': 50,
                    'large': 60,
                },
            };
        },
    };
</script>

<style scoped lang="scss">
    @import '../../css/_mixins.scss';

    .user-avatar {
        display: inline-block;
        position: relative;
        margin: 5px;

        .avatar-image {
            top: 0;
            left: 0;
            position: absolute;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            @include border-radius(50%);
        }

        &.small {
            width: 30px;
            height: 30px;

            .avatar-image {
                width: 30px;
                height: 30px;
            }
        },

        &.normal {
            width: 40px;
            height: 40px;

            .avatar-image {
                width: 40px;
                height: 40px;
            }
        }

        &.medium {
            width: 50px;
            height: 50px;

            .avatar-image {
                width: 50px;
                height: 50px;
            }
        }

        &.large {
            width: 60px;
            height: 60px;

            .avatar-image {
                width: 60px;
                height: 60px;
            }
        }
    }
</style>
