FROM webdevops/php-nginx as vendor

WORKDIR /app

COPY composer.* ./

RUN composer install
