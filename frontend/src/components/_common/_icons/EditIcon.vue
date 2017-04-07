<template>
    <div class="edit-icon icon">
        <svg version="1.1" v-bind:class="fill" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32">
            <g>
                <path class="st0" d="M0,31.2c-0.1,0.2,0,0.5,0.2,0.6C0.4,32,0.6,32,0.8,32c0,0,8.9-2.1,8.9-2.1l-7.6-7.6 C2.1,22.3,0,31.2,0,31.2z"/>	
                <rect x="24.6" y="1.3" transform="matrix(-0.7074 0.7069 -0.7069 -0.7074 47.9033 -6.3714)" class="st0" width="1.4" height="10.8"/>	
                <rect x="3.4" y="11.2" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 37.9935 17.5333)" class="st0" width="24" height="10.8"/>
                <path class="st0" d="M30.5,9.1L31,8.6c1.3-1.3,1.3-3.5,0-4.8L28.1,1c-0.6-0.6-1.5-1-2.4-1c-0.9,0-1.8,0.4-2.4,1 l-0.5,0.5L30.5,9.1z"/>
            </g>
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
        fill: $secondColor;
    }
</style>
