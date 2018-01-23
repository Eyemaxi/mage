<?php
/* @var Mage_Core_Model_Resource_Setup $installer */

$installer = $this;

$installer->startSetup();

/**
 * Create table 'linksgenerator/link'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('linksgenerator/link'))
    ->addColumn('link_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned' => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Link ID')
    ->addColumn('link_text', Varien_Db_Ddl_Table::TYPE_VARCHAR, 250, array(
        'nullable'  => false,
    ), 'Link Text')
    ->addColumn('link_state', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
    ), 'Link Status')
    ->addColumn('path_page', Varien_Db_Ddl_Table::TYPE_VARCHAR, 250, array(
        'nullable'  => false,
    ), 'Path to Page')
    ->setComment('Linksgenerator Link Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'linksgenerator/bind'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('linksgenerator/bind'))
    ->addColumn('bind_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'unsigned' => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Link_Page ID')
    ->addColumn('link_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
    ), 'Linksgenerator Link ID')
    ->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
    ), 'Cms Page ID')
    ->addForeignKey($installer->getFkName('linksgenerator/bind', 'link_id', 'linksgenerator/link', 'link_id'),
        'link_id', $installer->getTable('linksgenerator/link'), 'link_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('linksgenerator/bind', 'page_id', 'cms/page', 'page_id'),
        'page_id', $installer->getTable('cms/page'), 'page_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Linksgenerator Binder Links and Pages');
$installer->getConnection()->createTable($table);

/*  using method run() for MySQL server */
/*
$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('linksgenerator/link')}` (
  `link_id` int(11) NOT NULL,
  `link_text` varchar(500) NOT NULL,
  `link_state` tinyint(1) NOT NULL,
  `path_page` varchar(500) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");
*/

$installer->endSetup();