Campr - Project Management Tool
===============================

[![build status](https://lab.trisoft.ro/campr/campr/badges/master/build.svg)](https://lab.trisoft.ro/campr/campr/commits/master)

#### Genereate Sami documentation

To generate Sami documentation for the code just write the correct annotations and descriptions for classes and run the following command:

`php bin/sami/sami.phar update bin/sami/config.php` OR

`php bin/sami/sami.phar update bin/sami/config.php -v`  to display any existing errors.

The command will generate/update the html files for all php classes inside the /src folder.

**Use the following pages to find more about the structure of this project:**

* [Admin Controllers](backend/src/AppBundle/Resources/docs/AdminControllers.md)
* [API Controllers](backend/src/AppBundle/Resources/docs/ApiControllers.md)
* [Entities](backend/src/AppBundle/Resources/docs/Entities.md)
* [Forms](backend/src/AppBundle/Resources/docs/Forms.md)
* [Services](backend/src/AppBundle/Resources/docs/Services.md)
* [Javascripts](backend/src/AppBundle/Resources/docs/Javascripts.md)

#### Frontend

`cd frontend`

`npm install` (install dependencies)

##### Prod

`npm run build` (build for production with minification)

##### Dev

`npm run dev` (serve with hot reload at localhost:8080)

##### Tests
`npm run unit` (run unit tests)

`npm run e2e` (run e2e tests)

`npm test` (run all tests)

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).
