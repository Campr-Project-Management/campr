<template>
  <div class="ro-list">
    <div class="ro-list-item" v-for="item in listData">
      <div class="avatar" style="background-image:url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg);" v-tooltip.top="item.responsible"></div>
      <a href="#" title="Plant based dietary Program">{{item.title}}</a>
      <p>{{ translateText('message.potential_savings') }}: <b>{{item.potential_savings}}</b></p>
      <p>{{ translateText('message.potential_time_savings') }}: <b>{{item.potential_time_savings}}</b></p>
      <p>{{ translateText('message.priority') }}: <b>{{item.priority}}</b></p>
      <p>{{ translateText('message.strategy') }}: <b>{{item.strategy}}</b></p>
      <p>{{ translateText('message.status') }}: <b>{{item.status}}</b></p>
    </div>
  </div>
</template>

<script>
export default {
    props: ['listData'],
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
.ro-list {
        width: 35%;
        float: left;
    }
.ro-list-item {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .avatar {
            height: 20px;
            width: 20px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            right: 0;
            top: 0;
            @include border-radius(50%);
        }

        a {
            font-size: 1.166em;
            color: $secondColor;
            font-weight: 600;
            padding-right: 25px;
            display: block;
        }

        p {
            font-size: 0.833em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }
</style>