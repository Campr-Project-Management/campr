<template>
  <div>
    <navigation></navigation>
    <recent-projects></recent-projects>
  </div>
</template>

<script>
import Navigation from '../Navigation'
import RecentProjects from './RecentProjects'

export default {
  components: {
    Navigation,
    RecentProjects
  },
  data () {
    return {
      title: 'CAMPR'
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables.scss';
</style>
