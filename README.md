# Campr - Project Management Tool

[![build status](https://lab.trisoft.ro/campr/campr/badges/master/build.svg)](https://lab.trisoft.ro/campr/campr/commits/master)

## Docker DEV (http://dev.campr.biz)
Linux:
```
cp docker-compose.yml.linux.dist docker-compose.yml
```
OSX:
```
cp docker-compose.yml.mac.dist docker-compose.yml
```
then
```
cp config/docker/.env.dist config/docker/.env
docker-compose up
```
update `/etc/hosts` with `127.0.0.1 dev.campr.biz www.dev.campr.biz qaname.dev.campr.biz`

`qaname.dev.campr.biz` is just a test workspace, you'll need it's db from the team

update `parameters.yml`:
```
database_host: mysql
redis_host: redis
```

enter in the docker app container with `docker exec -it campr_app bash`, then run `composer install`

example of importing a db `docker exec -i campr_mysql_1 mysql -uroot -pcampr campr_dev < my.sql`

enter in the docker mysql container with `docker exec -it campr_mysql_1 bash` and run mysql cmds

frontend build:
```
bin/front-static
cd frontend
yarn install
yarn run build
cd ..
cd ssr
yarn install
yarn run build
```

## Frontend

`cd frontend`

`yarn install` (install dependencies)

##### Prod

`yarn run build` (build for production with minification)

##### Dev

`yarn run dev` (serve with hot reload at localhost:8080)

##### Tests
`yarn run unit` (run unit tests)

`yarn run e2e` (run e2e tests)

`yarn test` (run all tests)

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

## Generate Sami documentation

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
