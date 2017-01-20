<template>
    <div class="calendar-icon">
        <svg version="1.1" id="Layer_1" width="30px" height="30px" fill="transparent" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
               viewBox="0 0 26.3 26.5" style="enable-background:new 0 0 26.3 26.5;" xml:space="preserve">
            <g id="XMLID_149_">
                <rect id="XMLID_152_" x="-327.2" y="67.2" v-bind:class="fill" width="395" height="40"/>
                <text id="XMLID_150_" transform="matrix(1 0 0 1 -331.875 17.6572)" style="fill:#8894C5; font-family:'Poppins-Regular'; font-size:12px; letter-spacing:1;">10 / 02 / 2016</text>
                <g id="XMLID_279_">
                    <g>
                        <polyline id="XMLID_90_" v-bind:class="stroke" points="6.6,4.8 3.1,4.8 3.1,23 23.1,23 23.1,4.8 19.6,4.8 			"/>
                        <rect id="XMLID_89_" x="6.6" y="3" v-bind:class="stroke" width="2.6" height="3.5"/>
                        <rect id="XMLID_88_" x="17" y="3" v-bind:class="stroke" width="2.6" height="3.5"/>
                        <line id="XMLID_87_" v-bind:class="stroke" x1="9.2" y1="4.8" x2="17" y2="4.8"/>
                        <line id="XMLID_86_" v-bind:class="stroke" x1="3.1" y1="9.1" x2="23.1" y2="9.1"/>
                        <line id="XMLID_85_" v-bind:class="stroke" x1="8.3" y1="10.9" x2="8.3" y2="21.3"/>
                        <line id="XMLID_84_" v-bind:class="stroke" x1="12.7" y1="10.9" x2="12.7" y2="21.3"/>
                        <line id="XMLID_83_" v-bind:class="stroke" x1="17" y1="10.9" x2="17" y2="21.3"/>
                        <line id="XMLID_82_" v-bind:class="stroke" x1="4.9" y1="12.6" x2="21.4" y2="12.6"/>
                        <line id="XMLID_81_" v-bind:class="stroke" x1="4.9" y1="16.1" x2="21.4" y2="16.1"/>
                        <line id="XMLID_80_" v-bind:class="stroke" x1="4.9" y1="19.6" x2="21.4" y2="19.6"/>
                    </g>
                </g>
                <text id="XMLID_424_" transform="matrix(1 0 0 1 -331.875 -11.9525)" style="fill:#8894C5; font-family:'Poppins-Regular'; font-size:8px; letter-spacing:1;">Proposed Start Date</text>
            </g>
        </svg>
    </div>
</template>

<script>
export default {
    props: ['fill', 'stroke'],
};
</script>

<style lang="scss">
  @import '../../../css/_common.scss';
  @import '../../../css/_variables.scss';

  .st1 {
      fill:none;
      stroke-linecap:round;
      stroke-linejoin:round;
      stroke-miterlimit:10;
  }
</style>
