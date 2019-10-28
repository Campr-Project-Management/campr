#!/usr/bin/env bash

if [ "$1" = 'supervisord' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p var/cache var/log public/media
	setfacl -R -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads
	setfacl -dR -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads

  bin/console doc:database:create --if-not-exists

  until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
    (>&2 echo "Waiting for MySQL to be ready...")
    sleep 1
  done

  composer install

  bin/console doc:database:create --if-not-exists

  bin/console app:migrate:all-databases

  cd /app/frontend && yarn && cd /app
fi

exec "$@"
