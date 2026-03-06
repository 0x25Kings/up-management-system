FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

RUN apt-get update && apt-get install -y libpq-dev

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/sessions \
    storage/framework/cache \
    storage/framework/views

RUN chown -R www-data:www-data storage bootstrap/cache

RUN a2enmod rewrite

EXPOSE 10000

CMD ["apache2-foreground"]
