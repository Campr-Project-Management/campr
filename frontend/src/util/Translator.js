import Vue from 'vue';

Vue.prototype.translate = (key) => {
    return window.Translator.trans(key);
};

Vue.translate = (key) => {
    return window.Translator.trans(key);
};
