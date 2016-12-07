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
html, body {
  height: 100%;
  margin: 0;

  .tablet {
    display: none;
  }

  ul {
    list-style-type: none;
    padding: 0;
    text-align: left;
  }
  a {
    color: inherit;
    text-decoration: none;

    &:hover, &:active, &:focus {
      text-decoration: none;
      color: inherit;
    }
  }
  #app {
    font-family: 'Poppins', sans-serif;
    text-align: center;
    color: #D8DAE5;
    background: #232D4B;
    height: 100%;
    padding-left: 210px;
  }
}
</style>
