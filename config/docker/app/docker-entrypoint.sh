#!/usr/bin/env bash

if [ ! -d '/app/backend/vendor' ] || [ ! -f "/app/backend/vendor/autoload.php" ]; then
  cd /app && composer install --no-scripts
  cd /app/ssr && yarn
  cd /app/frontend && yarn && yarn build
fi

if [ "$1" = 'supervisord' ] || [ "$1" = 'bin/console' ]; then
	mkdir -p var/cache var/log public/media web/static/js /dev/shm/campr/cache/
	setfacl -R -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads /dev/shm/campr/
	setfacl -dR -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads /dev/shm/campr/

  # MacOS // Vagrant
  if [[ "${VAGRANT}" == "VAGRANT" ]]; then
    mkdir -p /dev/shm/campr/cache
    chmod -R 777 /dev/shm/campr
  fi

  composer install

  if [ -f "/app/bin/console" ]; then
    bin/console doc:database:create --if-not-exists

    until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
      (>&2 echo "Waiting for MySQL to be ready...")
      sleep 1
    done

    bin/console doc:database:create --if-not-exists

    bin/console app:migrate:all-databases
  fi

  cd /app/frontend && yarn && cd /app
fi

exec "$@"
