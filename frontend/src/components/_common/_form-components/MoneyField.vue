<template>
    <div class="input-holder money-input-holder">
        <span v-if="currency" class="currency">{{ currency }}</span>
        <input
                @input="onInput"
                type="number"
                step="1"
                class="float-label"
                :id="id"
                v-model="lazyValue"
                :disabled="disabled"
                :style="css"
                @focusin="onFocus"
                @focusout="onBlur"/>
        <label :for="id" :class="{ active: isActive }">{{ label }}</label>
    </div>
</template>

<script>
    export default {
        name: 'money-field',
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
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
        computed: {
            id() {
                return `money-input-${this._uid}`;
            },
            isActive() {
                return this.lazyValue != null || this.active;
            },
        },
        watch: {
            value: {
                handler(value) {
                    this.lazyValue = value;
                },
                immediate: true,
            },
        },
        methods: {
            onInput(event) {
                const value = parseInt(event.target.value);
                if (isNaN(value)) {
                    return;
                }

                this.$emit('input', value);
            },
            onFocus() {
                this.active = true;
                this.$emit('focus');
            },
            onBlur() {
                this.active = false;
                this.$emit('blur');
            },
        },
        data() {
            return {
                lazyValue: this.value,
                active: false,
            };
        },
    };
</script>
<style scoped lang="scss">
    @import '~theme/variables';

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
