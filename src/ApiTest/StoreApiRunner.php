<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteAdminApiTestRunner\ApiTest;

use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelLifecycleManager;
use Shopware\Core\Framework\Test\TestCaseHelper\TestBrowser;
use Shopware\Core\Kernel;
use Symfony\Component\HttpFoundation\Response;

class StoreApiRunner
{
    use IntegrationTestBehaviour;

    private ?Kernel $kernel = null;

    private ?TestBrowser $browser = null;

    public function afterCall(bool $isRequestTransactional): void
    {
        $this->kernel = null;
        $this->browser = null;

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
        if ($this->kernel === null) {
            $this->kernel = $this->getKernel();
        }
        if ($this->browser === null) {
            $this->browser = KernelLifecycleManager::createBrowser($this->kernel, false);
        }

        $this->browser->request(
            $apiTestRequest->httpRequest->getMethod(),
            $apiTestRequest->httpRequest->getUri(),
            $apiTestRequest->httpRequest->request->all(),
            $apiTestRequest->httpRequest->files->all(),
            $apiTestRequest->httpRequest->server->all(),
            $apiTestRequest->httpRequest->getContent(),
        );

        return $this->browser->getResponse();
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
}
