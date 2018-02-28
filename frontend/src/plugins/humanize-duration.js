import hd from 'humanize-duration';

export default {
    install(Vue) {
        Vue.prototype.$humanizeDuration = function(amount, options) {
            return humanizeDuration(amount, options);
        };

        Vue.mixin({
            filters: {
                humanizeDuration(amount, options) {
                    return humanizeDuration(amount, options);
                },
                humanizeHours(amount, options) {
                    return humanizeHours(amount, options);
                },
            },
        });
    },
};

/**
 * Default options
 * @return {{locale: string}}
 */
function defaultOptions() {
    let options = {
        locale: 'en',
        round: true,
    };

    if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
        options.locale = window.user.locale;
    }

    return options;
}

/**
 * Humanize duration
 * @param {number} amount
 * @param {object} options
 * @return {string}
 */
function humanizeDuration(amount, options) {
    options = Object.assign(defaultOptions(), options);

    return hd(amount, options);
}

/**
 * Humanize duration given as hours
 * @param {number} amount
 * @param {object} options
 * @return {string}
 */
function humanizeHours(amount, options) {
    options = Object.assign(defaultOptions(), options);

    return hd(amount * 60 * 60 * 1000, options);
}
