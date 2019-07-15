<template>
    <div class="page">
        <div class="header">
            <div class="logo pull-left" style="text-align: left;">
                <img :src="leftLogoUrl" v-if="leftLogoUrl" alt="logo" />
            </div>
            <div class="logo pull-right" style="text-align: right;">
                <img :src="rightLogoUrl" v-if="rightLogoUrl" alt="logo" />
            </div>
            <div class="text-center" v-if="title && subtitle">
                <h1>{{ title }}</h1>
                <h2>{{ subtitle }}</h2>
            </div>
        </div>
        <slot />
    </div>
</template>

<script>
    export default {
        name: 'Page',
        props: {
            project: {
                type: Object,
            },
            team: {
                type: Object,
            },
            title: {
                type: String,
                default() {
                    return '';
                },
            },
            subtitle: {
                type: String,
                default() {
                    return '';
                },
            },
        },
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

<style lang="scss">
    .gray-table {
        -webkit-print-color-adjust: exact !important;
        margin: 5px 0;
        width: 100%;
        table-layout: fixed;
        border-top: 1px solid #999;
        border-left: 1px solid #999;
        background: #efefef;

        th, td {
            border-bottom: 1px solid #999;
            border-right: 1px solid #999;
            font-size: 9px;
            padding: 3px;
            width: 50%;
        }
    }

    .content-table {
        border: none;
        width: 100%;
        table-layout: fixed;

        th, td {
            font-size: 9px;
            padding: 3px;
            word-break: break-word;
        }

        td {
            border-left: 1px solid #999;
            border-top: 1px solid #999;

            &:nth-child(1) {
                border-left: none;
            }
        }
    }

    .row {
        break-inside: avoid;
    }

    @media print {
        .resources {
            width: 100% !important;
            transform: translate(-255px) scale(0.5);
        }
        .resources-half {
            display: block;
            width: 320px !important;
            transform: translate(-118px, -90px) scale(0.5, 0.5);
        }
    }
</style>

<style lang="scss" scoped>
    @import '../../frontend/src/css/variables';

    .page {
        /deep/ h1, /deep/ .h1, /deep/ h2, /deep/ .h2, /deep/ h3, /deep/ .h3, /deep/ h4, /deep/ .h4 {
            margin-top: 10px;
            margin-bottom: 5px;
        }

        width: 100%;
        font-size: 9px;
        line-height: 12px;

        /deep/ ol, /deep/ ul {
            margin-bottom: 3px;
        }

        .header {
            -webkit-print-color-adjust: exact !important;
            background: $mainColor;
            color: $lighterColor;
            height: 60px;

            /deep/ h1, h2 {
                margin: 0;
                padding: 0;
                line-height: 30px;
            }

            /deep/ h1 {
                font-size: 20px;
            }

            /deep/ h2 {
                font-size: 15px;
            }

            .logo {
                display: block;
                height: 60px;
                line-height: 60px;
                width: 60px;
                margin: 0;
                padding: 0;

                img {
                    max-height: 57px;
                }
            }
        }

        /deep/ h3 {
            font-size: 9px;
            -webkit-print-color-adjust: exact !important;
            background: $mainColor;
            color: $lighterColor;
            padding: 3px;
            clear: both;
        }

        /deep/ h4 {
            font-size: 9px;
            -webkit-print-color-adjust: exact !important;
            background: $mainColor;
            color: $lighterColor;
            padding: 3px;
            clear: both;
        }

        /deep/ .flex-v-center {
            text-align: center;
        }
    }
</style>
