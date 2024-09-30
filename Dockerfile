# Use PHP 8.2 with Apache
FROM php:8.2-apache
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
EXPOSE 80

# Install Composer
RUN apt-get update && apt-get install -y curl git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your application files to the container
COPY . .

# Install PHP dependencies (including vlucas/phpdotenv)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port 80 to access the application
EXPOSE 80

# Start Apache in the foreground (standard command for Apache in Docker)
CMD ["apache2-foreground"]
