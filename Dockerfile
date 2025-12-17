FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer globally inside the image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure Xdebug for code coverage
RUN echo "xdebug.mode=coverage" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=0" >> /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html
