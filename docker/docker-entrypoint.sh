#!/bin/bash

# Set environment-specific configurations here

# Start Apache in the foreground


composer install 

npm install &

apache2-foreground &

/usr/bin/supervisord -c /etc/supervisor/conf.d/laravel-worker.conf &

chown -R www-data:www-data ./** &

php artisan websockets:serve --host=0.0.0.0  --port=6001 & 

# Check if the cron service is running before starting it
if ! pgrep -x "cron" > /dev/null
then
    cron
fi

# Keep the script running
tail -f /var/log/cron.log

