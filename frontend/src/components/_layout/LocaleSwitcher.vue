<template>
    <div class="locale-switcher">
        <button
                type="button"
                v-tooltip.bottom="translate('message.locale_switcher.en')"
                :class="{'active-locale': isActive('en')}"
                @click="onSwitch('en')">
            <img src="../../assets/english.png" :alt="translate('message.locale_switcher.en')">
        </button>

        <button
                type="button"
                v-tooltip.bottom="translate('message.locale_switcher.de')"
                :class="{'active-locale': isActive('de')}"
                @click="onSwitch('de')">
            <img src="../../assets/german.png" :alt="translate('message.locale_switcher.de')">
        </button>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';

    export default {
        name: 'locale-switcher',
        computed: {
            ...mapGetters([
                'user',
                'locale',
            ]),
        },
        methods: {
            ...mapActions([
                'switchLocale',
            ]),
            isActive(locale) {
                return this.locale === locale;
            },
            onSwitch(locale) {
                this.switchLocale(locale);
            },
        },
    };
</script>

<style scoped lang="scss">
    @import '../../css/_common';
    @import '~theme/variables';

    .locale-switcher {
        margin: 0 10px 0 10px;
        padding-top: 9px;

        button {
            border: none;
            background: transparent;
            padding: 0;
            margin: 0 5px 0 5px;
            height: 24px;
            width: 24px;
            opacity: 0.3;

            &.active-locale {
                opacity: 1;
            }

            img {
                height: 100%;
                width: auto;

                @include opacity(0.8);
                @include transition(all, 0.2s, ease);
            }

            &:focus {
                outline: 0;
            }

            &:hover {
                img {
                    @include opacity(1);
                }
            }
        }
    }
</style>
