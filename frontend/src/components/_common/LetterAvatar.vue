<template>
    <img
            class="rounded"
            :src="url"
            :alt="name"/>
</template>

<script>
    import _ from 'lodash';

    export default {
        name: 'letter-avatar',
        props: {
            name: {
                required: true,
            },
            size: {
                type: Number,
                required: false,
                default: 40,
            },
            ratio: {
                type: Number,
                required: false,
                default: window.devicePixelRatio ? window.devicePixelRatio : 1,
            },
        },
        computed: {
            initials() {
                let name = this.name ? String(this.name) : '';
                let parts = _.trim(name).split(' ');
                let initials = [];
                parts.forEach((part) => {
                    initials.push(part.charAt(0));
                });

                let text = initials.slice(0, 2).join('').toUpperCase();

                return text.length === 0 ? '?' : text;
            },
            colorIndex() {
                let code = 72;
                if (this.initials !== '?' && this.initials.length > 0) {
                    code = this.initials.charCodeAt(0);
                }

                code -= 64;

                return code % this.colors.length;
            },
            color() {
                return this.colors[this.colorIndex - 1];
            },
            width() {
                return this.size * this.ratio;
            },
            height() {
                return this.size * this.ratio;
            },
            fontSize() {
                return this.width / 2.5;
            },
            url() {
                let canvas = document.createElement('canvas');
                canvas.width = this.width;
                canvas.height = this.height;
                let context = canvas.getContext('2d');

                context.fillStyle = this.color;
                context.fillRect(0, 0, canvas.width, canvas.height);
                context.font = `${this.fontSize}px Arial`;
                context.textAlign = 'center';
                context.fillStyle = '#FFF';
                context.fillText(this.initials, this.width / 2, this.height / 1.5);

                let uri = canvas.toDataURL();
                canvas = null;

                return uri;
            },
        },
        data() {
            return {
                colors: [
                    '#1abc9c',
                    '#2ecc71',
                    '#3498db',
                    '#9b59b6',
                    '#34495e',
                    '#16a085',
                    '#27ae60',
                    '#2980b9',
                    '#8e44ad',
                    '#2c3e50',
                    '#f1c40f',
                    '#e67e22',
                    '#e74c3c',
                    '#ecf0f1',
                    '#95a5a6',
                    '#f39c12',
                    '#d35400',
                    '#c0392b',
                    '#bdc3c7',
                    '#7f8c8d',
                ],
            };
        },
    };
</script>

<style scoped lang="scss">
    .rounded {
        border-radius: 50%;
    }
</style>
