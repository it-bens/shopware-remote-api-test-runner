<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\Setup;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

final class DatabaseRestoreExecutor
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    private readonly Connection $connection;

    public function __construct(
        private readonly string $backupDirectory
    ) {
        /** @var Connection $connection */
        $connection = $this->getBrowser(false)
            ->getContainer()
            ->get(Connection::class);

        $this->connection = $connection;
    }

    public function restoreDatabaseFromBackup(): void
    {
        /** @var string $dbName */
        $dbName = $this->connection->getDatabase();

        /** @var array<string, mixed> $params */
        $params = $this->connection->getParams();
        $passwordString = '';
        if ($params['password'] ?? '') {
            $passwordString = '-p' . escapeshellarg($params['password']);
        }

        $path = sprintf('%s/%s_%s.sql', $this->backupDirectory, $params['host'] ?? '', $dbName);

        $cmd = sprintf(
            'mariadb -u %s %s -h %s --port=%s %s < %s',
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
