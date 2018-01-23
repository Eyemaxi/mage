<?php

//$installer = $this;
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');

/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('customer', 'bonuspayment');

$entityTypeId = $setup->getEntityTypeId('customer');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute('customer', 'bonuspayment', array(
    'backend'      => '',
    'label'        => 'Bonus account',
    'visible'      => true,
    'required'     => false,
    'type'         => 'int',
    'input'        => 'text',
    'source'       => '',
    'default'      => '',
    'frontend'     => '',
    'unique'       => false,
    'note'         => 'Custom Attribute'
));

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'bonuspayment');

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'bonuspayment',
    '999'
);

$used_in_forms[]='adminhtml_customer';
$attribute->setData('used_in_forms', $used_in_forms)
    ->setData('is_used_for_customer_segment', true)
    ->setData('is_system', 0)
    ->setData('is_user_defined', 1)
    ->setData('is_visible', 1)
    ->setData('sort_order', 100);
$attribute->save();

$table = $installer->getTable('eav/attribute');
$installer->getConnection()
    ->addColumn($table,'bonuspayment', array(
        'type'               => 'int',
        'label'              => 'Bonus account',
        'input'              => 'select',
        'source'             => 'eav/entity_attribute_source_table',
        'required'           => false,
        'sort_order'         => 120,
        'visible'            => false,
        'system'             => false,
        'validate_rules'     => 'a:0:{}',
        'position'           => 120,
        'admin_checkout'     => 1
    ));

$installer->endSetup();