<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\Setup;

use Composer\InstalledVersions;
use Composer\Semver\Comparator;
use Doctrine\DBAL\Connection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Maintenance\System\Service\ShopConfigurator;

final class AfterInstallConfigurator
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    private readonly ShopConfigurator $shopConfigurator;

    public function __construct()
    {
        $shopConfiguratorConstructorArguments = [];

        /** @var Connection $connection */
        $connection = $this->getBrowser()
            ->getContainer()
            ->get(Connection::class);
        $shopConfiguratorConstructorArguments[] = $connection;

        if (Comparator::greaterThanOrEqualTo(InstalledVersions::getVersion('shopware/platform'), '6.6.10.0')) {
            /** @var EventDispatcherInterface $eventDispatcher */
            $eventDispatcher = $this->getBrowser()
                ->getContainer()
                ->get(EventDispatcherInterface::class);
            $shopConfiguratorConstructorArguments[] = $eventDispatcher;
        }

        $this->shopConfigurator = new ShopConfigurator(...$shopConfiguratorConstructorArguments);
    }

    public function changeSystemCurrency(string $currency): void
    {
        $this->shopConfigurator->setDefaultCurrency($currency);
    }

    public function changeSystemLanguage(string $locale): void
    {
        $this->shopConfigurator->setDefaultLanguage($locale);
    }
}
