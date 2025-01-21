## build context is at docker/

ARG PHP_VERSION=8.2

FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream

FROM php:$PHP_VERSION-apache-bookworm

## add MariaDB repository for more recent versions
RUN mkdir -p /etc/apt/keyrings && \
    curl -o /etc/apt/keyrings/mariadb-keyring.pgp 'https://mariadb.org/mariadb_release_signing_key.pgp'
ADD docker/production/mariadb.sources /etc/apt/sources.list.d/mariadb.sources

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    git \
    gnupg \
    apt-transport-https \
    lsb-release \
    sudo \
    wget \
    zip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer_upstream /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    apcu \
    gd \
    intl \
    pdo_mysql \
    zip