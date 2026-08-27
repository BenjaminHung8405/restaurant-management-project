#!/bin/sh
set -eu

for directory in /var/www/html/storage /var/www/html/assets/uploads; do
    if [ -d "$directory" ]; then
        chown -R www-data:www-data "$directory"
        chmod -R 775 "$directory"
    fi
done

if [ -f /var/www/html/composer.json ] && [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

exec "$@"
