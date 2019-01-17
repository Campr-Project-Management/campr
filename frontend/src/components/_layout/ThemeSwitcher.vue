<template>
    <div class="theme-switcher">
        <button
                type="button"
                v-tooltip.bottom="translate(`message.theme_switcher.${$themeName}`)"
                class="glyphicon glyphicon-adjust"
                :class="[`btn-${$themeName}-theme`]"
                @click="onSwitch"></button>
    </div>
</template>

<script>
    import {DARK, LIGHT} from '../../util/theme';
    import {mapActions} from 'vuex';

    export default {
        name: 'theme-switcher',
        methods: {
            ...mapActions([
                'switchTheme',
            ]),
            isActive(locale) {
                return this.locale === locale;
            },
            onSwitch() {
                let theme = DARK;
                if (this.$themeName === DARK) {
                    theme = LIGHT;
                }

                this.switchTheme(theme);
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../css/_common';
    @import '~theme/variables';

    .theme-switcher {
        margin: 0 20px 0 0;
        padding-top: 9px;

        button {
            border: none;
            background: transparent;
            padding: 0;
            height: 24px;
            width: 24px;
            font-size: 24px;

            &.btn-dark-theme {
                color: $lightColor;
                transform: rotate(180deg);
                @include transition(all, 0.2s, ease);

                &:hover {
                    transform: rotate(0deg);
                    @include transition(all, 0.2s, ease);
                }
            }

            &.btn-light-theme {
                color: $middleColor;
                @include transition(all, 0.2s, ease);

                &:hover {
                    transform: rotate(180deg);
                    @include transition(all, 0.2s, ease);
                }
            }

            &:focus {
                outline: 0;
            }
        }
    }
</style>
