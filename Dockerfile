ARG PREFIX=reg.cic.kz/centras
ARG NODE_VERSION=14
ARG PHP_VERSION=8

FROM composer AS composer

FROM php:${PHP_VERSION}-fpm as mycent-php-vendor
WORKDIR /release

RUN apt-get update -y
RUN apt-get install -y libxml2-dev \
                        curl \
                        iputils-ping \
                        libzip-dev \
                        default-mysql-client \
                        unzip \
                        libpq-dev

RUN docker-php-ext-install zip pdo pdo_pgsql pgsql
RUN docker-php-ext-enable zip pdo pdo_pgsql pgsql

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./composer.json /release/composer.json
COPY ./composer.lock /release/composer.lock

RUN composer install \
    --no-autoloader \
    --no-interaction \
    --no-scripts



FROM ${PREFIX}/node:${NODE_VERSION} as mycent-node-vendor
WORKDIR /release

COPY ./package.json /release/package.json
COPY ./package-lock.json /release/package-lock.json

RUN npm i



FROM ${PREFIX}/node:${NODE_VERSION} as mycent-node-assets
WORKDIR /release

COPY ./public/ /release/public

COPY --from=mycent-node-vendor /release/node_modules /release/node_modules
COPY ./resources/ /release/resources
COPY ./package-lock.json /release/
COPY ./package.json /release/
COPY ./webpack.mix.js /release/

RUN npm run dev

FROM php:${PHP_VERSION}-fpm as mycent-php-autoload

WORKDIR /release

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./composer.json /release/
COPY ./composer.lock /release/

COPY ./app /release/app
COPY ./bootstrap /release/bootstrap
COPY ./config /release/config
COPY ./database /release/database
COPY ./artisan /release/artisan
# COPY ./storage /release/storage
COPY ./routes /release/routes

COPY --from=mycent-php-vendor /release/vendor /release/vendor

RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache

RUN docker-php-ext-install zip pdo pdo_pgsql pgsql
RUN docker-php-ext-enable zip pdo pdo_pgsql pgsql

RUN composer install
RUN composer dump-autoload

FROM ${PREFIX}/nginx:laravel
WORKDIR /app

COPY ./composer.json /app/
COPY ./composer.lock /app/
COPY ./package.json /app/
COPY ./package-lock.json /app/

# COPY --from=node-vendor /release/node_modules /app/node_modules
COPY --from=mycent-php-autoload /release/vendor /app/vendor
COPY --from=mycent-node-assets /release/public /app/public
COPY --from=mycent-node-assets /release/.env /app/.env

# RUN composer install \
#     --no-dev \
#     --no-autoloader \
#     --no-interaction \
#     --no-scripts

# RUN npm ci

# RUN apt-get update && apt-get install -y procps
COPY ./app/ /app/app
COPY ./bootstrap/ /app/bootstrap
COPY ./config/ /app/config
COPY ./database/ /app/database
COPY ./routes/ /app/routes
COPY ./storage/ /app/storage
COPY ./resources/ /app/resources

# COPY ./storage/ /app/storage

# COPY ./.env /app/
COPY ./artisan /app/

RUN chmod 0777 storage -R
RUN mkdir -p /app/storage/framework/sessions
RUN mkdir -p /app/storage/framework/views
RUN mkdir -p /app/storage/framework/cache

RUN echo 'alias a="php artisan"' >> ~/.bashrc
