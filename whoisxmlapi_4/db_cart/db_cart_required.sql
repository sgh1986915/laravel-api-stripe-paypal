

CREATE TABLE `db_cart_orders` (
  `id` bigint(11) NOT NULL auto_increment,
  `customer` varchar(20),
  `order_for` varchar(20),
  `order_date` timestamp NOT NULL default NOW(),
  `processed_on` timestamp,
  `status` varchar(5) NOT NULL default 'O',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;





CREATE TABLE `db_cart_rows` (
  `id` bigint(11) NOT NULL auto_increment,
  `order_id` bigint(11) NOT NULL default '0',
  `product_id` varchar(20) NOT NULL default '0',
  `product_name` varchar(100) NOT NULL default '',
  `product_type` varchar(10) NOT NULL,
  `price` decimal(6,2) NOT NULL default '0.00',
  `tax_perc` decimal(3,1),
  `quantity` int(11) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 ;





# --------------------------------------------------------

CREATE TABLE `db_cart_shipment` (
  `order_id` bigint(11) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `name2` varchar(50) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `address2` varchar(50) NOT NULL default '',
  `postal_code` varchar(10) NOT NULL default '',
  `place` varchar(50) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  PRIMARY KEY  (`order_id`)
) TYPE=InnoDB COMMENT='Table with shipment information';

INSERT INTO `db_cart_shipment` VALUES (1, 'finalwebsites.com', 'web development', 'Hoofdstraat 1', 'Gebouw 12, 1te verdieping', '1000 AA', 'some place in the Netherlands', 'The Netherlands', '');
