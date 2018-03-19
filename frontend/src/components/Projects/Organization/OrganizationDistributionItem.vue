<template>
  <li>
    <span class="role">
            {{item.name}}
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                <g>
                    <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                    <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                    <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                    <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                </g>
            </svg>
        </span>
    <ul>
        <organization-distribution-item v-for="child in item.children" :item="child"></organization-distribution-item>
    </ul>
  </li>
</template>

<script>

export default {
    props: ['item'],
    name: 'organization-distribution-item',
};

</script>

<style lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';

    ul.roles-hierarchy {
        margin: 0;
        padding: 0;
        list-style: none;

        li {
            span.role {
                display: block;
                background-color: $darkColor;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: $lightColor;
                line-height: 1em;
                padding: 17px 56px 13px 20px;
                position: relative;
                cursor: grab;
                margin-bottom: 20px;

                svg {
                    width: 16px;
                    height: 16px;
                    fill: $lightColor;
                    position: absolute;
                    right: 20px;
                    top: 13px;
                }

                &:active {
                    cursor: grabbing;
                }
            }

            ul {
                li {
                    padding-left: 30px;
                }
            }
        }
    }
</style>
