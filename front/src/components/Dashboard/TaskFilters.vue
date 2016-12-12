<template>
  <div class="filters">
    <span class="title">Filter By</span>
    <dropdown title="Customer"></dropdown>
    <dropdown title="Programme"></dropdown>
    <dropdown title="Status"></dropdown>
    <div class="separator"></div>
  </div>
</template>

<script>
import Dropdown from '../_common/Dropdown'

export default {
  components: {
    Dropdown
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables.scss';

  .dropdown, .separator {
    margin-left: 20px;
  }

  .filters {
    display: flex;
    align-items: center;

    .title {
      text-transform: uppercase;
    }

    .separator {
      width: 1px;
      height: 40px;
      background: $darkColor;
    }
  }
</style>
