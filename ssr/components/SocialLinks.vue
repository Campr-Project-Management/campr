<template>
    <div class="social-links flex flex-center" v-bind:class="align">
        <a :href="facebook" target="_blank" v-show="facebook">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M10.4,8.1H9c0,2,0,4.7,0,4.7H7 c0,0,0-2.6,0-4.7H5.7V6.7H7V6c0-0.7,0.2-1.9,1.8-1.9l1.6,0v1.5c0,0-0.8,0-0.9,0C9.2,5.6,9,5.7,9,6.1v0.7h1.6L10.4,8.1z"/>
            </svg>
        </a>
        <a :href="twitter" target="_blank" v-show="twitter">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                <path class="st0" d="M8,0.2c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.2,8,0.2z M11.5,6.5c0,0.1,0,0.2,0,0.2 c0,2.3-1.8,5-5,5c-1,0-1.9-0.3-2.7-0.8c0.9,0.1,1.8-0.1,2.6-0.7c-0.8,0-1.4-0.5-1.6-1.2c0.3,0,0.5,0,0.8,0C4.8,8.8,4.2,8,4.2,7.2 c0,0,0,0,0,0C4.4,7.3,4.7,7.4,5,7.4C4.5,7.1,4.2,6.5,4.2,5.9c0-0.3,0.1-0.6,0.2-0.9C5.3,6.1,6.6,6.8,8,6.9c0-0.1,0-0.3,0-0.4 c0-1,0.8-1.8,1.8-1.8c0.5,0,1,0.2,1.3,0.6c0.4-0.1,1.3-0.2,1.3-0.2C12.1,5.5,11.9,6.2,11.5,6.5z"/>
            </svg>
        </a>
        <a :href="linkedin" target="_blank" v-show="linkedin">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M6.3,11.4H5V6.7h1.3V11.4z M5.7,5.4C5.3,5.4,5,5.1,5,4.7c0-0.4,0.3-0.7,0.7-0.7s0.7,0.3,0.7,0.7C6.3,5.1,6,5.4,5.7,5.4z M12.3,11.4h-2V9.1 c0-0.2-0.1-0.3-0.3-0.3c-0.2,0-0.3,0.1-0.3,0.3v2.3h-2c0,0,0-4.3,0-4.7h2v0.6c0,0,0.3-0.5,1.1-0.5c1,0,1.5,0.7,1.5,2.2V11.4z"/>
            </svg>
        </a>
        <a :href="gplus" target="_blank" v-show="gplus">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                <g>
                    <path class="st0" d="M8.4,6.1c0-0.4-0.2-0.9-0.5-1.2C7.6,4.6,7.1,4.4,6.7,4.4c-0.4,0-0.8,0.2-1.1,0.4 C5.3,5.2,5.1,5.6,5.1,6c0,0.4,0.2,0.9,0.5,1.2C6.3,7.8,7.4,7.9,8,7.3C8.3,7,8.4,6.6,8.4,6.1z"/>
                    <path class="st0" d="M6.8,9.9c-1.4,0-2.7,0.6-2.7,1.4c0,0.8,1.2,1.4,2.7,1.4c1.4,0,2.7-0.6,2.7-1.4 C9.4,10.5,8.2,9.9,6.8,9.9z"/>
                    <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M6.8,13.3 c-1.9,0-3.3-0.9-3.3-2.1c0-1.2,1.5-2.1,3.3-2.1c0.6,0,1.1,0.1,1.5,0.2C7.9,9.2,7.5,8.9,7.4,8.3c-0.2,0-0.3,0.1-0.5,0.1 c-0.6,0-1.3-0.3-1.7-0.7C4.7,7.2,4.5,6.6,4.5,6c0-0.6,0.2-1.2,0.6-1.6c0.4-0.4,1-0.6,1.6-0.6v0h2.6v0.7h-1c0,0,0.1,0,0.1,0.1 c0.4,0.4,0.7,1,0.7,1.6c0,0.6-0.2,1.2-0.6,1.6C8.4,7.9,8.2,8,8.1,8C8,8.4,8.2,8.6,8.7,8.9c0.6,0.4,1.4,0.9,1.4,2.4 C10.1,12.4,8.6,13.3,6.8,13.3z M13.3,7.1H12v1.3h-0.7V7.1H10V6.4h1.3V5.1H12v1.3h1.3V7.1z"/>
                </g>
            </svg>
        </a>
        <a :href="'mailto:'+email" v-show="email">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16.4">
                <path class="st0" d="M8,0.4c-4.4,0-8,3.6-8,8s3.6,8,8,8s8-3.6,8-8S12.4,0.4,8,0.4z M3.7,7.4l2.9,1.5l-2.9,1.7V7.4z M12.3,12.1H3.7v-0.7l3.6-2.1L8,9.6l0.8-0.4l3.6,2.1V12.1z M12.3,10.6L9.5,8.9l2.9-1.5V10.6z M12.3,5.9L8,8.1L3.7,5.9V4.7h8.7V5.9z"
                  />
            </svg>
        </a>
        <a v-show="phone" class="phone">
            <svg version="1.1" :width="size" :height="size" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20.5 20.8">
            <path class="st0" d="M10.3,0.5c-5.5,0-10,4.5-10,10c0,5.5,4.5,10,10,10s10-4.5,10-10C20.3,5,15.9,0.5,10.3,0.5z M15.9,15.2l-0.7,0.7c-0.7,0.7-1.7,0.8-2.5,0.3c-3.2-2.1-6-4.9-8.1-8.1C4.1,7.3,4.2,6.3,4.9,5.6l0.7-0.7c0.5-0.5,1.4-0.5,1.9,0 l1.3,1.3c0.5,0.5,0.5,1.4,0,1.9L8.5,8.5c1.1,1.4,2.4,2.7,3.8,3.8l0.4-0.4c0.5-0.5,1.4-0.5,1.9,0l1.3,1.3 C16.5,13.8,16.5,14.7,15.9,15.2z"/>
            </svg>
            <div class="link-tooltip">
                {{ phone }}
                <span class="caret"></span>
            </div>
        </a>
    </div>
</template>

<script>
export default {
    props: ['facebook', 'twitter', 'linkedin', 'gplus', 'email', 'phone', 'size', 'align'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../frontend/src/css/_variables';

    .st0 {
        fill: $secondColor;
    }

    .social-links {
        margin-top: 15px;

        a {
            margin: 0 3px;
        }

        &.left {
            justify-content: flex-start;

            a:first-child {
                margin-left: 0;
            }
        }
    }

    .phone {
        position: relative;
        display: block;

        &:hover .link-tooltip {
            display: block;
        }

    }

    .link-tooltip {
        display: none;
        background: $secondColor;
        color: $darkColor;
        position: absolute;
        padding: 5px 15px;
        text-align: center;
        border-radius: 5px;
        left: 50%;
        margin-left: -65px;
        top: -30px;
        min-width: 130px;

        .caret {
            border-top: 5px solid $secondColor;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            width: 0;
            height: 0;
            bottom: -5px;
            position: absolute;
            left: 60px;
            display: block;
        }
    }
</style>
