<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmnewsletter` (
    `id_tmnewsletter` int(11) NOT NULL AUTO_INCREMENT,
	`id_guest` int(11) NOT NULL,
	`id_user` int(11) NOT NULL,
	`id_shop` int(11) NOT NULL,
	`status` int(1) NOT NULL,
    PRIMARY KEY  (`id_tmnewsletter`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;
