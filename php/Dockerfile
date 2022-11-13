FROM php:fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

COPY ./data/ /var/www/html/
COPY ./php/php.ini /usr/local/etc/php/php.ini
