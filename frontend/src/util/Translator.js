export default {
    install(Vue) {
        if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
            document.documentElement.lang = window.user.locale;
            window.Translator.locale = window.user.locale;
        }

        Vue.prototype.translate = (key) => {
            return trans(key);
        };

        Vue.translate = (key) => {
            return trans(key);
        };

        Vue.mixin({
            filters: {
                trans(key) {
                    return trans(key);
                },
            },
        });
    },
};

/**
 * Translate
 *
 * @param {string} key
 * @return {string}
 */
export function trans(key) {
    return window.Translator.trans(key);
}

