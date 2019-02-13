<?php
/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
$installer = $this;

/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();



$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('catalog_product','reqcoupon_status');

   /*create attribute for reqcoupon_status*/

 $setup->addAttribute('catalog_product', 'reqcoupon_status', array(
    'group' => 'General',
    'label' => 'Request Coupon Status',
    'type' => 'int',
    'input' => 'boolean',
    'default' => '',
    'class' => '',
    'backend' => '',
    'frontend' => '',
    'source' => 'eav/entity_attribute_source_boolean',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'unique' => false,
    'apply_to' => 'simple,virtual,grouped,configurable,bundle,downloadable',
));

 /* Create the new table to store customer details who send request for to coupon enabled product */
  $installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('reqforcoupon')};
CREATE TABLE {$this->getTable('reqforcoupon')} (
  `reqforcoupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `admin_reply` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`reqforcoupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
$installer->endSetup(); 