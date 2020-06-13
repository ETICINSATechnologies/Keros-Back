FROM php:7.3-cli

COPY . /usr/src/app

WORKDIR /usr/src/app

RUN apt-get update \
    # General preparation
    # Workaround for man packages error for pdftk
    && mkdir -p /usr/share/man/man1 \
    # Install system packages
    && apt-get install git zip unzip pdftk libzip-dev -yq \
    # Install Apache PHP MySQL modules
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip \
    # Go into project folder
    && cd /usr/src/app \
    # Override settings.ini with docker settings
    && mv .deploy/settings.dev.ini src/settings.ini \
    # Set up composer
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install