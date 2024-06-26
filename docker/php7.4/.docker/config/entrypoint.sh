#!/usr/bin/env bash

set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php "$@"
fi

# Change storage folder's ownership since php-fpm.ini's default user is www-data
mkdir -p /app/storage
chown -R www-data:www-data /app/storage

#!/bin/bash
export NVM_DIR="/root/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

exec "$@"
