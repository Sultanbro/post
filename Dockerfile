ARG PREFIX=reg.cic.kz/centras
ARG NODE_VERSION=latest

FROM composer AS composer

FROM ${PREFIX}/php-fpm:8.0 as mycent-php-vendor
WORKDIR /release

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./composer.json /release/composer.json
COPY ./composer.lock /release/composer.lock

RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache

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

ENV PUSHER_APP_ID="874293"
ENV PUSHER_APP_KEY="7be3a303223fdcdf62d5"
ENV PUSHER_APP_SECRET="91d7feb674c6a3f29d46"
ENV PUSHER_APP_CLUSTER="ap2"
ENV MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
ENV MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

RUN echo "PUSHER_APP_ID=$PUSHER_APP_ID" >> /release/.env
RUN echo "PUSHER_APP_KEY=$PUSHER_APP_KEY" >> /release/.env
RUN echo "PUSHER_APP_SECRET=$PUSHER_APP_SECRET" >> /release/.env
RUN echo "PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER" >> /release/.env
RUN echo "MIX_PUSHER_APP_KEY=$PUSHER_APP_KEY" >> /release/.env
RUN echo "MIX_PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER" >> /release/.env

COPY ./public/ /release/public

COPY --from=mycent-node-vendor /release/node_modules /release/node_modules
COPY ./resources/ /release/resources
COPY ./package-lock.json /release/
COPY ./package.json /release/
COPY ./webpack.mix.js /release/

RUN npm run dev

FROM ${PREFIX}/php-fpm:8.0 as mycent-php-autoload

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
RUN mkdir /app/storage/framework/sessions
RUN mkdir /app/storage/framework/views
RUN mkdir /app/storage/framework/cache

RUN echo 'alias a="php artisan"' >> ~/.bashrc
