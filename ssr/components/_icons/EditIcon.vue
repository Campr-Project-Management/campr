<template>
    <div class="edit-icon icon">
        <svg version="1.1" v-bind:class="fill" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
            <path d="M15.5,1.9l-1.4-1.4c-0.7-0.7-1.9-0.7-2.6,0L11,1c0,0,0,0,0,0s0,0,0,0l-0.9,0.9c0,0,0,0,0,0s0,0,0,0l-8.8,8.8c0,0,0,0,0,0
                s0,0,0,0l-0.2,0.2C1.1,10.9,1,11,1,11.1l-1,4.3c0,0.2,0,0.3,0.1,0.5C0.2,15.9,0.4,16,0.5,16c0,0,0.1,0,0.1,0l4.3-1
                c0.1,0,0.2-0.1,0.2-0.1l0.2-0.2c0,0,0,0,0,0s0,0,0,0L14,5.9c0,0,0,0,0,0c0,0,0,0,0,0L15,5c0,0,0,0,0,0s0,0,0,0l0.5-0.5
                C16.2,3.8,16.2,2.6,15.5,1.9z M13.4,1.2l1.4,1.4c0.3,0.3,0.3,0.8,0,1.2l-0.1,0.1l-2.6-2.6l0.1-0.1C12.5,0.9,13,0.9,13.4,1.2z
                 M13.7,4.9l-2.6-2.6l0.2-0.2l2.6,2.6L13.7,4.9z M4.9,13.6L2.4,11L10.4,3L13,5.6L4.9,13.6z M1.8,11.9l2.2,2.2l-2.9,0.7L1.8,11.9z"/>
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
