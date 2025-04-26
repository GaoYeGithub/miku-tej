# Use official PHP image with Apache
FROM php:8.2-apache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite (optional but useful)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy PHP files and composer files
COPY . .

# Install PHP dependencies
RUN composer install

# Set the default entrypoint to index.php
# (Apache does this automatically if index.php exists)
