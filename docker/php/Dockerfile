FROM php:8.3-fpm

ARG UID=1000
ARG GID=1000

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl


RUN groupadd -g ${GID} app \
    && useradd -u ${UID} -g app -m app

USER app