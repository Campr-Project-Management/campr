<template>
  <aside>
    <a href="/" class="logo"></a>
    <nav>
      <ul>
        <li class="active">
          <router-link :to="{name: 'dashboard'}">
            <span class="default">Dashboard</span>
            <span class="tablet">SVG</span>
            <span class="notifications">1</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{name: 'projects'}">
            <span class="default">Projects</span>
            <span class="tablet">SVG</span>
            <span class="notifications">2</span>
          </router-link>
        </li>
        <li>
          <router-link :to="{name: 'tasks'}">
            <span class="default">Tasks</span>
            <span class="tablet">SVG</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script>
export default {
  name: 'sidebar',
  data () {
    return {
      title: 'CAMPR'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../css/_variables.scss';

  aside {
    height: 100%;
    width: 210px;
    background: $darkColor;
    padding: 10px;
    position: absolute;
    left: 0;
    top: 0;

    @media screen and (max-width: 768px) {
      width: 59px;

      .default {
        display: none;
      }
      .tablet {
        display: block;
      }
    }

    .logo {
      margin: 14px 26px;
      display: block;
      width: 142px;
      height: 36px;
      background: url('../assets/logo.png');

      @media screen and (max-width: 768px) {
        width: 39px;
        margin: 14px 0;
        background: url('../assets/logo-small.png');
      }
    }

    nav {
      margin-top: 46px;
      color: $lightColor;
      font-size: 11px;
      text-transform: uppercase;

      ul li {
        padding: 2px 0;
        position: relative;

        .notifications {
          display: block;
          width: 20px;
          height: 20px;
          border-radius: 50%;
          background: $secondColor;
          color: $mainColor;
          position: absolute;
          right: 5px;
          top: 8px;
          text-align: center;
          line-height: 20px;

          @media screen and (max-width: 768px) {
            width: 16px;
            height: 16px;
            top: -1px;
            right: -9px;
            line-height: 16px;
          }
        }

        a {
          display: block;
          padding: 8px 10px;
          border-radius: 15px;

          &.router-link-active, &:hover {
            color: $lighterColor;
            background: $mainColor;
          }
        }
      }
    }
  }
</style>
