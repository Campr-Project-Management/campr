<template>
    <a href="javascript:void(0)" :title="!isFavorite ? 'Add to Favorites' : 'Remove from Favorites'" @click="toggleProjectFavorite()">
        <svg class="icon star" v-bind:class="{ active: isFavorite }" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          width="16px" height="16px" viewBox="0 0 16 15.121" enable-background="new 0 0 16 15.121" xml:space="preserve">
            <polygon points="16,5.773 10.469,4.974 8,0 5.53,4.974 0,5.773 4.001,9.65 3.058,15.121 8,12.538 12.941,15.12
              11.998,9.65 "/>
        </svg>
    </a>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    props: ['item'],
    methods: {
        ...mapActions(['toggleFavorite']),
        toggleProjectFavorite() {
            this.toggleFavorite({
                project: this.item,
                favorite: !this.isFavorite,
            });
        },
    },
    computed: {
        ...mapGetters({
            localUser: 'localUser',
        }),
        isFavorite() {
            return this.item.userFavorites.indexOf(this.localUser.id) !== -1;
        },
    },
};
</script>

<style scoped lang="scss">
  @import '../../../css/_common.scss';
</style>
