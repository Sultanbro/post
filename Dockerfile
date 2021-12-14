FROM webdevops/php-nginx:8.0-alpine as vendor

WORKDIR /app

COPY composer.* ./

RUN composer install \
    --no-autoloader \
    --no-interaction \
    --no-scripts

FROM webdevops/php-nginx:8.0-alpine

WORKDIR /app

COPY --from=vendor /app/vendor /app/vendor
COPY . .
