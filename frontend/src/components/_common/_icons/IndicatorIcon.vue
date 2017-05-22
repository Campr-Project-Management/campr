<template>
    <div class="indicator-icon icon" v-bind:style="{ left: position + '%' }" v-tooltip.bottom="title + ' <b>' + position + ' %</b>'">
    	<svg version="1.1" v-bind:class="fill" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 10 12">
			<path d="M5,12c2.7,0,4.7-2.2,4.7-5C9.7,3.4,5,0,5,0S0.3,3.4,0.3,7C0.3,9.8,2.3,12,5,12z M5,4.7 c1.4,0,2.6,1.2,2.6,2.6c0,1.4-1.2,2.6-2.6,2.6c-1.4,0-2.6-1.2-2.6-2.6C2.4,5.9,3.6,4.7,5,4.7z"/>
		</svg>
    </div>
</template>

<script>
export default {
    props: ['fill', 'position', 'title'],
};
</script>

<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

	.indicator-icon {
        position: absolute;
        bottom: 0;
        margin-left: -15px;
        width: 15px;
        height: 18px;

        svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 15px;
            height: 18px;
        }
    }
</style>
