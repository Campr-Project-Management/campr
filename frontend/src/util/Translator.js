import Vue from 'vue';

if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
    document.documentElement.lang = window.user.locale;
    window.Translator.locale = window.user.locale;
}

Vue.prototype.translate = (key) => {
    return window.Translator.trans(key);
};

Vue.translate = (key) => {
    return window.Translator.trans(key);
};
