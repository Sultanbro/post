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
COPY .deploy/.conf/php/php.ini /usr/local/etc/php/conf.d/98-webdevops.ini

RUN composer dump-autoload

RUN php artisan storage:link
# RUN php artisan config:cache
RUN php artisan route:cache

RUN ln -s /shared/.env /app/.env

# Push useful bash aliases
# https://www.atatus.com/blog/14-useful-bash-aliases-that-make-shell-less-complex-and-more-fun/
RUN echo 'alias a="php /app/artisan"' >> ~/.bashrc
RUN echo 'alias ll="ls -la"' >> ~/.bashrc
RUN echo 'alias tin="php /app/artisan tinker"' >> ~/.bashrc
