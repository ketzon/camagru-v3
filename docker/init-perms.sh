#!/bin/sh
set -e
BASE=/var/www/storage
mkdir -p "$BASE"/renders "$BASE"/uploads
if [ ! -f "$BASE/db.sqlite" ]; then
    install -m 660 -o www-data -g www-data /dev/null "$BASE/db.sqlite"
fi
chmod -R 777 "$BASE"
exec docker-php-entrypoint "$@"
