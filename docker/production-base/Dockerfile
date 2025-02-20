## build context is at docker/

ARG PHP_VERSION=8.2

FROM ghcr.io/mlocati/php-extension-installer:2 AS php_extension_installer_upstream

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

# Add disable_warnings.ini to the PHP conf.d folder
COPY docker/production-base/php/disable_warnings.ini /usr/local/etc/php/conf.d/

# install MariaDB server
RUN /bin/sh -c echo debconf mysql-server/root_password password root | debconf-set-selections && \
    echo debconf mysql-server/root_password_again password root | debconf-set-selections && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get update && \
    apt-get install -y -q mariadb-server=1:11.4.5+maria~deb12 mariadb-client=1:11.4.5+maria~deb12 && \
    usermod -d /var/lib/mysql/ mysql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/* && \
    chmod 0444 /etc/mysql/my.cnf

ADD docker/production-base/mysql/ /etc/mysql/conf.d/
RUN chmod -R 0444 /etc/mysql/conf.d/