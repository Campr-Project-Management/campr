export default {
    install(Vue) {
        if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
            document.documentElement.lang = window.user.locale;
            window.Translator.locale = window.user.locale;
        }

        Vue.prototype.translate = (key, parameters, domain, locale) => {
            return trans(key, parameters, domain, locale);
        };

        Vue.translate = (key, parameters, domain, locale) => {
            return trans(key, parameters, domain, locale);
        };

        Vue.mixin({
            filters: {
                trans(key, parameters, domain, locale) {
                    return trans(key, parameters, domain, locale);
                },
            },
        });
    },
};

/**
 * Translate
 *
 * @param {string} key
 * @param {array} parameters
 * @param {string} domain
 * @param {string} locale
 * @return {string}
 */
export function trans(key, parameters, domain, locale) {
    return window.Translator.trans(key, parameters, domain, locale);
}

