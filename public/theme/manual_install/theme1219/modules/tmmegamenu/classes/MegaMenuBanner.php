<?php
if (!defined('_PS_VERSION_'))
	exit;

class MegaMenuBanner extends ObjectModel
{
	public $id_shop;
	public $specific_class;
	public $title;
	public $blank;
	public $url;
	public $image;

	public static $definition = array(
		'table' => 		'tmmegamenu_banner',
		'primary' => 	'id_item',
		'multilang'=>	true,
		'fields' => 	array(
			'id_shop' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'specific_class' => 	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 128),
			'title' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
			'blank' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'url' =>				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
			'image' =>				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
		),
	);

	public function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$res = parent::add($autodate, $null_values);
		return $res;
	}

	public function delete()
	{
		$res = true;

		$images = $this->image;
		foreach ($images as $image)
		{
			if ($image && file_exists(_PS_MODULE_DIR_.'tmmegamenu/images/'.$image))
				$res &= @unlink(_PS_MODULE_DIR_.'tmmegamenu/images/'.$image);
		}

		$res &= parent::delete();
		return $res;
	}

	/*****
	****** Get list of banners
	****** return all items data
	*****/
	public function getBannersList()
	{
		$sql = 'SELECT tmb.*, tmbl.`title`, tmbl.`url`, tmbl.`image`
				FROM `'._DB_PREFIX_.'tmmegamenu_banner` tmb
				LEFT JOIN `'._DB_PREFIX_.'tmmegamenu_banner_lang` tmbl
				ON (tmb.`id_item` = tmbl.`id_item`)
				WHERE tmb.`id_shop` = '.(int)Context::getContext()->shop->id.'
				AND tmbl.`id_lang` = '.(int)Context::getContext()->language->id;

		return Db::getInstance()->executeS($sql);
	}
}