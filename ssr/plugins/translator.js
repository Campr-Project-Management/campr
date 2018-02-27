import Vue from 'vue';

let Translator = require('./translator/index');

let translatorConfig = [
    'config',
    'en',
    'de'
];

translatorConfig.map(item => {
    const tc = require('./translator/' + item);
    tc(Translator);
});

Translator.install = (Vue, options) => {
    Vue.translate = str => Translator.trans(str);

    Vue.mixin({
        translate: str => Translator.trans(str)
    });

    Vue.prototype.translate = str => Translator.trans(str);
};

global.Translator = Translator;

Vue.use(Translator);
