<?php

$sql = array();

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'customer_tmsociallogin`';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;