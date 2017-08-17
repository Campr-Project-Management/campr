<template>
    <div class="input-holder">
        <input v-on:input="updateValue($event.target.value)" v-if="type == 'text'" type="text" class="float-label" :id="'input' + _uid" :value="content" :disabled="disabled" :style="css">
        <textarea v-on:input="updateValue($event.target.value)" v-if="type == 'textarea'" class="float-label" :id="'input' + _uid" :value="content" :disabled="disabled"></textarea>
        <label v-bind:class="{ 'active': content }">{{ label }}</label>
    </div>
</template>

<script>
export default {
    props: ['value', 'type', 'label', 'content', 'css', 'disabled'],
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

        if($this.disabled = true) {
            $this.next().addClass('active');
        };

        if($this.val() === '' || $this.val() === 'blank') {
            $this.next().removeClass();
        }

        $this.blur(function() {
            if($this.val() === '' || $this.val() === 'blank') {
                $this.next().removeClass();
            }
        });

        if(textValue!='') {
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
