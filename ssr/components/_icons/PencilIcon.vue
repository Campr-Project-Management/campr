<template>
    <router-link :to="link.name" title="Edit" v-if="link">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           viewBox="0 3 22.6 26.4" style="enable-background:new 0 0 21.6 27.4;" xml:space="preserve" width="32px" height="25px">
            <g id="XMLID_1202_">
                <path id="XMLID_1025_" class="st0" d="M2.9,21.2c0,0.1,0,0.2,0.1,0.3c0.1,0.1,0.2,0.1,0.3,0.1c0,0,4.4-1,4.5-1.1l-3.8-3.8
                  C3.9,16.7,2.9,21.2,2.9,21.2z"/>

                  <rect id="XMLID_1024_" x="15.2" y="6.2" transform="matrix(-0.7074 0.7069 -0.7069 -0.7074 32.8035 4.3053)" class="st0" width="0.7" height="5.4"/>

                  <rect id="XMLID_923_" x="4.6" y="11.2" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 27.849 16.2564)" class="st0" width="12" height="5.4"/>
                <path id="XMLID_186_" class="st0" d="M18.1,10.1l0.2-0.2c0.7-0.7,0.7-1.7,0-2.4l-1.4-1.4c-0.3-0.3-0.7-0.5-1.2-0.5
                  c-0.5,0-0.9,0.2-1.2,0.5l-0.2,0.2L18.1,10.1z"/>
            </g>
        </svg>
    </router-link>
    <div v-else>
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           viewBox="0 3 22.6 26.4" style="enable-background:new 0 0 21.6 27.4;" xml:space="preserve" width="32px" height="25px">
            <g id="XMLID_1202_">
                <path id="XMLID_1025_" class="st0" d="M2.9,21.2c0,0.1,0,0.2,0.1,0.3c0.1,0.1,0.2,0.1,0.3,0.1c0,0,4.4-1,4.5-1.1l-3.8-3.8
                  C3.9,16.7,2.9,21.2,2.9,21.2z"/>

                  <rect id="XMLID_1024_" x="15.2" y="6.2" transform="matrix(-0.7074 0.7069 -0.7069 -0.7074 32.8035 4.3053)" class="st0" width="0.7" height="5.4"/>

                  <rect id="XMLID_923_" x="4.6" y="11.2" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 27.849 16.2564)" class="st0" width="12" height="5.4"/>
                <path id="XMLID_186_" class="st0" d="M18.1,10.1l0.2-0.2c0.7-0.7,0.7-1.7,0-2.4l-1.4-1.4c-0.3-0.3-0.7-0.5-1.2-0.5
                  c-0.5,0-0.9,0.2-1.2,0.5l-0.2,0.2L18.1,10.1z"/>
            </g>
        </svg>
    </div>
</template>

<script>
export default {
    props: ['link'],
};
</script>

<style scoped lang="scss">
  @import '../../../css/_common.scss';

  .st0 {
    fill:#666FA1;
  }
</style>
