FROM nginx:latest

RUN apt-get update && apt-get install -y \
    php7.0-fpm \
    php7.0-mysql
