<template>
  <div>
    <div class="projects-header">
      <h1>My Recent Projects</h1>
      <task-filters></task-filters>
    </div>
    <div class="recent-projects">
      <project-box v-for="project in projects"></project-box>
    </div>
  </div>
</template>

<script>
import TaskFilters from './TaskFilters'
import ProjectBox from '../ProjectBox'
import { mapActions, mapGetters } from 'vuex'

export default {
  components: {
    TaskFilters,
    ProjectBox
  },
  methods: mapActions(['getProjects']),
  created () {
    this.getProjects()
  },
  computed: mapGetters({
    projects: 'projects'
  })
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables';

  .projects-header {
    display: flex;
    justify-content: space-between;

    h1 {
      margin: 0;
      text-transform: uppercase;
      font-size: 20px;
      line-height: 80px;
    }
  }

  .recent-projects {
    display: flex;
    flex-flow: row wrap;
    margin: -14px -13px;
  }
</style>
