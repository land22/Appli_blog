<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customer_tmsociallogin` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_customer` int(10) unsigned NOT NULL,
	`id_shop` int(11) NOT NULL DEFAULT \'1\',
	`social_id` varchar(50) NOT NULL,
	`social_type` varchar(50) NOT NULL,
    PRIMARY KEY  (`id`,`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;