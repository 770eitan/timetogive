web: PWD1=$PWD && cd $PWD1/public && rm -f storage && ln -s ../storage/app/public storage && cd $PWD1 && php artisan optimize && vendor/bin/heroku-php-nginx -C nginx_app.conf public/
worker: php artisan queue:work redis --sleep=3 --timeout=1800
