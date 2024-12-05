#!/bin/bash

# Wait for MySQL to be ready
until php artisan db:monitor > /dev/null 2>&1; do
  echo "Waiting for database connection..."
  sleep 2
done

# Run migrations
php artisan migrate --force
php artisan optimize:clear

# Start Apache in foreground
exec "$@"
