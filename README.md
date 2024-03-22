# Shopware Remote Admin API Test Runner

Shopware provides an Admin API which grants more or less direct access to the DAL. Integrations can be used to access this API. The Admin API is the most convient way to read data from and write data to Shopware from an outside application.

## Do I really require this project?

The documentation of the Admin API is generate automatically from the DAL entity definitions of Shopware. However, at some points, the documentation doesn't provide enough details for the development of an application that uses the Admin API. This test runner is intended to provide a more detailed documentation of the Admin API.

As all of you know, tests can be used to do three things: verify that the code works as expected, document the code, and provide the ability to debug the running code. While Unit tests should be the basis of your test pyramid, End-to-End tests can be used to verify that the API interaction works as expected.

Dockware provides excellent images for plugin development and to play with Shopware. But the images are not suitable for automated tests that require a "clean" system for every run. That's the point where this project steps in. It provides docker images that contain a complete Shopware instance which can be rolled back to the state after it started.

## Which image should I use?

If you have a look into the GitHub container registry of this project, you will notice that there are images for different languages, currencies and Shopware versions. Use the image that fits the need of your project.

### The Shopware default language problem

During the installation of Shopware, a default language has to be chosen or is set for you. The UUID of the default language is always the same: `2fbb5fe2e29a4d70aa5854ce7ce3e20b`. Shopware uses this UUID for the choice of the default language. All translation tables contain a foreign key referencing the language table. If the default language is changed in a running system, all existing translations will be a mess, because they point to the wrong language. This sounds more like an inconvenience than a problem, but it can render Shopware useless and it cannot always be undone. That's why Shopware has no command to do so.

However, changing the default language in a fresh installation is relatively safe. That is done during the building of the images provided by this project.

## How can the images be used?

### Vanilla Images

While I don't recommend to run the Unit tests inside the Shopware container, it's technically possible. The `/var/www/html` directory is not used and if the PHP version and installed extensions matches the requirements of the your project ... it is still not recommended. ;)

It's better to use the Shopware container as a service for your test container. Shopware is running on port `80`. A `docker-compose.yml` file could look like this:

```yaml
tests:
  image: php:8.2-cli
  extra_hosts:
    - host.docker.internal:host-gateway
  volumes:
    - ./:/var/www/html

e2e-test-shopware:
  image: ghcr.io/it-bens/it-bens/shopware-remote-admin-api-test-runner:6.5.6.1_de-DE_EUR
  extra_hosts:
    - host.docker.internal:host-gateway
```

The Shopware URL would be `http://e2e-test-shopware:80`.

### Modified Images

If you need to modify the Shopware instance, you can create a new Dockerfile that uses the image of this project as a base image. The Dockerfile could look like this:

```Dockerfile
FROM ghcr.io/it-bens/it-bens/shopware-remote-admin-api-test-runner:6.5.6.1_de-DE_EUR

RUN apt update && apt install -y unzip

ADD docker/test-e2e/UmbrellaTVirus.zip /UmbrellaTVirus.zip
RUN unzip /UmbrellaTVirus.zip -d /

ADD docker/test-e2e/satis.json /opt/satis/satis.json
RUN cd /opt/satis && \
    php bin/satis build ./satis.json ./public

RUN cd /opt/shopware-remote-admin-api-test-runner && \
    composer config repositories.local composer http://localhost:8090 && \
    composer config secure-http false

RUN service apache2 start && \
    service mariadb start && \
    cd /opt/shopware-remote-admin-api-test-runner && \
    composer require umbrella/t-virus && \
    php -d memory_limit=-1 bin/console plugin:refresh && \
    php -d memory_limit=-1 bin/console plugin:install UmbrellaTVirus && \
    php -d memory_limit=-1 bin/console plugin:activate UmbrellaTVirus && \
    php -d memory_limit=-1 bin/console cache:clear && \
    php -d memory_limit=-1 backup_database.php && \
    chown -R www-data:www-data /opt/shopware-remote-admin-api-test-runner
```

Don't be confused by the Dockerfile, I will explain the backup and plugin stuff later.

## The features

### The database reset

A core feature, I mentioned, is the ability to reset the Shopware instance. During the image building a database backup is created via the respective Shopware console command. The image also provides a `backup_database.php` script that can be used to create a backup of the database at any given time.

The database reset can be done in two ways:
1) The `reset_database.php` script can be used via CLI.
2) A HTTP request to `/reset`. Any HTTP method can be used. No payload or query parameters are required.

The database backups are stored in the `/opt/shopware-remote-admin-api-test-runner/var/dump` directory. 

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
RUN cd /opt/shopware-remote-admin-api-test-runner && \
composer config repositories.local composer http://localhost:8090 && \
composer config secure-http false
```

After that, it's the usual install/activation process of Shopware. But there is one last point: if the plugin is modifying the database, a new database backup has to be created and the old ones have to be deleted.

### The missing feature: automated authentication

<details>
  <summary>Like the title says, this is actually a missing feature. I experimented with automated authentication to make the Admin API calls a little more convenient. The debugging of this feature was very interesting and insightful and ended in discarding this feature. If you're not interested in the details, just skip this point.</summary>

Shopware uses the [PHP league OAuth2 server](https://github.com/thephpleague/oauth2-server) to authenticate the Admin API calls. A successful token request returns a bearer token that can be used to authenticate API calls. So far, so usual.

Shopware provides a lot of internal test classes and test bootstrapping that this projects uses. A look into the `Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour` trait shows methods to create/return a pre-authenticated browser or at least methods to do the authentication very easy. Internally this methods execute the usual authentication process.

At some point in the authentication process, the `Shopware\Core\Framework\Api\EventListener\JsonRequestTransformerListener` is called. Doesn't sound exciting or deal-breaking? Well, the JSON request transforming isn't. But the way this method get the raw data from the request can be problematic:

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

The code reads the raw request content via the PHP input stream ... which is read-only. The data of the authentication request is overwritten with the content of the actual request sent to the Shopware instance before the authentication procedure. **The result: if the request contains a body, the authentication fails.**

Because the input stream is read-only, I couldn't find a way to hook into this process and prevent the data override without a massive intervention into the Shopware core. That's why I discarded the automated authentication feature.
</details>

## What's next?

The Admin API is really good for ... administration stuff. But it's cumbersome to use to create data that is normally created by a user, like orders. That's why I want to explore how this project can be used for the Shop API as well.

## Contributing
I am really happy that the software developer community loves Open Source, like I do! ♥

That's why I appreciate every issue that is opened (preferably constructive) and every pull request that provides other or even better code to this package.

You are all breathtaking!