CREATE TABLE IF NOT EXISTS `#__deallab_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deal_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(64) NOT NULL,
  `details` text,
  `notes` text,
  `amount` double NOT NULL,
  `currency` varchar(5) NOT NULL,
  `shipping` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `gateway` varchar(64) NOT NULL,
  `mail_sent` tinyint(1) NOT NULL DEFAULT '0',
  `cdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;