<template>
    <div class="input-holder">
        <template v-if="type === 'textarea'">
            <textarea
                    @input="onInput($event.target.value)"
                    class="float-label"
                    :id="'input' + _uid"
                    :value="value"
                    :disabled="disabled"
                    :style="css"
                    @focusin="onFocus"
                    @focusout="onBlur">
            </textarea>
        </template>
        <template v-else>
            <input
                    @input="onInput($event.target.value)"
                    :type="type"
                    class="float-label"
                    :id="'input' + _uid"
                    :value="value"
                    :disabled="disabled"
                    :style="css"
                    @focusin="onFocus"
                    @focusout="onBlur"/>
        </template>
        <label v-bind:class="{ 'active': value }">{{ label }}</label>
    </div>
</template>

<script>
    import $ from 'jquery';

    export default {
        name: 'input-field',
        props: {
            value: {
                required: false,
            },
            type: {
                type: String,
                required: false,
                default: 'text',
            },
            label: {
                type: String,
                required: false,
                default: '',
            },
            content: {
                required: false,
                default: '',
            },
            css: {
                required: false,
            },
            disabled: {
                type: Boolean,
                default: false,
            },
        },
        mounted() {
            const $this = $('#input' + this._uid);

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

            $('select').next().removeClass();
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

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    textarea {
        min-height: 160px;
        margin-bottom: 0;
    }
    label {
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .input-holder {
        margin-top: 2em;
    }
</style>
