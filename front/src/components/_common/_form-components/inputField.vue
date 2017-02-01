<template>
    <div class="input-holder">
        <input v-if="type == 'text'" type="text" class="float-label" :id="'input' + _uid" :value="content">
        <textarea v-if="type == 'textarea'" class="float-label" :id="'input' + _uid">{{ content }}</textarea>
        <label v-bind:class="{ 'active': content }">{{ label }}</label>
    </div>
</template>

<script>
export default {
    props: ['type', 'label', 'content'],
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
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_common.scss';

    textarea {
        min-height: 160px;
        margin-bottom: 25px;
    }
</style>
