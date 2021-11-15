FROM reg.cic.kz/centras/php-fpm:mycic as mycic-php-vendor
WORKDIR /app

RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache

COPY ./composer.json /app/composer.json
COPY ./composer.lock /app/composer.lock

RUN composer install \
    --no-autoloader \
    --no-interaction \
    --no-scripts



FROM reg.cic.kz/centras/node:14 as mycic-node-vendor
WORKDIR /app

COPY ./package.json /app/package.json
COPY ./package-lock.json /app/package-lock.json

RUN npm i



FROM reg.cic.kz/centras/node:14 as mycic-node-assets
WORKDIR /app

ENV PUSHER_APP_ID="874293"
ENV PUSHER_APP_KEY="7be3a303223fdcdf62d5"
ENV PUSHER_APP_SECRET="91d7feb674c6a3f29d46"
ENV PUSHER_APP_CLUSTER="ap2"
ENV MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
ENV MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

RUN echo "PUSHER_APP_ID=$PUSHER_APP_ID" >> /app/.env
RUN echo "PUSHER_APP_KEY=$PUSHER_APP_KEY" >> /app/.env
RUN echo "PUSHER_APP_SECRET=$PUSHER_APP_SECRET" >> /app/.env
RUN echo "PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER" >> /app/.env
RUN echo "MIX_PUSHER_APP_KEY=$PUSHER_APP_KEY" >> /app/.env
RUN echo "MIX_PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER" >> /app/.env

COPY ./public/ /app/public

COPY --from=mycic-node-vendor /app/node_modules /app/node_modules
COPY ./resources/ /app/resources
COPY ./package-lock.json /app/
COPY ./package.json /app/
COPY ./webpack.mix.js /app/

RUN npm run dev

FROM reg.cic.kz/centras/php-fpm:mycic as mycic-php-autoload

WORKDIR /app

COPY ./ /app/
COPY --from=mycic-php-vendor /app/vendor /app/vendor

RUN composer dump-autoload

FROM reg.cic.kz/centras/php-fpm:mycic as mycic-build-app
WORKDIR /app

COPY ./composer.json /app/
COPY ./composer.lock /app/
COPY ./package.json /app/
COPY ./package-lock.json /app/

# COPY --from=node-vendor /app/node_modules /app/node_modules
COPY --from=mycic-php-vendor /app/vendor /app/vendor
COPY --from=mycic-php-autoload /app/vendor/autoload.php /app/vendor/autoload.php
COPY --from=mycic-node-assets /app/public /app/public
COPY --from=mycic-node-assets /app/.env /app/.env

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
COPY ./resources/ /app/resources
COPY ./storage/ /app/storage
# COPY ./.env /app/
COPY ./artisan /app/

RUN composer dump-autoload

RUN chmod 0777 storage -R

RUN echo 'alias a="php artisan"' >> ~/.bashrc
