var path = require('path');

module.exports = {
    /*
    ** Headers of the page
    */
    head: {
        title: 'CAMPR - https://campr.biz',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: 'Campr'}
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'}
        ]
    },
    /*
    ** Customize the progress bar color
    */
    loading: {color: '#3B8070'},
    /*
    ** Plugins
    */
    plugins: [
        '~/plugins/bootstrap',
        '~/plugins/fetch',
        '~/plugins/translator',
        '~/plugins/vue-charts',
        '~/plugins/vueditor',
        '~/plugins/vue-moment',
        '~/plugins/date',
        '~/plugins/numeral',
        '~/plugins/tooltip',
        '~/plugins/humanize-duration',
        '~/plugins/templating'
    ],
    /*
    ** CSS
    */
    css: [
        '~/assets/less/bootstrap.less'
    ],
    /*
    ** Build configuration
    */
    build: {
        /*
        ** Run ESLint on save
        */
        extend(config, {isDev, isClient}) {
            if (isDev && isClient) {
                config.module.rules.push({
                    enforce: 'pre',
                    test: /\.(js|vue)$/,
                    loader: 'eslint-loader',
                    exclude: /(node_modules)/
                });
            }

            config.resolve.alias.Translator = path.join(__dirname, 'plugins/translator.js');
            config.externals = {
                canvas: 'canvas'
            };
        }
    },
    generate: {
        routes: () => {
            return [];
        }
    }
};
