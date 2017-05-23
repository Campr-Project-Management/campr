<template>
  <div class="ro-grid">
    <div class="ro-grid-header vertical-axis-header">
      <div class="big-header">{{ translateText('message.impact') }}</div>
      <div class="small-headers clearfix">
        <div class="small-header">{{ translateText('message.very_low') }}</div>
        <div class="small-header">{{ translateText('message.low') }}</div>
        <div class="small-header">{{ translateText('message.high') }}</div>
        <div class="small-header">{{ translateText('message.very_high') }}</div>
      </div>
    </div>
    <div class="ro-grid-items clearfix" v-for="row in gridData">
        <div v-for="item in row">
      <div class="ro-grid-item medium" v-bind:class="[item.type]"><span>{{item.number? item.number : ''}}</span></div>
        </div>
    </div>
    <div class="ro-grid-header horizontal-axis-header">
      <div class="small-headers clearfix">
        <div class="small-header">{{ translateText('message.very_low') }}</div>
        <div class="small-header">{{ translateText('message.low') }}</div>
        <div class="small-header">{{ translateText('message.high') }}</div>
        <div class="small-header">{{ translateText('message.very_high') }}</div>
      </div>
      <div class="big-header">{{ translateText('message.probability') }}</div>
    </div>
  </div>
</template>

<script>
export default {
    props: ['gridData'],
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_mixins';
    @import '../../../css/_variables';
    .ro-grid {
            width: 65%;
            padding: 0 30px 67px 67px;
            float: left;
            position: relative;
        }

    .ro-grid-item {
            width: 24%;
            height: 0;
            padding-bottom: 24%;
            @include border-radius(50%);
            margin: 0.5%;
            float: left;
            font-size: 3em;
            font-weight: bold;
            color: $mainColor;
            text-align: center;
            white-space: nowrap;
            position: relative;
            cursor: pointer;
            @include transition(background-color, 0.2s, ease);

            span {
                display: inline-block;
                vertical-align: middle;
                width: 100%;
                height: 30px;
                line-height: 35px;
                position: absolute;
                top: 50%;
                left: 0;
                margin-top: -15px;
            }

            &.medium {
                background-color: $warningColor;
            }

            &.high {
                background-color: $dangerColor;
            }

            &.very-high {
                background-color: $dangerDarkColor;
            }

            &.low {
                background-color: $secondColor;
            }

            &.very-low {
                background-color: $secondDarkColor;
            }

            &.active {
                background-color: $lightColor;
            }

            &:hover{
                background-color: $lightColor;
            }
        }
        .ro-grid-header {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-align: center;
            position: absolute;
            width: 100%;
            left: 0;

            .big-header {
                color: $lightColor;
                font-size: 0.875em;
            }

            .small-header {
                width: 25%;
                font-size: 0.75em;
                float: left;
                white-space: nowrap;
            }

            &.vertical-axis-header {
                @include rotate(-90);
                @include transform-origin(left top);
                top: calc(100% - 67px);
                width: calc(100% - 97px);

                .big-header {
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                    border-bottom: 1px solid $darkerColor;
                }
            }

            &.horizontal-axis-header {
                bottom: 0;
                padding: 0 30px 0 67px;

                .big-header {
                    padding-top: 10px;
                    margin-top: 10px;
                    border-top: 1px solid $darkerColor;
                }
            }
        }
</style>
