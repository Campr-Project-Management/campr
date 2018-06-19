<template>
    <div
            class="user-avatar"
            :class="size"
            v-tooltip.top-center="this.tooltip">
        <letter-avatar
                v-if="name && !lazyUrl"
                :ratio="ratio"
                :name="name"
                :size="letterAvatarSize"/>
        <img
                v-if="lazyUrl"
                class="avatar-image"
                :src="lazyUrl"
                :alt="name"/>
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
                    return ['very-small', 'small', 'normal', 'medium', 'large'].indexOf(value) >= 0;
                },
            },
            ratio: {
                type: Number,
                default: window.devicePixelRatio ? window.devicePixelRatio : 1,
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
        watch: {
            url(value) {
                this.lazyUrl = value;
                if (!this.lazyUrl) {
                    return;
                }

                let image = new Image();
                image.src = this.lazyUrl;

                image.onerror = () => {
                    this.lazyUrl = null;
                };
            },
        },
        data() {
            return {
                letterAvatarSizes: {
                    'very-small': 20,
                    'small': 30,
                    'normal': 40,
                    'medium': 50,
                    'large': 60,
                },
                lazyUrl: this.url,
                sizes: {
                    'very-small': 20,
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
            @include border-radius(50%);
        }

        &.very-small {
            width: 20px;
            height: 20px;

            .avatar-image {
                width: 20px;
                height: 20px;
            }
        }

        &.small {
            width: 30px;
            height: 30px;

            .avatar-image {
                width: 30px;
                height: 30px;
            }
        }

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
