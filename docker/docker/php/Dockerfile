FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_CACHE_DIR=/tmp/composer-cache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli zip gd \
    && a2enmod rewrite headers alias \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY starter-kit/docker/php/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY starter-kit/docker/php/entrypoint.sh /usr/local/bin/patito-entrypoint

RUN chmod +x /usr/local/bin/patito-entrypoint

WORKDIR /var/www/html/client-web
ENTRYPOINT ["patito-entrypoint"]
CMD ["apache2-foreground"]
