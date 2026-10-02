FROM dunglas/frankenphp:1.4-php8.4-alpine
RUN apk add --no-cache icu-dev libpng-dev libjpeg-turbo-dev libzip-dev zip unzip git oniguruma-dev redis
RUN docker-php-ext-configure gd --with-jpeg  && docker-php-ext-install  pdo_mysql  mbstring exif pcntl  bcmath gd intl  zip opcache 
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN git config --global --add safe.directory /var/www/html \
    && composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=9999"]