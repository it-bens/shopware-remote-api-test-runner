<?php

declare(strict_types=1);

use ITB\ShopwareRemoteAdminApiTestRunner\ApiTest\Runner;
use ITB\ShopwareRemoteAdminApiTestRunner\Setup\DatabaseRestoreExecutor;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

require __DIR__ . '/vendor/shopware/core/TestBootstrap.php';

echo 'Resetting database...' . PHP_EOL;

$remotelyCalledApiTest = new Runner();
$remotelyCalledApiTest->beforeCall(true);
$remotelyCalledApiTest->afterCall(true);

$databaseRestoreExecutor = new DatabaseRestoreExecutor(__DIR__ . '/var/dump');
$databaseRestoreExecutor->restoreDatabaseFromBackup();

echo 'Database reset done.' . PHP_EOL;
