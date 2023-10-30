<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteAdminApiTestRunner\Setup;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

final class DatabaseRestoreExecutor
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    public function __construct(
        private readonly string $backupDirectory
    ) {
    }

    public function restoreDatabaseFromBackup(): void
    {
        /** @var Connection $connection */
        $connection = $this->getBrowser()
            ->getContainer()
            ->get(Connection::class);

        /** @var string $dbName */
        $dbName = $connection->getDatabase();

        /** @var array<string, mixed> $params */
        $params = $connection->getParams();
        $passwordString = '';
        if ($params['password'] ?? '') {
            $passwordString = '-p' . escapeshellarg($params['password']);
        }

        $path = sprintf('%s/%s_%s.sql', $this->backupDirectory, $params['host'] ?? '', $dbName);

        $cmd = sprintf(
            'mysql -u %s %s -h %s --port=%s %s < %s',
            escapeshellarg($params['user'] ?? ''),
            $passwordString,
            escapeshellarg($params['host'] ?? ''),
            escapeshellarg((string) ($params['port'] ?? '')),
            escapeshellarg($dbName),
            escapeshellarg($path)
        );

        $output = [];
        $returnCode = 0;
        exec($cmd, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new RuntimeException('Database restore failed.' . PHP_EOL . implode(PHP_EOL, $output));
        }
    }
}
