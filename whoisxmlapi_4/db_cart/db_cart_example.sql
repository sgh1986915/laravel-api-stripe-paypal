CREATE TABLE `db_cart_example_customer` (
  `id` int(11) NOT NULL auto_increment,
  `cust_no` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `name2` varchar(50) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `address2` varchar(50) NOT NULL default '',
  `postal_code` varchar(20) NOT NULL default '',
  `place` varchar(35) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Example customer tabel to use with the db_cart class' AUTO_INCREMENT=2 ;

INSERT INTO `db_cart_example_customer` VALUES (1, 99, 'finalwebsites.com', 'web development', 'Hoofdstraat 1', 'Gebouw 12, 1te verdieping', '1000 AA', 'some place in the Netherlands', 'The Netherlands', 'someMail@gmail.com');

# --------------------------------------------------------

CREATE TABLE `db_cart_example_products` (
  `id` int(11) NOT NULL auto_increment,
  `art_no` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `price` decimal(6,2) NOT NULL default '0.00',
  `vat_perc` decimal(3,1) NOT NULL default '0.0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `art_no` (`art_no`)
) TYPE=MyISAM COMMENT='Example product tabel to use together with the db_cart class' AUTO_INCREMENT=4 ;

INSERT INTO `db_cart_example_products` VALUES (1, 15060600, 'GSM-Holder', 'exclusive telephone', '14.00', '0.0');
INSERT INTO `db_cart_example_products` VALUES (2, 15210100, 'Mirror set', 'to mount on spoiler only!', '16.00', '0.0');
INSERT INTO `db_cart_example_products` VALUES (3, 15003200, 'Passenger seat red', 'Also available in black ', '53.00', '0.0');

# --------------------------------------------------------

CREATE TABLE `db_cart_stock_article_example` (
  `id` int(11) NOT NULL auto_increment,
  `art_no` varchar(11) NOT NULL default '',
  `art_descr` varchar(30) NOT NULL default '',
  `amount` int(11) NOT NULL default '0',
  `price` decimal(6,2) NOT NULL default '0.00',
  `last_buy` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Example product table with onStock values' AUTO_INCREMENT=4 ;

INSERT INTO `db_cart_stock_article_example` VALUES (1, '04.20.43.00', 'Daytona BF-3', 12, '469.77', '2004-11-10');
INSERT INTO `db_cart_stock_article_example` VALUES (2, '15.00.10.00', 'Duostoel zwart Lux', 0, '34.57', '2004-11-10');
INSERT INTO `db_cart_stock_article_example` VALUES (3, '15.09.03.00', 'Freewheel', 40, '35.00', '2004-11-30');
