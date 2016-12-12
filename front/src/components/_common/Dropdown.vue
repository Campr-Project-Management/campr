<template>
  <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ title }}
      <span class="caret"></span></button>
      <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="#">Filter 1</a></li>
      </ul>
  </div>
</template>

<script>
export default {
  props: ['title'],
  data () {
    return {
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables.scss';

  .btn-primary {
    background: $darkColor;
    color: $lightColor;
    border: none;
    width: 200px;
    text-transform: uppercase;
    height: 40px;
    font-size: 10px;
    border-radius: 1px;
  }
</style>
