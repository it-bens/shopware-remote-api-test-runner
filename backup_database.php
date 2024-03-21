<?php

declare(strict_types=1);

use ITB\ShopwareRemoteAdminApiTestRunner\Setup\DatabaseBackupExecutor;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

require __DIR__ . '/vendor/shopware/platform/src/Core/TestBootstrap.php';

echo 'Backing up database...' . PHP_EOL;

$databaseBackupExecutor = new DatabaseBackupExecutor(__DIR__ . '/var/dump');
$databaseBackupExecutor->createDatabaseBackup();

echo 'Database backup done.' . PHP_EOL;