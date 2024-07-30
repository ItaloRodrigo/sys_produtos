FROM php:8.3-fpm

ARG user=italo
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

# Install redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Copy custom configurations PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Copy application code
COPY --chown=$user:$user . /var/www

# Install Laravel dependencies
RUN composer install --prefer-dist --no-scripts --no-dev --optimize-autoloader

# Generate Laravel application key
RUN php artisan key:generate

USER $user

EXPOSE 9000
