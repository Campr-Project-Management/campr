<script>
import VueFlashMessage from 'vue-flash-message';

export default {
    name: 'flash-message',
    extends: VueFlashMessage,
};
</script>

<style lang="scss">
    @import '~theme/variables';

    .flash__wrapper {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9;

        .flashpool,
        .flash__message {
            transition: all .5s;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            font-size: 13px;
        }

        .flash__message {
            text-align: center;
            box-sizing: border-box;
            padding: 15px 30px 15px 15px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            border-radius: 4px;
            position: relative;

            &.success {
                color: $secondDarkColor;
                background: $secondColor;
            }

            &.error {
                color: $blackColor;
                background: $dangerColor;
            }

            &.warning {
                color: $warningColor;
                background: $whiteColor;
            }

            &.info {
                color: $consultedColor;
                background: $whiteColor;
            }
        }

        .flash__close-button {
            box-sizing: border-box;
            position: absolute;
            top: 0;
            right: 0;
            color: inherit;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: transparent;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            line-height: 10px;
            margin: 0;
            padding: 8px;
        }
    }
</style>
