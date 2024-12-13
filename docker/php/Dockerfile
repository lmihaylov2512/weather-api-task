FROM composer:2.8 AS composer
FROM php:8.3-fpm

# set working directory
WORKDIR /var/www/html

# install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

# install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# install xdebug extension
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# clear cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# copy a custom start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# change non-root user for security concern
USER www-data

# expose port 9000 and start with a custom script
EXPOSE 9000
CMD ["sh", "/usr/local/bin/start.sh"]
