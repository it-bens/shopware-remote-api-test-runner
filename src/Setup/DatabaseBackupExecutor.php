<?php

declare(strict_types=1);

namespace ITB\ShopwareRemoteAdminApiTestRunner\Setup;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

final class DatabaseBackupExecutor
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    private const TABLES_TO_BACKUP = [
        'category',
        'category_tag',
        'category_translation',
        'country',
        'country_state',
        'country_state_translation',
        'country_translation',
        'currency',
        'currency_country_rounding',
        'currency_translation',
        'customer',
        'customer_address',
        'customer_group',
        'customer_group_registration_sales_channel',
        'customer_group_translation',
        'customer_tag',
        'delivery_time',
        'delivery_time_translation',
        'document',
        'language',
        'locale',
        'locale_translation',
        'mail_header_footer',
        'mail_header_footer_translation',
        'mail_template',
        'mail_template_translation',
        'mail_template_media',
        'mail_template_translation',
        'main_category',
        'media',
        'media_folder',
        'media_folder_configuration',
        'media_folder_configuration_media_thumbnail_size',
        'media_tag',
        'media_thumbnail',
        'media_thumbnail_size',
        'media_translation',
        'order',
        'order_address',
        'order_customer',
        'order_delivery',
        'order_delivery_position',
        'order_line_item',
        'order_line_item_download',
        'order_tag',
        'order_transaction',
        'order_transaction_capture',
        'order_transaction_capture_refund',
        'order_transaction_capture_refund_position',
        'payment_method',
        'payment_method_translation',
        'payment_token',
        'product',
        'product_category',
        'product_category_tree',
        'product_configurator_setting',
        'product_cross_selling',
        'product_cross_selling_assigned_products',
        'product_cross_selling_translation',
        'product_custom_field_set',
        'product_download',
        'product_export',
        'product_feature_set',
        'product_feature_set_translation',
        'product_keyword_dictionary',
        'product_manufacturer',
        'product_manufacturer_translation',
        'product_media',
        'product_option',
        'product_price',
        'product_property',
        'product_preview',
        'product_review',
        'product_stream',
        'product_stream_filter',
        'product_stream_mapping',
        'product_stream_translation',
        'product_tag',
        'product_translation',
        'product_visibility',
        'promotion',
        'promotion_cart_rule',
        'promotion_discount',
        'promotion_discount_prices',
        'promotion_discount_rule',
        'promotion_individual_code',
        'promotion_order_rule',
        'promotion_persona_customer',
        'promotion_persona_rule',
        'promotion_sales_channel',
        'promotion_setgroup',
        'promotion_setgroup_rule',
        'promotion_translation',
        'property_group',
        'property_group_option',
        'property_group_option_translation',
        'property_group_translation',
        'rule',
        'rule_condition',
        'rule_tag',
        'sales_channel',
        'sales_channel_analytics',
        'sales_channel_api_context',
        'sales_channel_country',
        'sales_channel_currency',
        'sales_channel_domain',
        'sales_channel_language',
        'sales_channel_payment_method',
        'sales_channel_shipping_method',
        'sales_channel_translation',
        'shipping_method',
        'shipping_method_price',
        'shipping_method_tag',
        'shipping_method_translation',
        'tag',
        'tax',
        'tax_provider',
        'tax_provider_translation',
        'tax_rule',
        'unit',
        'unit_translation',
        'user',
        'user_config',
        'webhook',
    ];

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

        $tables = $connection->getSchemaManager()
            ->listTableNames();
        $tablesToIgnore = array_filter($tables, static fn (string $table): bool => ! in_array($table, self::TABLES_TO_BACKUP, true));
        $tablesToIgnoreStmt = implode(
            ' ',
            array_map(static fn (string $table): string => '--ignore-table ' . escapeshellarg($dbName . '.' . $table), $tablesToIgnore)
        );

        /** @var array<string, mixed> $params */
        $params = $connection->getParams();
        $passwordString = '';
        if ($params['password'] ?? '') {
            $passwordString = '-p' . escapeshellarg($params['password']);
        }

        $path = sprintf('%s/%s_%s.sql', $this->backupDirectory, $params['host'] ?? '', $dbName);

        file_put_contents($path, 'SET unique_checks=0;SET foreign_key_checks=0;SET autocommit=0;');
        $cmd = sprintf(
            'mysqldump -u %s %s -h %s --port=%s -q --opt --hex-blob --no-autocommit %s %s >> %s',
            escapeshellarg($params['user'] ?? ''),
            $passwordString,
            escapeshellarg($params['host'] ?? ''),
            escapeshellarg((string) ($params['port'] ?? '')),
            $tablesToIgnoreStmt,
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
