#!/usr/bin/env sh
set -eu

cd /var/www/html/client-web

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

php-fpm -D
exec "$@"
