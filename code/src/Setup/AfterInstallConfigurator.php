<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Maintenance\System\Service\ShopConfigurator;

final class AfterInstallConfigurator
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    public function changeSystemCurrency(string $currency): void
    {
        /** @var Connection $connection */
        $connection = $this->getBrowser()
            ->getContainer()
            ->get(Connection::class);
        $shopConfigurator = new ShopConfigurator($connection);
        $shopConfigurator->setDefaultCurrency($currency);
    }

    public function changeSystemLanguage(string $locale): void
    {
        /** @var Connection $connection */
        $connection = $this->getBrowser()
            ->getContainer()
            ->get(Connection::class);
        $shopConfigurator = new ShopConfigurator($connection);
        $shopConfigurator->setDefaultLanguage($locale);
    }
}
