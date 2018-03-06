import moment from 'moment';

const DEFAULT_DATE_FORMAT = 'DD.MM.YYYY';

export default {
    install(Vue) {
        Vue.formatDate = (date, options) => {
            return formatDate(date, options);
        };

        Vue.prototype.$formatDate = function(date, options) {
            return formatDate(date, options);
        };

        Vue.mixin({
            filters: {
                date(date, options) {
                    return formatDate(date, options);
                },
            },
        });
    },
};

/**
 * Format date date
 * @param {string} date
 * @param {object} options
 * @return {string}
 */
function formatDate(date, options) {
    let defaultOptions = {
        format: DEFAULT_DATE_FORMAT,
        default: '-',
    };

    options = Object.assign(defaultOptions, options);
    if (!date) {
        return options.default;
    }

    return moment(date).format(options.format);
}
