# Use an official PHP image with Apache
FROM php:8.3-apache

# Install necessary tools
RUN apt-get update && \
    apt-get install -y \
        git \
        zip \
        unzip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Symfony files to the container
COPY . .

# Configure Apache
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80