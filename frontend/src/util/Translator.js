export default {
    install(Vue, options) {
        if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
            document.documentElement.lang = window.user.locale;
            window.Translator.locale = window.user.locale;
        }

        Vue.prototype.translate = (key, parameters, domain, locale) => {
            if (!locale) {
                locale = getCurrentLocale(options.store);
            }

            return trans(key, parameters, domain, locale);
        };

        Vue.translate = (key, parameters, domain, locale) => {
            if (!locale) {
                locale = getCurrentLocale(options.store);
            }

            return trans(key, parameters, domain, locale);
        };

        Vue.mixin({
            filters: {
                trans(key, parameters, domain, locale) {
                    if (!locale) {
                        locale = getCurrentLocale(options.store);
                    }

                    return trans(key, parameters, domain, locale);
                },
            },
        });
    },
};

/**
 * Current Locale
 * @param {object} store
 * @return {string}
 */
function getCurrentLocale(store) {
    let locale = 'en';
    if (store && store.getters.locale) {
        locale = store.getters.locale;
    }

    return locale;
}

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
    if (!locale) {
        locale = window.user.locale;
    }

    return window.Translator.trans(key, parameters, domain, locale);
}

