#!/usr/bin/env bash

if [ ! -d '/app/backend/vendor' ] || [ ! -f "/app/backend/vendor/autoload.php" ]; then
  runuser -l $APPLICATION_USER -c 'cd /app && composer install --no-scripts'
  runuser -l $APPLICATION_USER -c 'cd /app/ssr && yarn'
  runuser -l $APPLICATION_USER -c 'cd /app/frontend && yarn && yarn build'
fi

if [ "$1" = 'supervisord' ] || [ "$1" = 'bin/console' ]; then
  mkdir -p var/cache var/log public/media web/static/js /dev/shm/campr/cache/
  setfacl -R -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads /dev/shm/campr
  setfacl -dR -m u:www-data:rwX -m u:application:rwX -m g:application:rwX -m u:"$(whoami)":rwX var web/static web/uploads /dev/shm/campr

  # MacOS // Vagrant
  if [[ "${VAGRANT}" == "VAGRANT" ]]; then
    mkdir -p /dev/shm/campr/cache
    chmod -R 777 /dev/shm/campr
  fi

  runuser -l $APPLICATION_USER -c 'cd /app && composer install'

  if [ -f "/app/bin/console" ]; then
    runuser -l $APPLICATION_USER -c 'cd /app && bin/console doc:database:create --if-not-exists'

    until runuser -l $APPLICATION_USER -c 'cd /app && bin/console doctrine:query:sql "select 1" >/dev/null 2>&1'; do
      (>&2 echo "Waiting for MySQL to be ready...")
      sleep 1
    done

    runuser -l $APPLICATION_USER -c 'cd /app && bin/console doc:database:create --if-not-exists'

    runuser -l $APPLICATION_USER -c 'cd /app && bin/console app:migrate:all-databases'
  fi

#  runuser -l $APPLICATION_USER -c 'cd /app/frontend && yarn && yarn build'

  chown -R "$APPLICATION_USER:$APPLICATION_GROUP" /app

  runuser -l $APPLICATION_USER -c 'cd /app && bin/console cache:clear'
  runuser -l $APPLICATION_USER -c 'cd /app && bin/front-static'
  runuser -l $APPLICATION_USER -c 'cd /app/frontend && yarn build'
fi

exec "$@"
