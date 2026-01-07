# syntax=docker/dockerfile:1.4
FROM php:8.4-fpm-trixie

WORKDIR /app

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt update -y && apt install -y $PHPIZE_DEPS git && install-php-extensions intl pdo_sqlite

COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

EXPOSE 8080