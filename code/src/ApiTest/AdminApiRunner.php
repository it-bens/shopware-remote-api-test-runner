<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\ApiTest;

use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Symfony\Component\HttpFoundation\Response;

class AdminApiRunner
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    public function afterCall(bool $isRequestTransactional): void
    {
        $this->resetAdminApiTestCaseTrait();
        $this->clearCacheData();
        if ($isRequestTransactional) {
            $this->stopTransactionAfter();
            $this->removeWrittenFilesAfterFilesystemTests();
        }
        $this->clearRequestStack();
        $this->resetRequestContext();
        $this->clearSession();
        $this->resetInjectedTranslatorSettings();
    }

    public function beforeCall(bool $isRequestTransactional): void
    {
        $this->clearCacheData();
        if ($isRequestTransactional) {
            $this->startTransactionBefore();
            $this->removeWrittenFilesAfterFilesystemTests();
        }
        $this->clearRequestStack();
        $this->resetInjectedTranslatorSettings();
    }

    public function call(Request $apiTestRequest): Response
    {
        $this->getBrowser(authorized: false)
            ->request(
                $apiTestRequest->httpRequest->getMethod(),
                $apiTestRequest->httpRequest->getUri(),
                $apiTestRequest->httpRequest->request->all(),
                $apiTestRequest->httpRequest->files->all(),
                $apiTestRequest->httpRequest->server->all(),
                $apiTestRequest->httpRequest->getContent(),
            );

        return $this->getBrowser(authorized: false)
            ->getResponse();
    }

    /**
     * This method is required because the traits are normally used in test classes.
     */
    private static function assertEquals($expected, $actual, string $message = ''): void
    {
    }

    /**
     * This method is required because the traits are normally used in test classes.
     */
    private static function assertNull($actual, string $message = ''): void
    {
    }

    /**
     * This method is required because the traits are normally used in test classes.
     */
    private static function getName(bool $withDataSet = true): string
    {
        return self::class;
    }

    /**
     * This method is required because the traits are normally used in test classes.
     */
    private static function nameWithDataSet(): string
    {
        return self::class;
    }
}
