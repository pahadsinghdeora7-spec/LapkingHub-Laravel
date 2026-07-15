#!/usr/bin/env sh
set -eu

php artisan storage:link --force || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
