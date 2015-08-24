<?php
/* @author  Armande Bayanes
 *
 * Note: If for some reason this script don't seem to be running,
 * delete the 'storecredit_setup' code in the `[prefix]core_resource` table.
 *
 * The reason is, you might have run this module before without the
 * MySQL installation scripts, settings, etc... yet.
 * */

// exit('StoreCredit MySQL Installation is running ...'); // Uncomment this line to debug if this script is actually running.

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('storecredit')};
CREATE TABLE {$this->getTable('storecredit')} (
  `storecredit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `credit_earned` float NOT NULL,
  `credit_remaining` float NOT NULL,
  `credit_spent` float NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`storecredit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('storecredit_history')};
CREATE TABLE {$this->getTable('storecredit_history')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `credit` float NOT NULL,
  `spent` float NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 