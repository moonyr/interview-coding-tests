FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

FROM base AS dev

RUN composer install --dev
ENV PATH="$PATH:/app/vendor/bin"

FROM base AS prod
