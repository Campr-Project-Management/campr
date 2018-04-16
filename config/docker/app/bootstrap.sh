#!/usr/bin/env bash

eval "$(ssh-agent -s)"

groupadd -g "$APPLICATION_GID" "$APPLICATION_GROUP"
useradd -u "$APPLICATION_UID" --home "/home/$APPLICATION_USER" --create-home --shell /bin/bash --no-user-group "$APPLICATION_GROUP" -g "$APPLICATION_GID"

rm -rf /dev/shm/campr

echo "Welcome to Campr!"
