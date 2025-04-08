# Shopware Remote API Test Runner

![Static Badge](https://img.shields.io/badge/Shopware-6.5.4.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.5.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.5.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.5.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.6.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.6.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.7.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.7.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.7.3-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.7.4-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.3-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.4-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.5-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.6-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.7-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.8-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.9-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.10-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.11-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.12-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.13-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.14-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.15-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.16-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.5.8.17-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.0.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.0.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.0.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.0.3-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.1.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.1.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.1.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.2.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.3.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.3.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.4.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.4.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.5.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.5.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.6.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.6.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.7.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.7.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.8.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.8.1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.8.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.9.0-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.10.2-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.6.10.3-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.7.0.0-rc1-189eff?logo=shopware)
![Static Badge](https://img.shields.io/badge/Shopware-6.7.0.0-rc2-189eff?logo=shopware)

Shopware provides two APIs: the Admin API and the Store API. 

The Admin API grants more or less direct access to the DAL. Integrations can be used to access this API. The Admin API is the most convenient way to read data from and write data to Shopware from an outside application.

The Store API is customer focused and allows the headless usage of a Storefront. It provides a much better and more natural way to create carts and place orders because they go through Shopware calculation logics.

## Do I really require this project?

The [documentation of the Admin API](https://shopware.stoplight.io/docs/admin-api/branches/v6.5/twpxvnspkg3yu-quick-start-guide) and the [documentation of the Store API](https://shopware.stoplight.io/docs/store-api/branches/v6.5/38777d33d92dc-quick-start-guide) are generated (at least partially) automatically from the DAL entity definitions of Shopware. However, at some points, the documentations doesn't provide enough details for the development of an application that uses the Admin or Store API.

As all of you know, tests can be used to do three things: verify that the code works as expected, document the code, and provide the ability to debug the running code. While Unit tests should be the basis of your test pyramid, End-to-End tests can be used to verify that the API interaction works as expected.

[Dockware](https://dockware.io) provides excellent images for plugin development and to play with Shopware. But the images are not suitable for automated tests that require a "clean" system for every run. That's the point where this project steps in. It provides docker images that contain a complete Shopware instance which can be rolled back to the state after it started.

## Which image should I use?

If you have a look into the GitHub container registry of this project, you will notice that there are images for different languages, currencies and Shopware versions. Use the image that fits the need of your project.

### The Shopware default language problem

During the installation of Shopware, a default language has to be chosen or is set for you. The UUID of the default language is always the same: `2fbb5fe2e29a4d70aa5854ce7ce3e20b`. All translation tables contain a foreign key referencing the language table. If the default language is changed in a running system, all existing translations will be a mess, because they point to the wrong language. This sounds more like an inconvenience than a problem, but it can render Shopware useless and it cannot always be undone. That's why Shopware has no command to do so.

However, changing the default language in a fresh installation is relatively safe. That is done during the building of the images provided by this project.

## How can the images be used?

### Vanilla Images

While I don't recommend to run the Unit tests inside the Shopware container, it's technically possible. The `/var/www/html` directory is not used and if the PHP version and installed extensions matches the requirements of your project ... it is still not recommended. 😉

It's better to use the Shopware container as a service for your test container. Shopware is running on port `80`. A `docker-compose.yml` file could look like this:

```yaml
tests:
  image: php:8.2-cli
  extra_hosts:
    - host.docker.internal:host-gateway
  volumes:
    - ./:/var/www/html

e2e-test-shopware:
  image: ghcr.io/it-bens/shopware-remote-api-test-runner:6.5.6.1_de-DE_EUR
  extra_hosts:
    - host.docker.internal:host-gateway
```

The Shopware URL would be `http://e2e-test-shopware:80`.

### Modified Images

If you need to modify the Shopware instance, you can create a new Dockerfile that uses the image of this project as a base image. The Dockerfile could look like this:

```Dockerfile
FROM ghcr.io/it-bens/shopware-remote-api-test-runner:6.5.6.1_de-DE_EUR

RUN apt update && apt install -y unzip

ADD docker/test-e2e/UmbrellaTVirus.zip /UmbrellaTVirus.zip
RUN unzip /UmbrellaTVirus.zip -d /

ADD docker/test-e2e/satis.json /opt/satis/satis.json
RUN cd /opt/satis && \
    php bin/satis build ./satis.json ./public

RUN cd /opt/shopware-remote-api-test-runner && \
    composer config repositories.local composer http://localhost:8090 && \
    composer config secure-http false

RUN service apache2 start && \
    service mariadb start && \
    cd /opt/shopware-remote-api-test-runner && \
    composer require umbrella/t-virus && \
    php -d memory_limit=-1 bin/console plugin:refresh && \
    php -d memory_limit=-1 bin/console plugin:install UmbrellaTVirus && \
    php -d memory_limit=-1 bin/console plugin:activate UmbrellaTVirus && \
    php -d memory_limit=-1 bin/console cache:clear && \
    php -d memory_limit=-1 backup_database.php && \
    chown -R www-data:www-data /opt/shopware-remote-api-test-runner
```

Don't be confused by the Dockerfile, I will explain the backup and plugin stuff later.

### Inside a pipeline

The images of this project can be used in CI/CD pipelines. Because pipeline configuration can be hard, I will provide an example for GitLab CI:

```yaml
phpunit e2e tests image build:
  stage: test preparation
  services:
    - docker:20.10.21-dind
  before_script:
    - docker login -u gitlab-ci-token -p $CI_JOB_TOKEN $CI_REGISTRY
  script:
    - DOCKER_BUILDKIT=1 docker build --cache-from ${E2E_TEST_IMAGE} --tag ${E2E_TEST_IMAGE} --file docker/test-e2e/Dockerfile .
    - docker push ${E2E_TEST_IMAGE}

phpunit e2e tests:
  stage: test execution
  image: registry.gitlab.com/umbrella/shopware/waneaaerps-bridge:phpunit-runner
  services:
    - name: ${E2E_TEST_IMAGE}
      alias: e2e-test-shopware
  before_script:
    - composer install
    - APP_ENV=test APP_DEBUG=0 composer run-script auto-scripts
  script:
    - SYMFONY_DEPRECATIONS_HELPER=disabled vendor/bin/phpunit --do-not-cache-result --log-junit phpunit-report.xml --coverage-cobertura phpunit-coverage.xml --coverage-text --colors=never
  artifacts:
    when: always
    reports:
      junit: phpunit-report.xml
      coverage_report:
        coverage_format: cobertura
        path: phpunit-coverage.xml
  coverage: '/^\s*Lines:\s*\d+.\d+\%/'
```

This pipeline builds a Shopware image extending an image of this project and pushes it to a registry. This image is then used as a service to for the actual PHPUnit test runner, which runs the E2E tests.

## The features

### The database reset

A core feature, I mentioned, is the ability to reset the Shopware instance. During the image building a database backup is created via the respective Shopware console command. The image also provides a `backup_database.php` script that can be used to create a backup of the database at any given time.

The database reset can be done in two ways:
1) The `reset_database.php` script can be used via CLI.
2) A HTTP request to `/reset`. Any HTTP method can be used. No payload or query parameters are required.

The database backups are stored in the `/opt/shopware-remote-api-test-runner/var/dump` directory. 

### The plugin stuff

Let's imagine you are the Umbrella cooperation and you want to sell the T-Virus online. Shopware is the weapon of choice, but you need some additional data for the product entity, like the global body count. So you create the `UmbrellaTVirus` plugin. The We-are-not-evil-at-all-ERP-system (short: WANEAAERPS) should use the Admin API to provide the additional data to Shopware. To test the WANEAAERPS integration with the Shopware instance, the plugin has to be installed and activated. 

Shopware provides two ways to install plugins: via the administration interface or via composer. Because the web interface isn't usable in an automated test environment, the plugin has to be installed via composer. Because Umbrella doesn't want to publish the T-Virus plugin, packagist is not an option. That's why the image contains a Satis instance which can act like a local packagist.

To provide the plugin via composer, Satis requires two things: a `satis.json` file and the plugin zip file. The `satis.json` file could look like this:

```json
{
  "name": "umbrella/not-evil-repository",
  "homepage": "http://localhost",
  "repositories": [
    { "type": "path", "url": "/UmbrellaTVirus/" }
  ],
  "require-all": true
}
```

The `UmbrellaTVirus.zip` has to be added to the image and unzipped afterwards. After that, the Satis instance can be re-built:

```Dockerfile
RUN cd /opt/satis && \
    php bin/satis build ./satis.json ./public
```

Satis can then be added to the `composer.json` file of this project (inside the container). Also insecure connections have to be allowed:
    
```Dockerfile
RUN cd /opt/shopware-remote-api-test-runner && \
composer config repositories.local composer http://localhost:8090 && \
composer config secure-http false
```

After that, it's the usual install/activation process of Shopware. But there is one last point: if the plugin is modifying the database, a new database backup has to be created and the old ones have to be deleted.

#### Warnings and Deprecations

There are several reasons why Shopware plugins cause PHP warnings and deprecations. If the plugin is somehow use (direct or indirect) during an API call, the API call would normally just contains the warning/deprecation message. This is usually disabled in a production environment.

If could come in handy to disable this warning/deprecation suppression in order to see the actual problems and tackle them. That's why this image supports the `DISABLE_WARNINGS` environment variable at the container startup.

```yaml
e2e-test-shopware:
  image: ghcr.io/it-bens/shopware-remote-api-test-runner:6.5.6.1_de-DE_EUR
  extra_hosts:
    - host.docker.internal:host-gateway
  environment:
    DISABLE_WARNINGS: false
```

### Static file serving

Shopware allows the upload of images and other static files via Admin/Action API. If the image upload should be tested with this image, access to the image via URL is necessary. Because a test-image shouldn't require access to the internet, the images of this project contain the ability to serve static files via port `8100`. The files have to be placed in the `/opt/static` directory. The usage in a derived image could look like this:

```Dockerfile
FROM ghcr.io/it-bens/shopware-remote-api-test-runner:6.5.6.1_de-DE_EUR

ADD /path/to/local/image.jpg /opt/static/image.jpg
```

The image would be available via `http://localhost:8100/image.jpg`.

### The missing feature: automated authentication

<details>
  <summary>Like the title says, this is actually a missing feature. I experimented with automated authentication to make the Admin API calls a little more convenient. The debugging of this feature was very interesting and insightful and ended in discarding this feature. If you're not interested in the details, just skip this point.</summary>


Shopware uses the [PHP league OAuth2 server](https://github.com/thephpleague/oauth2-server) to authenticate the Admin API calls. A successful token request returns a bearer token that can be used to authenticate API calls. So far, so usual.

Shopware provides a lot of internal test classes and test bootstrapping that this projects uses. A look into the `Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour` trait shows methods to create/return a pre-authenticated browser or at least methods to do the authentication very easy. Internally this methods execute the usual authentication process.

At some point in the authentication process, the `Shopware\Core\Framework\Api\EventListener\JsonRequestTransformerListener` is called. Doesn't sound exciting or deal-breaking? Well, the JSON request transforming isn't. But the way this method gets the raw data from the request can be problematic:

```php
/** @var Symfony\Component\HttpKernel\Event\RequestEvent $event */
$data = json_decode($event->getRequest()->getContent(), true);
```

This line calls the `getContent()` method of the `Symfony\Component\HttpFoundation\Request` class. Normally this wouldn't be a problem. But for the authentication procedure, Shopware creates a synthetic request object with a nulled `content` property. The actual data is stored in another property. That's why this code of the `getContent()` method is hit:

```php
if (null === $this->content || false === $this->content) {
    $this->content = file_get_contents('php://input');
}

return $this->content;
```

The code reads the raw request content via the PHP input stream. The data of the authentication request is overwritten with the content of the actual request sent to the Shopware instance before the authentication procedure. **The result: if the request isn't a authentication request anyways, the authentication fails.**

Because the PHP input stream is read-only, I couldn't find a way to hook into this process and prevent the data override without a massive intervention into the Shopware core. That's why I discarded the automated authentication feature.
</details>

## What's next?

Shopware is able to serve documents and pictures. It requires a publicly accessible storage to do so. Currently, this Shopware instance cannot serve stores files. That's why I want to explore how this project can be used to test the document and media API.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive) and every pull request that provides other or even better code to this package.

You are all breathtaking!