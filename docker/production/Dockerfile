## build context is at docker/

ARG PHP_VERSION=8.2

FROM php:$PHP_VERSION-apache-bookworm AS php_webserver_upstream
FROM php:$PHP_VERSION-cli-bookworm AS php_cli_upstream
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
ADD ./code/ /var/www/html

RUN jq --arg v "$SHOPWARE_VERSION" '.require."shopware/platform" = $v' composer.json > composer.json.tmp && \
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

FROM php_cli_upstream AS project_builder_satis

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
COPY --from=php_extension_installer_upstream --link /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    ctype \
    iconv \
    zip

# Set environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"

# Install Composer
COPY --from=composer_upstream --link /composer /usr/bin/composer

RUN mkdir -p /opt && \
    cd /opt && \
    composer create-project composer/satis --stability=dev --keep-vcs
ADD docker/production/satis.json /opt/satis/satis.json
RUN cd /opt/satis && \
    php bin/satis build ./satis.json ./public

FROM php_webserver_upstream AS project_runner

ARG SHOPWARE_VERSION="^6.5"
ARG LOCALE="en-GB"
ARG CURRENCY="EUR"

ENV APP_ENV="test"
ENV APP_DEBUG="1"
ENV APP_SECRET="!not-a-secret!"
ENV KERNEL_CLASS="Shopware\Core\Kernel"
ENV DATABASE_URL="mysql://root:root@127.0.0.1:3306/shopware_test?serverVersion=mariadb-10.11.6"

LABEL org.opencontainers.image.authors="Martin Bens <martin.bens@it-bens.de>" \
      org.opencontainers.image.title="Shopware Remote API Test Wrapper" \
      org.opencontainers.image.source="https://github.com/it-bens/shopware-remote-api-test-runner" \
      org.opencontainers.image.description="A Docker image for running Shopware with a remote API for testing purposes." \
      org.opencontainers.image.licenses="MIT" \
      shopware.version=$SHOPWARE_VERSION \
      shopware.locale=$LOCALE \
      shopware.currency=$CURRENCY

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    git \
    gnupg \
    lsb-release \
    sudo \
    wget \
    zip && \
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

# Set environment variables for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="${PATH}:/root/.composer/vendor/bin"

# Install Composer (required for installing Shopware plugins)
COPY --from=composer_upstream --link /composer /usr/bin/composer

# install MariaDB server
RUN /bin/sh -c echo debconf mysql-server/root_password password root | debconf-set-selections && \
    echo debconf mysql-server/root_password_again password root | debconf-set-selections && \
    export DEBIAN_FRONTEND=noninteractive && \
    apt-get update && \
    apt-get install -y -q mariadb-server=1:10.11.6-0+deb12u1 mariadb-client=1:10.11.6-0+deb12u1 && \
    usermod -d /var/lib/mysql/ mysql && \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/* && \
    chmod 0444 /etc/mysql/my.cnf && \
    service mariadb start && \
    mysqladmin -u root password 'root' && \
    mysql --user=root --password=root -e "ALTER USER 'root'@'localhost' IDENTIFIED VIA unix_socket OR mysql_native_password USING PASSWORD('root');FLUSH PRIVILEGES;use mysql;select user from user;" && \
    mysql --user=root --password=root -e "CREATE USER 'root'@'%' IDENTIFIED VIA unix_socket OR mysql_native_password USING PASSWORD('root');GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;use mysql;select user from user;" && \
    service mariadb restart

ADD docker/production/mysql/ /etc/mysql/conf.d/
RUN chmod -R 0444 /etc/mysql/conf.d/ && \
    service mariadb start

RUN rm /etc/apache2/sites-enabled/* && \
    rm /etc/apache2/sites-available/*
ADD docker/production/apache.conf /etc/apache2/sites-available/default.conf
RUN a2ensite default.conf && \
    a2enmod rewrite

# Expose port for Apache
EXPOSE 80
# Port 8090 for Satis must not be exposed.

# copy shopware remote api test runner code
COPY --from=project_builder --link --chown=www-data:www-data /var/www/html /opt/shopware-remote-api-test-runner
RUN touch /opt/shopware-remote-api-test-runner/.env && \
    echo "APP_ENV=\"$APP_ENV\"" >> /opt/shopware-remote-api-test-runner/.env && \
    echo "APP_DEBUG=\"$APP_DEBUG\"" >> /opt/shopware-remote-api-test-runner/.env && \
    echo "APP_SECRET=\"$APP_SECRET\"" >> /opt/shopware-remote-api-test-runner/.env && \
    echo "DATABASE_URL=\"$DATABASE_URL\"" >> /opt/shopware-remote-api-test-runner/.env && \
    echo "KERNEL_CLASS=\"$KERNEL_CLASS\"" >> /opt/shopware-remote-api-test-runner/.env && \
    chown www-data:www-data /opt/shopware-remote-api-test-runner/.env

WORKDIR /opt/shopware-remote-api-test-runner

# set jwt key permissions
RUN chmod -R 660 /opt/shopware-remote-api-test-runner/config/jwt/private.pem /opt/shopware-remote-api-test-runner/config/jwt/public.pem

RUN sudo -u www-data sed -i "/^];/i\    Shopware\\\Administration\\\Administration::class => ['all' => true]," /opt/shopware-remote-api-test-runner/config/bundles.php && \
    sudo -u www-data sed -i "/^];/i\    Shopware\\\Elasticsearch\\\Elasticsearch::class => ['all' => true]," /opt/shopware-remote-api-test-runner/config/bundles.php && \
    sudo -u www-data sed -i "/^];/i\    Shopware\\\Storefront\\\Storefront::class => ['all' => true]," /opt/shopware-remote-api-test-runner/config/bundles.php && \
    service mariadb start && \
    sudo -u www-data mkdir -p /opt/shopware-remote-api-test-runner/var/dump && \
    sudo -u www-data php -d memory_limit=-1 /opt/shopware-remote-api-test-runner/setup.php "$LOCALE" "$CURRENCY" && \
    service mariadb restart

RUN cp /opt/shopware-remote-api-test-runner/vendor/shopware/platform/bin/shopware /opt/shopware-remote-api-test-runner/bin/console

# copy satis code
COPY --from=project_builder_satis --link --chown=www-data:www-data /opt/satis /opt/satis

# create static files directory
RUN mkdir -p /opt/static && \
    chown www-data:www-data /opt/static

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