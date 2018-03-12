import num from 'accounting';

export default {
    install(Vue) {
        num.settings = settings.en;

        if (typeof window.user === 'object' &&
            typeof window.user.locale === 'string' &&
            settings[window.user.locale]) {
            num.settings = settings[window.user.locale];
        }

        Vue.formatMoney = (amount, options) => {
            return formatMoney(amount, options);
        };

        Vue.formatNumber = (amount, options) => {
            return formatNumber(amount, options);
        };

        Vue.prototype.$formatMoney = function(amount, options) {
            return formatMoney(amount, options);
        };

        Vue.prototype.$formatNumber = function(value, options) {
            return formatNumber(value, options);
        };

        Vue.mixin({
            filters: {
                formatMoney(amount, options = {}) {
                    return formatMoney(amount, options);
                },
                money(amount, options) {
                    return formatMoney(amount, options);
                },
                formatNumber(value, options) {
                    return formatNumber(value, options);
                },
            },
        });
    },
};

const currencyCodeToSymbolMap = {
    USD: '$',
    EUR: '€',
    GBP: '£',
};

const settings = {
    en: {
        currency: {
            symbol: '$',   // default currency symbol is '$'
            format: '%s%v', // controls output: %s = symbol, %v = value/number (can be object: see below)
            decimal: '.',  // decimal point separator
            thousand: ',',  // thousands separator
            precision: 2,   // decimal places
        },
        number: {
            precision: 0,  // default precision on numbers is 0
            thousand: ',',
            decimal: '.',
        },
    },
    de: {
        currency: {
            symbol: '€',   // default currency symbol is '$'
            format: '%s%v', // controls output: %s = symbol, %v = value/number (can be object: see below)
            decimal: '.',  // decimal point separator
            thousand: ',',  // thousands separator
            precision: 2,   // decimal places
        },
        number: {
            precision: 0,  // default precision on numbers is 0
            thousand: ',',
            decimal: '.',
        },
    },
};

/**
 * Convert currency code to currency symbol
 * @param {string} code
 * @return {string}
 */
function currencyCodeToSymbol(code) {
    if (Object.values(currencyCodeToSymbolMap).indexOf(code) >= 0) {
        return code;
    }

    if (!code || !currencyCodeToSymbolMap[code]) {
        code = 'USD';
    }

    return currencyCodeToSymbolMap[code];
}

/**
 * Format money
 * @param {number} amount
 * @param {object} options
 * @return {string}
 */
function formatMoney(amount, options = {}) {
    if (amount == null || isNaN(amount)) {
        return '';
    }

    if (options.symbol) {
        options.symbol = currencyCodeToSymbol(options.symbol);
    }

    return num.formatMoney(amount, options);
}

/**
 * Format number
 * @param {number} value
 * @param {object} options
 * @return {string}
 */
function formatNumber(value, options = {}) {
    if (value == null || isNaN(value)) {
        return value;
    }

    return num.formatNumber(value, options);
}
