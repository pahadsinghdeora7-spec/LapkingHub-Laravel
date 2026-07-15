#!/usr/bin/env sh
set -eu

if [ "${APP_CIPHER:-AES-256-CBC}" != "AES-256-CBC" ]; then
    echo "APP_CIPHER must be AES-256-CBC for this Laravel deployment." >&2
    exit 1
fi

if ! php -r '$key = getenv("APP_KEY") ?: ""; if (!str_starts_with($key, "base64:")) { exit(1); } $decoded = base64_decode(substr($key, 7), true); exit($decoded !== false && strlen($decoded) === 32 ? 0 : 1);'; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "Generated a valid temporary Laravel APP_KEY. Set APP_KEY in Railway variables for persistent encrypted sessions." >&2
fi

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan storage:link --force || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
