<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    KiwiCommerce_AdminActivity
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */
namespace KiwiCommerce\AdminActivity\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package KiwiCommerce\AdminActivity\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'kiwicommerce_activity'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('kiwicommerce_activity')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Primary key for the Table'
        )->addColumn(
            'username',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            40,
            ['nullable' => false, 'default' => ''],
            'Store admin username'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false, 'default' => ''],
            'Full name of admin'
        )->addColumn(
            'admin_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => false, 'unsigned' => true, 'default' => 1],
            'Store id of admin user'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            5,
            ['nullable' => false, 'unsigned' => true, 'default' => 0],
            'Insert store id'
        )->addColumn(
            'action_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            ['nullable' => false, 'default' => ''],
            'Action type like New, Edit, View, Delete, Mass Delete, etc'
        )->addColumn(
            'remote_ip',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            ['nullable' => false, 'default' => ''],
            'IP address of logged in admin user'
        )->addColumn(
            'user_agent',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '',
            ['nullable' => false, 'default' => ''],
            'Store browser’s user agent'
        )->addColumn(
            'module',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['nullable' => false, 'default' => ''],
            'Name of module in which action performed'
        )->addColumn(
            'item_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Name of item which is effected like product, user, order, etc'
        )->addColumn(
            'item_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Url of item if possible'
        )->addColumn(
            'is_revertable',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            '',
            ['nullable' => true, 'default' => 0],
            '0 = Not able revert activity, 1= Able to revert activity'
        )->addColumn(
            'revert_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false, 'default' => ''],
            'Name of admin user who reverted the activity'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'The date when the activity was created'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'The date when the activity was modified or reverted'
        )->addIndex(
            $installer->getIdxName('admin_user', ['user_id']),
            ['admin_id']
        )->addIndex(
            $installer->getIdxName('store', ['store_id']),
            ['store_id']
        )->setComment(
            'This is master table of admin activity extension'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'kiwicommerce_activity_log'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('kiwicommerce_activity_log')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Primary key for the Table'
        )->addColumn(
            'activity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => true, 'unsigned' => true, 'nullable' => false],
            'Entity id of kiwicommerce_activity table'
        )->addColumn(
            'field_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            ['nullable' => false, 'default' => ''],
            'Name of field which is effected'
        )->addColumn(
            'old_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '',
            ['nullable' => false, 'default' => ''],
            'Old value of field'
        )->addColumn(
            'new_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '',
            ['nullable' => false, 'default' => ''],
            'New value of field'
        )->addIndex(
            $installer->getIdxName('kiwicommerce_activity', ['entity_id']),
            ['activity_id']
        )->addForeignKey(
            $installer->getFkName(
                'kiwicommerce_activity_log',
                'activity_id',
                'kiwicommerce_activity',
                'entity_id'
            ),
            'activity_id',
            $installer->getTable('kiwicommerce_activity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'This is activity log table of admin activity extension'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'kiwicommerce_activity_detail'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('kiwicommerce_activity_detail')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Primary key for the Table'
        )->addColumn(
            'activity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false],
            'Entity id of kiwicommerce_activity table'
        )->addColumn(
            'model_class',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Name of field which is effected'
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['nullable' => true],
            'Old value of field'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            16,
            ['nullable' => false, 'default' => ''],
            'Status of revert process'
        )->addColumn(
            'response',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '',
            ['nullable' => false, 'default' => ''],
            'Error message faced during revert process'
        )->addIndex(
            $installer->getIdxName('kiwicommerce_activity', ['entity_id']),
            ['activity_id']
        )->addForeignKey(
            $installer->getFkName(
                'kiwicommerce_activity_detail',
                'activity_id',
                'kiwicommerce_activity',
                'entity_id'
            ),
            'activity_id',
            $installer->getTable('kiwicommerce_activity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'This is activity log details table of admin activity extension'
        );
        $installer->getConnection()->createTable($table);

        // Table creation
        $installer->endSetup();
    }
}
