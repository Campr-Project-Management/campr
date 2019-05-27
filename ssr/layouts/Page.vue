<template>
    <table class="page">
        <thead>
        <tr class="header">
            <td class="text-left">
                <img :src="leftLogoUrl" v-if="leftLogoUrl" class="left-logo" alt="logo"/>
            </td>
            <td class="text-right">
                <img :src="rightLogoUrl" v-if="rightLogoUrl" class="right-logo" alt="logo"/>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr class="body">
            <td colspan="2">
                <slot></slot>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: 'Page',
        props: ['project', 'team'],
        computed: {
            leftLogoUrl() {
                let logoUrl = this.defaultLogoUrl;
                if (this.team && this.team.logoUrl) {
                    logoUrl = this.team.logoUrl;
                }

                return logoUrl;
            },
            rightLogoUrl() {
                if (!this.project || !this.project.logoUrl) {
                    return null;
                }

                return this.project.logoUrl;
            },
        },
        data() {
            return {
                defaultLogoUrl: require('../../frontend/src/assets/logo.png'),
            };
        },
    };
</script>

<style lang="scss" scoped>
    .page {
        width: 100%;

        .header {
            td {
                padding: 5mm;

                .left-logo {
                    width: 140px;
                }

                .right-logo {
                    width: 140px;
                }
            }
        }
        .body {
           td {
               padding: 5mm;
           }
        }
    }
</style>
