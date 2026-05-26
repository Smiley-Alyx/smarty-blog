#!/bin/sh
set -e

mkdir -p /var/www/html/var/smarty/compile /var/www/html/var/smarty/cache
chown -R www-data:www-data /var/www/html/var/smarty 2>/dev/null || chmod -R 777 /var/www/html/var/smarty

if [ -f /var/www/html/scripts/build-css.sh ]; then
    sh /var/www/html/scripts/build-css.sh
fi

exec docker-php-entrypoint "$@"
