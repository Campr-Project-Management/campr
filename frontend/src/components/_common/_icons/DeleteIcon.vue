<template>
    <div class="delete-icon icon">
        <svg version="1.1" v-bind:class="fill" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32">
            <path  class="st0" d="M30.7,4h-9.3V0.7C21.3,0.3,21,0,20.7,0H10C9.6,0,9.3,0.3,9.3,0.7V4h-8C1,4,0.7,4.3,0.7,4.7 C0.7,5,1,5.3,1.3,5.3H4v26C4,31.7,4.3,32,4.7,32H26c0.4,0,0.7-0.3,0.7-0.7v-26h4c0.4,0,0.7-0.3,0.7-0.7C31.3,4.3,31,4,30.7,4z M10.7,24.7c0,0.4-0.3,0.7-0.7,0.7S9.3,25,9.3,24.7V10c0-0.4,0.3-0.7,0.7-0.7s0.7,0.3,0.7,0.7V24.7z M10.7,1.3H20V4h-9.3V1.3z M16,24.7c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V10c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7V24.7z M21.3,24.7 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V10c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7V24.7z"/>
        </svg>
    </div>
</template>

<script>
export default {
    props: ['fill'],
};
</script>

<style scoped lang="scss">
    @import '../../../css/_variables.scss';

    .st0{
        fill: $dangerColor;
    }
</style>

