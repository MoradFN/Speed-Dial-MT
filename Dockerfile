# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory to the root of the project
WORKDIR /var/www

# Copy the current directory contents into the container at /var/www
COPY . .

# Install Composer
RUN apt-get update && apt-get install -y curl git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (including vlucas/phpdotenv)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port 80 to access the application
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
