FROM webdevops/php-nginx:8.0 as vendor

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
COPY .deploy/conf/supervisor/laravel-worker.conf /opt/docker/etc/supervisor.d/laravel-worker.conf

RUN composer dump-autoload

RUN php artisan storage:link
RUN php artisan config:cache
