FROM webdevops/php-nginx:8.0 as vendor


# Run updates
RUN apt-get update --fix-missing && apt-get install -y

# Install Curl
RUN apt-get install curl -y

# Install supervisor
RUN apt-get install python3 -y
RUN curl "https://bootstrap.pypa.io/get-pip.py" -o "get-pip.py"
RUN python3 get-pip.py
RUN pip install supervisor



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
