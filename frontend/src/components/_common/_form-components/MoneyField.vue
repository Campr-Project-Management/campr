<template>
    <div class="input-holder money-input-holder">
        <span class="currency" v-if="currency">{{ currency }}</span>
        <input
                @input="onInput($event.target.value)"
                type="number"
                step="1"
                class="float-label"
                :id="'money-input' + _uid"
                :value="value"
                :disabled="disabled"
                :style="css"
                @focusin="onFocus"
                @focusout="onBlur"/>
        <label v-bind:class="{ 'active': value }">{{ label }}</label>
    </div>
</template>

<script>
    export default {
        name: 'money-field',
        props: {
            value: {
                required: false,
            },
            currency: {
                required: true,
            },
            label: {
                type: String,
                required: false,
                default: '',
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        mounted() {
            const $this = $('#money-input' + this._uid);

            let textValue = $this.val();
            let $label = $this.next();

            $label.on('click', function() {
                $(this).prev().focus();
            });

            $this.focus(function() {
                $this.next().addClass('active');
            });

            if ($this.disabled === true) {
                $this.next().addClass('active');
            }

            if ($this.val() === '' || $this.val() === 'blank') {
                $this.next().removeClass();
            }

            $this.blur(function() {
                if ($this.val() === '' || $this.val() === 'blank') {
                    $this.next().removeClass();
                }
            });

            if (textValue !== '') {
                $this.next().addClass('active');
            }
        },
        methods: {
            onInput(value) {
                this.$emit('input', value);
            },
            onFocus() {
                this.$emit('focus');
            },
            onBlur() {
                this.$emit('blur');
            },
        },
    };
</script>
<style scoped lang="scss">
    @import '../../../css/_variables.scss';

    .money-input-holder {
        display: table;

        .currency {
            width: 1%;
            display: table-cell;
            background-color: $lightColor;
            color: $semiDarkColor;
            padding: 0 5px 0 5px;
        }

        input[type=number] {
            display: table-cell;
            margin-bottom: 0;
            text-align: right;
        }

        label {
            left: 40px;
        }
    }
</style>
