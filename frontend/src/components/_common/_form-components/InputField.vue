<template>
    <div class="input-holder">
        <template v-if="type === 'textarea'">
            <textarea
                    v-on:input="updateValue($event.target.value)"
                    class="float-label"
                    :id="'input' + _uid"
                    :value="content"
                    :disabled="disabled">
            </textarea>
        </template>
        <template v-else>
            <input
                    v-on:input="updateValue($event.target.value)"
                    :type="type"
                    class="float-label"
                    :id="'input' + _uid"
                    :value="content"
                    :disabled="disabled"
                    :style="css"/>
        </template>
        <label v-bind:class="{ 'active': content }">{{ label }}</label>
    </div>
</template>

<script>
    export default {
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
            const $this = window.$('#input' + this._uid);

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
            updateValue: function(value) {
                this.$emit('input', value);
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
</style>
