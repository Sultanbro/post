FROM nginx:latest

RUN add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt install  ca-certificates apt-transport-https software-properties-common && \
    apt-get install -y \
    php8.0
