/**
 * Regular fro mask.
 * @param {any} val input argument
 * @return{int} like regular
 */
export function replaceBadInputs(val) {
    // Replace impossible inputs as they appear
    val = val.replace(/[^\dh:]/, '');
    val = val.replace(/^[^0-2]/, '');
    val = val.replace(/^([2-9])[4-9]/, '$1');
    val = val.replace(/^\d[:h]/, '');
    val = val.replace(/^([01][0-9])[^:h]/, '$1');
    val = val.replace(/^(2[0-3])[^:h]/, '$1');
    val = val.replace(/^(\d{2}[:h])[^0-5]/, '$1');
    val = val.replace(/^(\d{2}h)./, '$1');
    val = val.replace(/^(\d{2}:[0-5])[^0-9]/, '$1');
    val = val.replace(/^(\d{2}:\d[0-9])./, '$1');
    return val;
}

/**
 * Time picker mask
 * @return {void} alert
 */
export function timepicerMask() {
    $('.mx-input').keyup(function() {
        let val = this.value;
        let lastLength;
        do {
            // Loop over the input to apply rules repeately to pasted inputs
            lastLength = val.length;
            val = replaceBadInputs(val);
        } while (val.length > 0 && lastLength !== val.length);
        this.value = val;
        if (this.value.length == 2) {
            this.value += ':';
        };
    });
    // Check the final result when the input has lost focus
    $('.mx-input').blur(function() {
        let val = this.value;
        val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : '');
        this.value = val;
    });
}

/**
 * Parse url
 * @param {string} url
 * @return {{searchObject: {}, protocol: string, hostname: string, search: string, port: string, host: string, hash: string, pathname: string}}
 */
export function parseUrl(url) {
    let parser = document.createElement('a');
    let searchObject = {};
    // Let the browser do the work
    parser.href = url;
    // Convert query string to object
    let queries = parser.search.replace(/^\?/, '').split('&');
    for (let i = 0; i < queries.length; i++) {
        let split = queries[i].split('=');
        searchObject[split[0]] = split[1];
    }
    return {
        protocol: parser.protocol,
        host: parser.host,
        hostname: parser.hostname,
        port: parser.port,
        pathname: parser.pathname,
        search: parser.search,
        searchObject: searchObject,
        hash: parser.hash,
    };
}
