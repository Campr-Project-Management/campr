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
    $(document).ready(function() {
        $('.mx-input').keyup(function() {
            let val = this.value;
            let lastLength;
            do {
                // Loop over the input to apply rules repeately to pasted inputs
                lastLength = val.length;
                val = replaceBadInputs(val);
            } while(val.length > 0 && lastLength !== val.length);
            this.value = val;
            if(this.value.length == 2) {
                this.value += ':';
            };
        });

        // Check the final result when the input has lost focus
        $('.mx-input').blur(function() {
            let val = this.value;
            val = (/^(([01][0-9]|2[0-3])h)|(([01][0-9]|2[0-3]):[0-5][0-9])$/.test(val) ? val : '');
            this.value = val;
        });
    });
}
