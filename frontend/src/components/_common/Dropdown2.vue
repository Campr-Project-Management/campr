<template>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ activeTitle }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="option in options"><a href="javascript:void(0)" v-on:click="customTitle = option.label, selectedValue(option.key)">{{ option.label }}</a></li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['title', 'filter', 'options', 'item', 'selectedValue'],
    data: function() {
        return {
            customTitle: null,
        };
    },
    methods: {
        resetCustomTitle: function() {
            this.customTitle = null;
        },
    },
    computed: {
        activeTitle: function() {
            return this.customTitle ? this.customTitle : this.title;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .dropdown {
        .dropdown-menu {
            position: absolute;

            ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
        }
    }

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 200px;
        text-transform: uppercase;
        height: 40px;
        padding: 0 35px 0 20px;
        font-size: 11px;
        line-height: 43px;
        letter-spacing: 0.1em;
        text-align: left;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        @include border-radius(0);

        .caret {
            right: 20px;
            top: 18px;
            position: absolute;
        }

        &:focus {
            outline: 0;
        }

        &:hover,
        &:focus {
            background: $middleColor;
            border-color: $darkColor;
        }
    }

    .btn-primary.active, .btn-primary:active,
    .open > .dropdown-toggle.btn-primary {
        background-color: $middleColor;
        border-color: $darkColor;

        &:hover,
        &:focus {
            background-color: $middleColor;
            border-color: $darkColor;
        }
    }
</style>
