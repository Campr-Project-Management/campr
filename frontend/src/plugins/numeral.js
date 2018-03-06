import num from 'numeral';

export default {
    install(Vue) {
        if (typeof window.user === 'object' &&
            typeof window.user.locale === 'string') {
            num.locale(window.user.locale);
        }

        Vue.formatMoney = (amount) => {
            return formatMoney(amount);
        };

        Vue.formatNumber = (amount) => {
            return formatNumber(amount);
        };

        Vue.prototype.$formatMoney = function(amount) {
            return formatMoney(amount);
        };

        Vue.prototype.$formatNumber = function(value) {
            return formatNumber(value);
        };

        Vue.mixin({
            filters: {
                formatMoney(amount) {
                    return formatMoney(amount);
                },
                formatNumber(value) {
                    return formatNumber(value);
                },
            },
        });
    },
};

/**
 * Format money
 * @param {number} amount
 * @return {string}
 */
function formatMoney(amount) {
    if (amount == null || isNaN(amount)) {
        return '';
    }

    let format = '$0,0.00';
    if (Math.ceil(amount) === amount) {
        format = '$0,0';
    }

    return num(amount).format(format);
}

/**
 * Format number
 * @param {number} value
 * @return {string}
 */
function formatNumber(value) {
    if (value == null || isNaN(value)) {
        return value;
    }

    return num(value).format();
}
