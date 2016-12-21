<template>
    <div id="app">
        <sidebar></sidebar>
        <div class="page">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import Sidebar from './components/_layout/Sidebar';

export default {
    name: 'app',
    components: {
        Sidebar,
    },
};
</script>

<style lang="scss">
@import 'css/_variables.scss';

html, body {
  margin: 0;
  min-height: 100vh;
}

body {
  font-size: 12px;
}

.tablet {
  display: none;
}

ul {
  list-style-type: none;
  padding: 0;
  text-align: left;
}

p {
  margin: 0;
}

a {
  color: inherit;
  text-decoration: none;

  &:hover, &:active, &:focus {
    text-decoration: none;
    color: inherit;
  }
}

.notification-balloon {
  display: block;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: $secondColor;
  color: $mainColor;
  position: absolute;
  text-align: center;
  line-height: 20px;

  @media screen and (max-width: 768px) {
    width: 16px;
    height: 16px;
    line-height: 16px;
  }
}

.dropdown-menu {
  color: $lightColor;
  background: $darkColor;

  li a {
    color: inherit;
  }

  a:hover {
    color: $lighterColor;
    background: $mainColor;
  }
}

.btn-rounded {
  background: $middleColor;
  border-radius: 10px;
  text-transform: uppercase;
  font-size: 8px;
  text-align: center;
}

.new-box {
  height: 189px;
  width: 394px;
  border: 1px dashed #5FC3A5;
  text-align: center;
  line-height: 189px;
  text-transform: uppercase;
  display: block;
  color: $secondColor;
  margin: 0 12.5px 30px;
}

.second-col-bg {
  background: $secondColor !important;
}

.warning-col-bg {
  background: $warningColor !important;
}

.danger-col-bg {
  background: $dangerColor !important;
}

#app {
  font-family: 'Poppins', sans-serif;
  color: #D8DAE5;
  background: $darkColor;
  height: 100%;
  padding: 0 0 0 210px;
  min-height: 100vh;

  @media screen and (max-width: 768px) {
    padding: 0 0 0 75px;
  }

  .page {
    padding: 0 20px;
    background: #232D4B;
    min-height: 100vh;
  }
}
</style>
