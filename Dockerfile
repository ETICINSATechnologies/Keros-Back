FROM php:7.2.8-apache

WORKDIR /var/www

COPY . ./keros-api

RUN a2enmod rewrite \
    ## Enable apache headers
    && a2enmod headers \
    # General preparation
    && apt-get update \
    && apt-get install git zip unzip pdftk libzip-dev -yq \
    # Install Apache PHP MySQL modules
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip \
    # Go into project folder
    && cd keros-api \
    # Move custom Apache configuration to default location
    && mv .deploy/apache.conf /etc/apache2/sites-enabled/000-default.conf \
    # Override settings.ini with docker settings
    && mv .deploy/settings.docker.ini src/settings.ini \
    # Set up composer
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install \
    # Allow the Apache user to own all files
    && chown -R www-data:www-data /var/www
