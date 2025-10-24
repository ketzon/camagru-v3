FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev pkg-config \
    libpng-dev libjpeg-dev libfreetype6-dev \
    vim msmtp ca-certificates\
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo pdo_sqlite gd

RUN apt-get update && apt-get install -y sqlite3

RUN echo 'sendmail_path = "/usr/bin/msmtp -t"' > /usr/local/etc/php/conf.d/msmtp.ini

RUN printf "upload_max_filesize=10M\npost_max_size=10M\nmemory_limit=256M\nlog_errors=On\ndisplay_errors=Off\n" \
  > /usr/local/etc/php/conf.d/uploads.ini

COPY init-perms.sh /usr/local/bin/init-perms.sh
RUN chmod +x /usr/local/bin/init-perms.sh

WORKDIR /var/www
