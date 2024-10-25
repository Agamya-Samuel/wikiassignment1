# Use a PHP 8.0 base image
FROM php:8.0-apache

# Install necessary PHP extensions
RUN docker-php-ext-install curl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy app contents to the container
COPY . /var/www/html/

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Set working directory
WORKDIR /var/www/html

# Expose the default port for Vercel
EXPOSE 80
