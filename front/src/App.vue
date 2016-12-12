<template>
  <div id="app">
    <sidebar></sidebar>
    <router-view></router-view>
  </div>
</template>

<script>
import Sidebar from './components/Sidebar'

export default {
  name: 'app',
  components: {
    Sidebar
  }
}
</script>

<style lang="scss">
@import 'css/_variables.scss';

html, body {
  height: 100%;
  margin: 0;
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

#app {
  font-family: 'Poppins', sans-serif;
  color: #D8DAE5;
  background: #232D4B;
  height: 100%;
  padding: 0 20px 0 230px;

  @media screen and (max-width: 768px) {
    padding: 0 20px 0 75px;
  }
}

</style>
