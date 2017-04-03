'use strict';
let merge = require('webpack-merge'),
    prodEnv = require('./prod.env'),
    fs = require('fs');

if (process.env.NODE_ENV !== 'production') {
    let devLocalConfigFile = __dirname + '/dev.local.js';
    if (fs.existsSync(devLocalConfigFile)) {
        let devLocalConfig = require('./dev.local');
        prodEnv = merge(prodEnv, devLocalConfig);
    } else {
        console.log('dev.local.js is missing');
        process.exit(-1);
    }
}

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"'
});
