<?php
if (!defined('_PS_VERSION_'))
	exit;

class MegaMenuLink extends ObjectModel
{
	public $id_item;
	public $id_shop;
	public $specific_class;
	public $title;
	public $blank;
	public $url;

	public static $definition = array(
		'table' => 		'tmmegamenu_link',
		'primary' => 	'id_item',
		'multilang'=>	true,
		'fields' => 	array(
			'id_shop' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'specific_class' => 	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 128),
			'title' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
			'blank' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'url' =>				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
		),
	);

	public function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
	}

	/*****
	******	Add/update custom link
	*****/
	public function updateLink()
	{
		if (!($languages = Language::getLanguages(true)))
			return false;

		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		$id_shop = (int)Context::getContext()->shop->id;
		$specific_class = Tools::getValue('specific_class');
		$blank = Tools::getValue('blank');

		$data = array(
					'specific_class' => $specific_class,
					'blank' => $blank,
				);

		if (!Tools::getValue('id_item')) // if item not exist create new one
		{
			$data = array_merge($data, array('id_shop' => $id_shop));
			if (!Db::getInstance()->insert('tmmegamenu_link', $data) || !$id_item = Db::getInstance()->Insert_ID())
				return false;
			foreach ($languages as $language) // add multilanguage fields to tmmegamenu_link_lang table
			{
				if (!Tools::isEmpty(trim(Tools::getValue('title_'.$language['id_lang'])))) // item title is required, add default langage title to all language if missed title field
				{
					if (!Db::getInstance()->insert('tmmegamenu_link_lang', array(
						'id_item' => $id_item,
						'id_lang' => (int)$language['id_lang'],
						'title' => pSQL(Tools::getValue('title_'.$language['id_lang'])),
						'url' => Tools::getValue('url_'.$language['id_lang']),
					)))

					return false;
				}
				else
				{
					if (!Db::getInstance()->insert('tmmegamenu_link_lang', array(
						'id_item' => $id_item,
						'id_lang' => (int)$language['id_lang'],
						'title' => pSQL(Tools::getValue('title_'.$id_lang_default)),
						'url' => Tools::getValue('url_'.$id_lang_default),
					)))

					return false;
				}
			}
		}
		else // if item exist update it
		{
			$id_item = (int)Tools::getValue('id_item');
			if (!Db::getInstance()->update('tmmegamenu_link', $data, '`id_item` ='.$id_item.' AND `id_shop` ='.$id_shop))
				return false;
			foreach ($languages as $language)
			{
				if (!Tools::isEmpty(trim(Tools::getValue('title_'.$language['id_lang'])))) // item title is required, update langage title to all language if missed title field
				{
					if (!Db::getInstance()->update('tmmegamenu_link_lang', array(
						'title' => pSQL(Tools::getValue('title_'.$language['id_lang'])),
						'url' => Tools::getValue('url_'.$language['id_lang']),
					),
						'`id_item` = '.$id_item.'
						AND `id_lang` = '.(int)$language['id_lang']))

					return false;
				}
				else
				{
					if (!Db::getInstance()->update('tmmegamenu_link_lang', array(
						'title' => pSQL(Tools::getValue('title_'.$id_lang_default)),
						'url' => Tools::getValue('url_'.$id_lang_default),
					),
						'`id_item` = '.$id_item.'
						AND `id_lang` = '.(int)$language['id_lang']))

					return false;
				}
			}
		}

		return $id_item; // returns item id - to get just added/updated item
	}

	/*****
	****** Get all custom Links data for update
	****** $id_item = 0 if item id is undefined get it from POST
	******/
	public function getLink($id_item = 0)
	{
		$result = array();
		$languages = Language::getLanguages();
		if ($id_item)
			$id_item = $id_item;
		else
			$id_item = (int)Tools::getValue('id_item');
		$id_shop = (int)Context::getContext()->shop->id;
		$sql = 'SELECT *
				FROM '._DB_PREFIX_.'tmmegamenu_link
				WHERE `id_item` = '.$id_item.'
				AND `id_shop` = '.$id_shop;

		if (!$data = Db::getInstance()->executeS($sql))
			return false;
		foreach ($data as $res)
		{
			$result['id_item'] = $res['id_item'];
			$result['id_shop'] = $res['id_shop'];
			$result['specific_class'] = $res['specific_class'];
			$result['blank'] = $res['blank'];
		}
		// Get multilingual text
		foreach ($languages as $language)
		{
			$sql = 'SELECT `title`, `url`
				FROM '._DB_PREFIX_.'tmmegamenu_link_lang
				WHERE `id_item` = '.$id_item.'
				AND `id_lang` = '.$language['id_lang'];

			$data = Db::getInstance()->executeS($sql);
				foreach ($data as $text)
				{
					$result['title_'.$language['id_lang']] = $text['title'];
					$result['url_'.$language['id_lang']] = $text['url'];
				}
		}

		return $result;
	}

	/*****
	****** Get list of custom Links
	****** return all items data
	*****/
	public function getLinksList()
	{
		$sql = 'SELECT tml.*, tmll.`title`, tmll.`url`
				FROM `'._DB_PREFIX_.'tmmegamenu_link` tml
				LEFT JOIN `'._DB_PREFIX_.'tmmegamenu_link_lang` tmll
				ON (tml.`id_item` = tmll.`id_item`)
				WHERE tml.`id_shop` = '.(int)Context::getContext()->shop->id.'
				AND tmll.`id_lang` = '.(int)Context::getContext()->language->id;

		return Db::getInstance()->executeS($sql);
	}

	/*****
	****** Delete item and all related data
	*****/
	public function deleteLink()
	{
		$id_item = (int)Tools::getValue('id_item');
		$id_shop = (int)Context::getContext()->shop->id;

		if (!Db::getInstance()->delete('tmmegamenu_link', '`id_item` ='.$id_item.' AND `id_shop` = '.$id_shop) || !Db::getInstance()->delete('tmmegamenu_link_lang', '`id_item` ='.$id_item))
			return $this->displayError($this->l('Can\'t delete Link.'));
		return true;
	}
}