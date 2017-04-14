<template>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ currentOption ? currentOption.label : title }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="option in options"><a href="javascript:void(0)" v-on:click="updateValue(option)">{{ option.label }}</a></li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['title', 'options', 'currentOption'],
    methods: {
        updateValue: function(value) {
            this.$emit('input', value);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables.scss';
    @import '../../../css/_mixins.scss';

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 100%;
        text-transform: uppercase;
        height: 40px;
        font-size: 11px;
        line-height: 43px;
        letter-spacing: 0.1em;
        border-radius: 1px;
        text-align: left;
        padding: 0 35px 0 20px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        @include transition(all, 0.2s, ease-in);

        @media screen and (max-width: 1440px) {
            width: 120px;
        }

        .caret {
            right: 20px;
            top: 18px;
            position: absolute;
        }

        &:focus {
            background: $middleColor;
            color: $lighterColor;
            outline: 0;
        }
        
    }

    .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
        background: $middleColor;
        color: $lighterColor;
    }
</style>
