<?php

$sql = array();

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_lang`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_items`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_html`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_html_lang`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_link`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_link_lang`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_banner`';

$sql[] = 'DROP TABLE `'._DB_PREFIX_.'tmmegamenu_banner_lang`';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;
