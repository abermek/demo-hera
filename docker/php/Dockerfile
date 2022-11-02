FROM php:7.4-fpm

RUN addgroup -gid 1000 app
RUN adduser --disabled-password -uid 1000 --gid 1000 app

RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        libzip-dev \
    && docker-php-ext-install zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
