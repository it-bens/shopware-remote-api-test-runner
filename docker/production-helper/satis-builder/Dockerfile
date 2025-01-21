## build context is at docker/

ARG PHP_VERSION=8.2

FROM composer/composer:2-bin AS composer_upstream
FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream

FROM php:$PHP_VERSION-cli-bookworm

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
    git \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer_upstream /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    ctype \
    iconv \
    zip

# Set environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"

# Install Composer
COPY --from=composer_upstream /composer /usr/bin/composer
