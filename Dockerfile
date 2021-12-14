FROM nginx:latest

RUN apt-get update && \
    apt install  ca-certificates apt-transport-https software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y \
    php8.0
