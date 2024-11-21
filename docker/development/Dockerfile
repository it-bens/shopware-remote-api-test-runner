## build context is at docker/
FROM php:8.4-apache
WORKDIR /var/www/html

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
	  bash \
    git \
    unzip

# use the mlocati/docker-php-extension-installer to install necessary packages and php extensions
# the installer cleans up all dev packages which were only used for compiling
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    apcu \
    gd \
    intl \
    pdo_mysql \
    zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# install composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

# replace default apache document root with custom public root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# enable apache modules
RUN a2enmod rewrite

RUN addgroup --gid 1000 php
RUN adduser --ingroup php --shell /bin/sh php
USER php

VOLUME /var/www/html
