FROM php:7.2.1-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql

RUN apt -y update \
    && apt -y upgrade

RUN apt-get -y update \
    && apt-get -y install \
    git \
    wget \
    unzip \
    nano

RUN cd /tmp/ \
    && wget https://getcomposer.org/composer.phar \
    && chmod +x ./composer.phar \
    && mv ./composer.phar /usr/local/bin/composer \
    && /usr/local/bin/composer self-update