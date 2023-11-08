## build context is at docker/

FROM php:8.2-apache AS php_webserver_upstream
FROM php:8.2-cli AS php_cli_upstream
FROM composer/composer:2-bin AS composer_upstream
FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream

FROM php_cli_upstream AS project_builder

ARG SHOPWARE_VERSION="^6.5"

# Set working directory
WORKDIR /var/www/html

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
    jq \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Set environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"

# Install Composer
COPY --from=composer_upstream --link /composer /usr/bin/composer

# copy code
ADD ./ /var/www/html

RUN jq --arg v "$SHOPWARE_VERSION" '.require."shopware/core" = $v' composer.json > composer.json.tmp && \
    mv composer.json.tmp composer.json && \
    apt-get remove -y jq && \
    apt-get autoremove -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

ENV COMPOSER_MEMORY_LIMIT=-1

# prepare code
RUN composer update --no-cache --prefer-dist --no-scripts --no-interaction --ignore-platform-reqs --no-dev && \
    rm -rf \
    .env.* \
    .gitignore \
    docker-compose.yml \
    docker

FROM php_webserver_upstream AS project_runner

ARG SHOPWARE_VERSION="^6.5"
ARG LOCALE="en-GB"
ARG CURRENCY="EUR"

LABEL org.opencontainers.image.authors="Martin Bens <martin.bens@it-bens.de>" \
      org.opencontainers.image.title="Shopware Remote Admin API Test Wrapper" \
      org.opencontainers.image.source="https://github.com/it-bens/shopware-remote-admin-api-test-runnner" \
      org.opencontainers.image.description="A Docker image for running Shopware with a remote admin API for testing purposes." \
      org.opencontainers.image.licenses="MIT" \
      shopware.version=$SHOPWARE_VERSION \
      shopware.locale=$LOCALE \
      shopware.currency=$CURRENCY

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    gnupg \
    lsb-release \
    sudo \
    wget && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer_upstream --link /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    apcu \
    gd \
    intl \
    pdo_mysql \
    zip

# install MariaDB server
RUN /bin/sh -c echo debconf mysql-server/root_password password root | debconf-set-selections && \
    echo debconf mysql-server/root_password_again password root | debconf-set-selections && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get update && \
    apt-get install -y -q mariadb-server && \
    usermod -d /var/lib/mysql/ mysql && \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/* && \
    chmod 0444 /etc/mysql/my.cnf && \
    service mariadb start && \
    mysql --user=root --password=root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';FLUSH PRIVILEGES;use mysql;select user from user;" && \
    mysql --user=root --password=root -e "CREATE USER 'root'@'%' IDENTIFIED BY 'root';GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;use mysql;select user from user;" && \
    service mariadb restart

RUN sed -ri -e 's!/var/www/html!/opt/shopware-remote-admin-api-test-runnner/public!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!/opt/shopware-remote-admin-api-test-runnner/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

# Expose port for Apache
EXPOSE 80

# copy code
COPY --from=project_builder --link --chown=www-data:www-data /var/www/html /opt/shopware-remote-admin-api-test-runnner
WORKDIR /opt/shopware-remote-admin-api-test-runnner
# set jwt key permissions
RUN chmod -R 660 /opt/shopware-remote-admin-api-test-runnner/config/jwt/private.pem /opt/shopware-remote-admin-api-test-runnner/config/jwt/public.pem

RUN service mariadb start && \
    sudo -u www-data mkdir -p /opt/shopware-remote-admin-api-test-runnner/var/dump && \
    sudo -u www-data php -d memory_limit=-1 /opt/shopware-remote-admin-api-test-runnner/setup.php "$LOCALE" "$CURRENCY"

# Copy health check script
COPY ./docker/production/healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh

# Set health check
HEALTHCHECK --interval=30s --timeout=10s --retries=3 CMD /usr/local/bin/healthcheck.sh

# Copy entry script
COPY ./docker/production/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set entry point
ENTRYPOINT ["entrypoint.sh"]
CMD []