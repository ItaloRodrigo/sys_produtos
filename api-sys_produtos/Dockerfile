# Base image
FROM php:8.3-fpm

ARG user=italo
ARG uid=1000

ENV COMPOSER_HOME=/home/$user/.composer

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    sockets \
    zip

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p $COMPOSER_HOME \
    && chown -R $user:$user /home/$user

WORKDIR /var/www

COPY --chown=www-data:www-data . /var/www

RUN chown -R www-data:www-data /var/www

USER $user

EXPOSE 9000

CMD ["sh", "-c", "composer install --no-dev --optimize-autoloader && php artisan key:generate && php artisan l5-swagger:generate && php-fpm"]
