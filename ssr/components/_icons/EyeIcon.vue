<template>
    <router-link :to="link" title="View Project">
        <svg class="icon eye" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          width="16px" height="9.334px" viewBox="0 0 16 9.334" enable-background="new 0 0 16 9.334" xml:space="preserve">
            <path fill="#636EA0" d="M0.073,4.874c0.144,0.181,3.582,4.46,7.928,4.46c4.345,0,7.782-4.279,7.927-4.46
              c0.096-0.122,0.096-0.293,0-0.414C15.783,4.276,12.346,0,8.001,0C3.655,0,0.217,4.276,0.073,4.46
              C-0.024,4.581-0.024,4.752,0.073,4.874 M8.001,1.667c1.654,0,3,1.345,3,3c0,1.654-1.346,3-3,3c-1.654,0-3-1.346-3-3
              C5.001,3.012,6.347,1.667,8.001,1.667"/>
            <path fill="#636EA0" d="M8.001,7c1.286,0,2.333-1.047,2.333-2.333c0-1.287-1.047-2.334-2.333-2.334
              c-1.287,0-2.333,1.047-2.333,2.334C5.668,5.953,6.714,7,8.001,7"/>
        </svg>
    </router-link>
</template>

<script>
export default {
    props: ['link'],
};
</script>

<style scoped lang="scss">
  @import '../../../frontend/src/css/_common.scss';

  .eye {
    margin-right: 5px;
    height: 16px;
  }
</style>
