<?php

declare(strict_types=1);

use ITB\ShopwareRemoteAdminApiTestRunner\ApiTest\RequestFactory;
use ITB\ShopwareRemoteAdminApiTestRunner\ApiTest\Runner;
use ITB\ShopwareRemoteAdminApiTestRunner\Setup\DatabaseRestoreExecutor;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

require __DIR__ . '/../vendor/shopware/platform/src/Core/TestBootstrap.php';

$apiTestRequestFactory = new RequestFactory();

try {
    $apiTestRequest = $apiTestRequestFactory->createFromGlobals();
} catch (Throwable $throwable) {
    $response = new Response();
    $response->setStatusCode(HttpResponse::HTTP_BAD_REQUEST);
    $response->setContent($throwable->getMessage());
    $response->send();
    exit(1);
}

if ($apiTestRequest->httpRequest->getPathInfo() === '/reset') {
    try {
        $databaseResetExecutor = new DatabaseRestoreExecutor(__DIR__ . '/../var/dump');
        $databaseResetExecutor->restoreDatabaseFromBackup();

        $response = new Response();
        $response->setStatusCode(HttpResponse::HTTP_OK);
        $response->send();
        exit(0);
    } catch (Throwable $throwable) {
        $response = new Response();
        $response->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        $response->setContent($throwable->getMessage());
        $response->send();
        exit(1);
    }
}

$remotelyCalledApiTest = new Runner();

try {
    $remotelyCalledApiTest->beforeCall($apiTestRequest->isRequestTransactional());
    $response = $remotelyCalledApiTest->call($apiTestRequest);
    $remotelyCalledApiTest->afterCall($apiTestRequest->isRequestTransactional());
} catch (Throwable $throwable) {
    $response = new Response();
    $response->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    $response->setContent($throwable->getMessage());
    $response->send();
    exit(1);
}

$response->send();
exit(0);
