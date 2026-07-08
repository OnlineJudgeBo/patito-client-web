FROM php:8.2-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer-cache

RUN apt-get update \
    && apt-get install -y --no-install-recommends nginx git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd \
    && rm -rf /var/lib/apt/lists/* /etc/nginx/sites-enabled/default

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/docker/php/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/docker/php/entrypoint.sh /usr/local/bin/patito-entrypoint

RUN chmod +x /usr/local/bin/patito-entrypoint

WORKDIR /var/www/html/client-web

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY . ./
RUN composer dump-autoload --no-dev --optimize \
    && chown -R www-data:www-data /var/www/html/client-web

EXPOSE 80

ENTRYPOINT ["patito-entrypoint"]
CMD ["nginx", "-g", "daemon off;"]
