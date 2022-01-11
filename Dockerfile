FROM webdevops/php-nginx:8.0 as vendor

RUN apt-get install -y supervisor

COPY .deploy/conf/supervisor/laravel-worker.conf /etc/supervisord/conf.d/laravel-worker.conf

WORKDIR /app

COPY composer.* ./

RUN composer install \
    --no-autoloader \
    --no-interaction \
    --no-scripts

FROM webdevops/php-nginx:8.0

WORKDIR /app

COPY --from=vendor /app/vendor /app/vendor
COPY . .

COPY .deploy/conf/nginx/default.conf /opt/docker/etc/nginx/vhost.conf

RUN composer dump-autoload

RUN php artisan storage:link
