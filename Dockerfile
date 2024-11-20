FROM php:8.1-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install pdo_mysql mysqli

# Aktivieren von Apache-Modulen
RUN a2enmod rewrite

# Kopiere die Projektdateien in das Image
COPY public/ /var/www/html/
COPY controller/ /var/www/html/controller/

# Expose Port
EXPOSE 80
