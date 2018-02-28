import num from 'numeral';

export default {
    install(Vue) {
        if (typeof window.user === 'object' && typeof window.user.locale === 'string') {
            num.locale(window.user.locale);
        }

        Vue.prototype.$formatMoney = function(amount) {
            return formatMoney(amount);
        };

        Vue.mixin({
            filters: {
                formatMoney(amount) {
                    return formatMoney(amount);
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
