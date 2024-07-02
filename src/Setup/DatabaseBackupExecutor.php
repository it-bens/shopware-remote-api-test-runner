<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteApiTestRunner\Setup;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

final class DatabaseBackupExecutor
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    public function __construct(
        private readonly string $backupDirectory
    ) {
    }

    public function createDatabaseBackup(): void
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

        file_put_contents($path, 'SET unique_checks=0;SET foreign_key_checks=0;SET autocommit=0;');
        $cmd = sprintf(
            'mysqldump -u %s %s -h %s --port=%s -q --opt --hex-blob --no-autocommit %s >> %s',
            escapeshellarg($params['user'] ?? ''),
            $passwordString,
            escapeshellarg($params['host'] ?? ''),
            escapeshellarg((string) ($params['port'] ?? '')),
            escapeshellarg($dbName),
            escapeshellarg($path)
        );
        file_put_contents($path, 'SET unique_checks=1;SET foreign_key_checks=1;SET autocommit=1;COMMIT;', FILE_APPEND);

        $output = [];
        $returnCode = 0;
        exec($cmd, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new RuntimeException('Database backup failed.' . PHP_EOL . implode(PHP_EOL, $output));
        }
    }
}
