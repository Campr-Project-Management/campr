<template>
    <div class="locale-switcher">
        <button
                type="button"
                v-tooltip.bottom="translate('message.locale_switcher.english')"
                :class="{'active-locale': isActive('en')}"
                @click="onSwitch('en')">
            <img src="../../assets/english.png" :alt="translate('message.english')">
        </button>

        <button
                type="button"
                v-tooltip.bottom="translate('message.locale_switcher.german')"
                :class="{'active-locale': isActive('de')}"
                @click="onSwitch('de')">
            <img src="../../assets/german.png" :alt="translate('message.german')">
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
    @import '../../css/_variables';

    .locale-switcher {
        margin-right: 20px;
        padding-top: 9px;

        button {
            border: none;
            background: transparent;
            padding: 0;
            margin: 0 0 0 10px;
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
