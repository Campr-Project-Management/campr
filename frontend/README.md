# campr

> Campr PMTool frontend project

## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# run unit tests
npm run unit

# run e2e tests
npm run e2e

# run all tests
npm test
```

## Dump translations

php bin/console bazinga:js-translation:dump  --format=js --merge-domains

## Dump routes

php bin/console fos:js-routing:dump

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

## To dump translations,routes & generate symlinks in front run the following command

bin/front-static
