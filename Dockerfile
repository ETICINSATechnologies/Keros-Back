FROM php:7.2.8-apache

WORKDIR /var/www

COPY . ./keros-api

RUN a2enmod rewrite \
    # General preparation
    && apt-get update \
    && apt-get install git zip unzip -yq \
    # Install Apache PHP MySQL modules
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql \
    # Go into project folder
    && cd keros-api \
    # Move custom Apache configuration to default location
    && mv .deploy/apache.conf /etc/apache2/sites-enabled/000-default.conf \
    # Override settings.ini with docker settings
    && mv .deploy/settings.docker.ini src/settings.ini \
    # Set up composer
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install \
    # Allow the Apache user to own all files
    && chown -R www-data:www-data /var/www