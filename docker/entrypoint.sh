#!/bin/bash
set -e

# Render injects DATABASE_URL — Laravel reads DB_URL
if [ -n "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
    export DB_URL="$DATABASE_URL"
fi

if [ -n "$DATABASE_URL" ] || [ -n "$DB_URL" ]; then
    export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
fi

php artisan storage:link --force 2>/dev/null || true
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
