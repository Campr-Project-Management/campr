<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar';

export default {
    name: 'scrollbar',
    extends: VuePerfectScrollbar,
};
</script>

<style lang="scss">
	@import '../../css/_variables';
	@import '../../css/_mixins';

	.customScrollbar.ps{
	    -ms-touch-action:auto;
	    touch-action:auto;
	    overflow:hidden!important;
	    -ms-overflow-style:none
	}
	@supports (-ms-overflow-style:none){
	    .customScrollbar.ps{
	        overflow:auto!important
	    }
	}
	@media (-ms-high-contrast:none),screen and (-ms-high-contrast:active){
	    .customScrollbar.ps{
	        overflow:auto!important
	    }
	}
	.customScrollbar.ps.ps--active-x>.ps__scrollbar-x-rail,
	.customScrollbar.ps.ps--active-y>.ps__scrollbar-y-rail{
	    display:block;
	    background-color:$darkColor;
	}
	.customScrollbar.ps.ps--in-scrolling.ps--x>.ps__scrollbar-x-rail{
	    background-color:$darkColor;
	    opacity:.9
	}
	.customScrollbar.ps.ps--in-scrolling.ps--x>.ps__scrollbar-x-rail>.ps__scrollbar-x{
	    background-color:$middleColor;
	    height:10px
	}
	.customScrollbar.ps.ps--in-scrolling.ps--y>.ps__scrollbar-y-rail{
	    background-color:transparent;
	    opacity:.9
	}
	.customScrollbar.ps.ps--in-scrolling.ps--y>.ps__scrollbar-y-rail>.ps__scrollbar-y{
	    background-color:$middleColor;
	    width:6px
	}
	.customScrollbar.ps>.ps__scrollbar-x-rail{
	    display:none;
	    position:absolute;
	    opacity:0;
	    transition:background-color .2s linear,opacity .2s linear;
	    bottom:0;
	    height:16px;    

	    &:before,
	    &:after {
	    	content: "";
	    	position: absolute;
	    	top: 0;
	    	width: 3px;
	    	height: 100%;
	    	background-color: $darkColor;
	    	z-index: 10;
	    }

	    &:before {
	    	left: 0;
	    }

	    &:after {
	    	right: 0;
	    }
	}
	.customScrollbar.ps>.ps__scrollbar-x-rail>.ps__scrollbar-x{
	    position:absolute;
	    background-color:$middleColor;
	    border-radius:0;
	    transition:background-color .2s linear,height .2s linear,width .2s ease-in-out,border-radius .2s ease-in-out;
	    bottom:3px;
	    height:10px
	}
	.customScrollbar.ps>.ps__scrollbar-x-rail:active>.ps__scrollbar-x,.customScrollbar.ps>.ps__scrollbar-x-rail:hover>.ps__scrollbar-x{
	    height:10px
	}
	.customScrollbar.ps>.ps__scrollbar-y-rail{
	    display:none;
	    position:absolute;
	    opacity:0;
	    transition:background-color .2s linear,opacity .2s linear;
	    right:0;
	    width:10px;

	    &:before,
	    &:after {
	    	content: "";
	    	position: absolute;
	    	left: 0;
	    	width: 100%;
	    	height: 3px;
	    	background-color: $darkColor;
	    	z-index: 10;
	    }

	    &:before {
	    	top: 0;
	    }

	    &:after {
	    	bottom: 0;
	    }
	}
	.customScrollbar.ps>.ps__scrollbar-y-rail>.ps__scrollbar-y{
	    position:absolute;
	    background-color:$middleColor;
	    border-radius:0;
	    transition:background-color .2s linear,height .2s linear,width .2s ease-in-out,border-radius .2s ease-in-out;
	    right:2px;
	    width:6px
	}
	.customScrollbar.ps>.ps__scrollbar-y-rail:active>.ps__scrollbar-y,.customScrollbar.ps>.ps__scrollbar-y-rail:hover>.ps__scrollbar-y{
	    width:6px
	}
	.customScrollbar.ps:hover.ps--in-scrolling.ps--x>.ps__scrollbar-x-rail{
	    background-color:$darkColor;
	    opacity:.9
	}
	.customScrollbar.ps:hover.ps--in-scrolling.ps--x>.ps__scrollbar-x-rail>.ps__scrollbar-x{
	    background-color:$middleColor;
	    height:10px
	}
	.customScrollbar.ps:hover.ps--in-scrolling.ps--y>.ps__scrollbar-y-rail{
	    background-color:transparent;
	    opacity:.9
	}
	.customScrollbar.ps:hover.ps--in-scrolling.ps--y>.ps__scrollbar-y-rail>.ps__scrollbar-y{
	    background-color:$middleColor;
	    width:6px
	}
	.customScrollbar.ps:hover>.ps__scrollbar-x-rail,.customScrollbar.ps:hover>.ps__scrollbar-y-rail{
	    opacity:.6
	}
	.customScrollbar.ps:hover>.ps__scrollbar-x-rail:hover{
	    background-color:$darkColor;
	    opacity:.9
	}
	.customScrollbar.ps:hover>.ps__scrollbar-x-rail:hover>.ps__scrollbar-x{
	    background-color:$middleColor
	}
	.customScrollbar.ps:hover>.ps__scrollbar-y-rail:hover{
	    background-color:transparent;
	    opacity:.9
	}
	.customScrollbar.ps:hover>.ps__scrollbar-y-rail:hover>.ps__scrollbar-y{
	    background-color:$middleColor
	}
	.ps-container{
	    position:relative
	}
</style>
