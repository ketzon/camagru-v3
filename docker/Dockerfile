FROM php:8.2-fpm
RUN docker-php-ext-install pdo pdo_sqlite
RUN apt-get update && apt-get install -y libpng-dev \
 && docker-php-ext-configure gd --with-jpeg \
 && docker-php-ext-install gd \
 && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www
