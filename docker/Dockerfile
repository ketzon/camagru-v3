FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev pkg-config \
    libpng-dev libjpeg-dev libfreetype6-dev \
    vim\
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo pdo_sqlite gd

WORKDIR /var/www
