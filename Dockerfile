FROM webdevops/php-nginx:8.0-alpine as vendor

WORKDIR /app

COPY composer.* ./

RUN composer install
