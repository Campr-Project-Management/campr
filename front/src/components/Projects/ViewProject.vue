<template>
    <div>
        <div class="page-section">
            <div class="header">
                <h1>{{ project.title }}</h1>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    methods: mapActions(['getProjectById']),
    created() {
        this.getProjectById(this.$route.params.id);
    },
    computed: mapGetters({
        project: 'project',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
