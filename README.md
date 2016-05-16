# magento-custom-discount-store-credit
Implementing Store Credit module and Custom Discounting in Magento.

<pre>CREATE TABLE IF NOT EXISTS `storecredit` (
  `storecredit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `credit_earned` float NOT NULL,
  `credit_remaining` float NOT NULL,
  `credit_spent` float NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`storecredit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `storecredit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `credit` float NOT NULL,
  `spent` float NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;</pre>
