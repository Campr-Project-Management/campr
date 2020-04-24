import Vue from 'vue';
import Translator from './translator/index';
import config from './translator/config'
import de from './translator/de'
import en from './translator/en'

config(Translator);
de(Translator);
en(Translator);

// let Translator = require('./translator/index');
//
// let translatorConfig = [
//     'config',
//     'en',
//     'de'
// ];
//
// translatorConfig.map(item => {
//     const tc = require('./translator/' + item);
//     tc(Translator);
// });

Translator.install = (Vue, options) => {
    Vue.translate = str => Translator.trans(str);

    Vue.mixin({
        translate: str => Translator.trans(str)
    });

    Vue.prototype.translate = str => Translator.trans(str);
};

global.Translator = Translator;

Vue.use(Translator);

export function trans(text) {
    return Translator.trans(text);
}
