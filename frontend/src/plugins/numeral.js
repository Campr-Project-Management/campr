import accounting from 'accounting';
import numeral from 'numeral';

export default {
    install(Vue) {
        accounting.settings = accountingSettings.en;

        // nodejs suuuuucks
        if (!process && typeof window.user === 'object' &&
            typeof window.user.locale === 'string' &&
            accountingSettings[window.user.locale]) {
            accounting.settings = accountingSettings[window.user.locale];
        }

        Vue.formatMoney = (amount, options) => {
            return formatMoney(amount, options);
        };

        Vue.formatNumber = (amount, options) => {
            return formatNumber(amount, options);
        };

        Vue.formatBytes = (value) => {
            return formatBytes(value);
        };

        Vue.prototype.$formatMoney = (amount, options) => {
            return formatMoney(amount, options);
        };

        Vue.prototype.$formatNumber = (value, options) => {
            return formatNumber(value, options);
        };

        Vue.prototype.$formatBytes = (value) => {
            return formatBytes(value);
        };

        Vue.prototype.formatMoney = (amount, options) => {
            return formatMoney(amount, options);
        };

        Vue.prototype.formatNumber = (value, options) => {
            return formatNumber(value, options);
        };

        Vue.prototype.formatBytes = (value) => {
            return formatBytes(value);
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
                formatBytes(value) {
                    return formatBytes(value);
                },
                bytes(value) {
                    return formatBytes(value);
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

const accountingSettings = {
    en: {
        currency: {
            symbol: '$', // default currency symbol is '$'
            format: '%s%v', // controls output: %s = symbol, %v = value/number (can be object: see below)
            decimal: '.', // decimal point separator
            thousand: ',', // thousands separator
            precision: 2, // decimal places
        },
        number: {
            precision: 0, // default precision on numbers is 0
            thousand: ',',
            decimal: '.',
        },
    },
    de: {
        currency: {
            symbol: '€', // default currency symbol is '$'
            format: '%s%v', // controls output: %s = symbol, %v = value/number (can be object: see below)
            decimal: '.', // decimal point separator
            thousand: ',', // thousands separator
            precision: 2, // decimal places
        },
        number: {
            precision: 0, // default precision on numbers is 0
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

    return accounting.formatMoney(amount, options);
}

/**
 * Format accountingber
 * @param {number} value
 * @param {object} options
 * @return {string}
 */
function formatNumber(value, options = {}) {
    if (value == null || isNaN(value)) {
        return value;
    }

    return accounting.formatNumber(value, options);
}

/**
 * Format bytes
 *
 * @param {int} value
 * @return {string}
 */
function formatBytes(value) {
    return numeral(value).format('0.0 ib');
}
