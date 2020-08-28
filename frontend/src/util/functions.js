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
