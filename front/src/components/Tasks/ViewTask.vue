<template>
    <div>
        <div class="page-section">
            <div class="header">
                <h1>{{ task.title }}</h1>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    methods: mapActions(['getTaskById']),
    created() {
        this.getTaskById(this.$route.params.id);
    },
    computed: mapGetters({
        task: 'task',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
