var path = require('path');

module.exports = {
    /*
    ** Headers of the page
    */
    head: {
        title: 'CAMPR - https://campr.cloud',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: 'Campr'},
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'},
        ],
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
        '~/plugins/translator.js',
        '~/plugins/vue-charts',
        '~/plugins/vueditor',
        '~/plugins/vue-moment',
        '~/plugins/date',
        '~/plugins/numeral',
        '~/plugins/tooltip',
        '~/plugins/humanize-duration',
        '~/plugins/templating',
        '~/plugins/theme',
    ],
    /*
    ** CSS
    */
    css: [
        '../frontend/src/css/bootstrap.less',
    ],
    dev: (process.env.NODE_ENV !== 'production'),
    /*
    ** Build configuration
    */
    build: {
        /*
        ** Run ESLint on save
        */
        extend(config, {isDev}) {
            if (isDev && process.client) {
                config.module.rules.push({
                    enforce: 'pre',
                    test: /\.(js|vue)$/,
                    loader: 'eslint-loader',
                    exclude: /(node_modules)/,
                });
            }

            config.resolve.alias.Translator = path.join(__dirname,
                'plugins/translator.js');
            config.externals = {
                canvas: 'canvas',
            };
            config.resolve.alias['theme'] = path.resolve(__dirname,
                '../frontend/src/css/themes/dark');
            config.resolve.alias['components'] = path.resolve(__dirname,
                '../frontend/src/components');
        },
    },
    generate: {
        routes: () => {
            return [];
        },
    },
    watchers: {
        webpack: {
            aggregateTimeout: 300,
            poll: 1000,
        },
    },
    babelrc: false,
    cacheDirectory: undefined,
    presets: ['@nuxt/babel-preset-app']
};
