<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once (dirname(__FILE__).'/classes/MegaMenu.php');
require_once (dirname(__FILE__).'/classes/MegaMenuHtml.php');
require_once (dirname(__FILE__).'/classes/MegaMenuLink.php');
require_once (dirname(__FILE__).'/classes/MegaMenuBanner.php');

class Tmmegamenu extends Module
{
	private $_menu = '';
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $spacer_size = '5';
	private $page_name = '';
	private $megamenu_items = '';
	private $user_groups;
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'tmmegamenu';
		$this->tab = 'front_office_features';
		$this->version = '0.0.1';
		$this->author = 'Template Monster (Alexander Grosul)';
		$this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
		$this->languages = Language::getLanguages();
		$this->need_instance = 0;

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('TM Mega Menu');
		$this->description = $this->l('Mega Menu by Template Monster');

		$this->confirmUninstall = $this->l('Are you sure that you want to delete all your info?');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		Configuration::updateValue('TMMEGAMENU_LIVE_MODE', false);

		include(dirname(__FILE__).'/sql/install.php');

		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('backOfficeHeader') &&
			$this->registerHook('displayTop');
	}

	public function uninstall()
	{
		Configuration::deleteByName('TMMEGAMENU_LIVE_MODE');

		include(dirname(__FILE__).'/sql/uninstall.php');

		return parent::uninstall();
	}

	public function getContent()
	{
		$output = '';
		$megamenu = new MegaMenu();
		$megamenuhtml = new MegaMenuHtml();
		$megamenulink = new MegaMenuLink();
		$megamenubanner = new MegaMenuBanner();
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');

		if (Tools::isSubmit('updateItem') || Tools::isSubmit('updateItemStay'))
		{
			if (Tools::isEmpty(trim(Tools::getValue('name_'.$id_lang_default))))
				$output .= $this->displayError($this->l('The tab name is required.'));
			else
			{
				$item_id = $megamenu->updateItem();
				$this->context->smarty->assign('item', $megamenu->getItem($item_id));
				if (!Tools::isSubmit('updateItemStay'))
					Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
				else
					Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&editItem&id_item='.$item_id);
			}
		}
		else if (Tools::getIsset('updateItemStatus'))
		{
			if (!$megamenu->changeItemStatus())
				$output .= $this->displayError($this->l('Can\'t update item status.'));
		}
		else if (Tools::getIsset('deleteItem'))
		{
			if (!$megamenu->deleteItem())
				$output .= $this->displayError($this->l('Can\'t delete item.'));
		}
		else if (Tools::getIsset('editItem'))
		{
			if (!$megamenu->getItem())
				$output .= $this->displayError($this->l('Can\'t load item.'));
			else
				$this->context->smarty->assign('item', $megamenu->getItem());
		}

		// Custom HTML manager
		if (Tools::isSubmit('updateHtml') || Tools::isSubmit('updateHtmlStay'))
		{
			if (Tools::isEmpty(trim(Tools::getValue('title_'.$id_lang_default))))
				$output .= $this->displayError($this->l('HTML item name is required.'));
			else
			{
				$html_id = $megamenuhtml->updateHtml();
				if (Tools::isSubmit('updateHtmlStay'))
					Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&editHtml&id_item='.$html_id);
			}
		}
		else if (Tools::getIsset('editHtml'))
		{
			if (!$megamenuhtml->getHtml())
				$output .= $this->displayError($this->l('Can\'t load HTML item.'));
		}
		else if (Tools::getIsset('deleteHtml'))
		{
			if (!$megamenuhtml->deleteHtml())
				$output .= $this->displayError($this->l('Can\'t delete HTML item.'));
		}

		// Custom Links manager
		if (Tools::isSubmit('updateLink') || Tools::isSubmit('updateLinkStay'))
		{
			if (Tools::isEmpty(trim(Tools::getValue('title_'.$id_lang_default))))
				$output .= $this->displayError($this->l('The Link name is required.'));
			else if (Tools::isEmpty(trim(Tools::getValue('url_'.$id_lang_default))))
				$output .= $this->displayError($this->l('The Link URL is required.'));
			else
			{
				$link_id = $megamenulink->updateLink();
				if (Tools::isSubmit('updateLinkStay'))
					Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&editLink&id_item='.$link_id);
			}
		}
		else if (Tools::getIsset('editLink'))
		{
			if (!$megamenulink->getLink())
				$output .= $this->displayError($this->l('Can\'t load Link.'));
		}
		else if (Tools::getIsset('deleteLink'))
		{
			if (!$megamenulink->deleteLink())
				$output .= $this->displayError($this->l('Can\'t delete Link.'));
		}
		// Banners manager
		if (Tools::isSubmit('updateBanner') || Tools::isSubmit('updateBannerStay'))
		{
			$errors = array();
			if (Tools::isEmpty(trim(Tools::getValue('title_'.$id_lang_default))))
				$output .= $this->displayError($this->l('The Banner name is required.'));
			else if (Tools::isEmpty(trim(Tools::getValue('url_'.$id_lang_default))))
				$output .= $this->displayError($this->l('The Banner URL is required.'));
			else if (Tools::getValue('image_'.$id_lang_default) != null && !Validate::isFileName(Tools::getValue('image_'.$id_lang_default)))
				$output .= $this->displayError($this->l('The Banner image is required.'));
			else
			{
				/* Sets ID if needed */
				if (Tools::getValue('id_item'))
				{
					$banner = new MegaMenuBanner((int)Tools::getValue('id_item'));
					if (!Validate::isLoadedObject($banner))
					{
						$output .= $this->displayError($this->l('Invalid banner ID'));
						return false;
					}
				}
				else
					$banner = new MegaMenuBanner();

				$banner->id_shop = (int)$this->context->shop->id;
				$banner->active = (int)Tools::getValue('active_slide');
				$banner->blank = (int)Tools::getValue('blank');
				$banner->specific_class = pSQL(Tools::getValue('specific_class'));

				/* Sets each langue fields */
				$languages = Language::getLanguages(false);

				foreach ($languages as $language)
				{
					$banner->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
					$banner->url[$language['id_lang']] = Tools::getValue('url_'.$language['id_lang']);

					/* Uploads image and sets banner */
					$type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
					$imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
					if (isset($_FILES['image_'.$language['id_lang']]) &&
						isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
						!empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
						!empty($imagesize) &&
						in_array(
							Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
								'jpg',
								'gif',
								'jpeg',
								'png'
							)
						) &&
						in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
					)
					{
						$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
						$salt = sha1(microtime());
						if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']]))
							$errors[] = $error;
						elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name))
							return false;
						elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.$salt.'_'.$_FILES['image_'.$language['id_lang']]['name'], null, null, $type))
							$errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
						if (isset($temp_name))
							@unlink($temp_name);
						$banner->image[$language['id_lang']] = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
					}
					elseif (Tools::getValue('image_old_'.$language['id_lang']) != '')
						$banner->image[$language['id_lang']] = Tools::getValue('image_old_'.$language['id_lang']);
				}

				/* Processes if no errors  */
				if (!$errors)
				{
					/* Adds */
					if (!Tools::getValue('id_item'))
					{
						if (!$banner->add())
							$errors[] = $this->displayError($this->l('The slide could not be added.'));
					}
					/* Update */
					elseif (!$banner->update())
						$errors[] = $this->displayError($this->l('The slide could not be updated.'));
					$this->clearCache();
				}

				if (Tools::isSubmit('updateBannerStay'))
					Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&editBanner&id_item='.$banner->id);
			}
		}
		else if (Tools::getIsset('deleteBanner'))
		{
			$banner = new MegaMenuBanner((int)Tools::getValue('id_item'));
			if (!$banner->delete())
				$output .= $this->displayError($this->l('Can\'t delete Banner.'));
		}

		$this->context->smarty->assign('module_dir', $this->_path);
		$this->context->smarty->assign('languages', $this->languages);
		$this->context->smarty->assign('default_language', $this->default_language);
		$this->context->smarty->assign('categTree', $this->initCategoriesQuery());
		$this->context->smarty->assign('cmsCatTree', $megamenu->getCMSCategories(true));
		$this->context->smarty->assign('tabs', $megamenu->getList());
		$this->context->smarty->assign('html_items', $megamenuhtml->getHtmlList());
		$this->context->smarty->assign('links', $megamenulink->getLinksList());
		$this->context->smarty->assign('banners', $megamenubanner->getBannersList());
		$this->context->smarty->assign('megamenu', $this->getMegamenuItems());
		$this->context->smarty->assign('image_baseurl', $this->_path.'images/');

		$this->context->smarty->assign('option_select', str_replace('\'', '\\\'', $this->renderChoicesSelect()));
		$this->context->smarty->assign('option_selected', $this->makeMenuOption());

		// buttons url
		$this->context->smarty->assign('url_enable', $this->context->link->getAdminLink('AdminModules', true).
			'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);

		$this->context->smarty->assign('branche_tpl_path', $this->local_path.'views/templates/admin/tree-branch.tpl');

		if (Tools::getIsset('addItem') || Tools::getIsset('editItem'))
			$output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/additem.tpl');
		else if (Tools::getIsset('addHtml') || Tools::getIsset('editHtml'))
			$output .= $this->renderAddHtml();
		else if (Tools::getIsset('addLink') || Tools::getIsset('editLink'))
			$output .= $this->renderAddLink();
		else if (Tools::getIsset('addBanner') || Tools::getIsset('editBanner'))
			$output .= $this->renderAddBanner();
		else
			$output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/list.tpl');

		return $output;
	}

	protected function initCategoriesQuery($id_category = false)
	{
		$megamenu = new MegaMenu();
		if (!$id_category)
			$from_category = Configuration::get('PS_HOME_CATEGORY');
		else
			$from_category = $id_category;
		$category = new Category($from_category, $this->context->language->id);

		$resultIds = array();
		$resultParents = array();

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT c.id_parent, c.id_category, c.level_depth, cl.name
		FROM `'._DB_PREFIX_.'category` c
		INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
		INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.') WHERE c.`active` = 1 ORDER BY `level_depth` ASC');

		foreach ($result as &$row)
		{
			$resultParents[$row['id_parent']][] = &$row;
			$resultIds[$row['id_category']] = &$row;
		}

		return $megamenu->getTree($resultParents, $resultIds, ($category ? $category->id : null));
	}

	public function renderChoicesSelect()
	{
		$megamenu = new MegaMenu();
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		if (!Tools::isEmpty(Tools::getValue('id_item')))
			$items = $megamenu->getMenuItem((int)Tools::getValue('id_item'));
		else
			$items = array();

		$html = '<select multiple="multiple" id="availableItems" class="availible_items">';
			$html .= '<optgroup label="'.$this->l('CMS').'">';
				$html .= $this->getCMSOptions(0, 1, $this->context->language->id, $items);
			$html .= '</optgroup>';

			$html .= '<optgroup label="'.$this->l('Supplier').'">';
	
				$html .= '<option value="ALLSUP0">'.$this->l('All suppliers').'</option>';
				$suppliers = Supplier::getSuppliers(false, $this->context->language->id);
				foreach ($suppliers as $supplier)
					$html .= '<option value="SUP'.$supplier['id_supplier'].'">'.$spacer.$supplier['name'].'</option>';
			$html .= '</optgroup>';

			$html .= '<optgroup label="'.$this->l('Manufacturer').'">';

				$html .= '<option value="ALLMAN0">'.$this->l('All manufacturers').'</option>';
				$manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id);
				foreach ($manufacturers as $manufacturer)
					$html .= '<option value="MAN'.$manufacturer['id_manufacturer'].'">'.$spacer.$manufacturer['name'].'</option>';
			$html .= '</optgroup>';

			$shop = new Shop((int)Shop::getContextShopID());
			$html .= '<optgroup label="'.$this->l('Categories').'">';

			$shops_to_get = Shop::getContextListShopID();

			foreach ($shops_to_get as $shop_id)
				$html .= $this->generateCategoriesOption($megamenu->customGetNestedCategories($shop_id, null, (int)$this->context->language->id, true), $items);
			$html .= '</optgroup>';

			if (Shop::isFeatureActive())
			{
				$html .= '<optgroup label="'.$this->l('Shops').'">';
					$shops = Shop::getShopsCollection();
					foreach ($shops as $shop)
					{
						if (!$shop->setUrl() && !$shop->getBaseURL())
							continue;
		
							$html .= '<option value="SHOP'.(int)$shop->id.'">'.$spacer.$shop->name.'</option>';
					}
				$html .= '</optgroup>';
			}


			$html .= '<optgroup label="'.$this->l('HTML').'">';
				$new_html = new MegaMenuHtml();
				foreach ($new_html = $new_html->getHtmlList() as $new)
						$html .= '<option value="HTML'.(int)$new['id_item'].'">'.$spacer.Tools::safeOutput($new['title']).'</option>';

			$html .= '<optgroup label="'.$this->l('Custom Links').'">';
				$links = new MegaMenuLink();
				foreach ($links = $links->getLinksList() as $link)
					$html .= '<option value="LNK'.(int)$link['id_item'].'">'.$spacer.Tools::safeOutput($link['title']).'</option>';

			$html .= '<optgroup label="'.$this->l('Banners').'">';
				$links = new MegaMenuBanner();
				foreach ($links->getBannersList() as $banner)
					$html .= '<option value="BNR'.(int)$banner['id_item'].'">'.$spacer.Tools::safeOutput($banner['title']).'</option>';

			$html .= '<optgroup label="'.$this->l('Products').'">';
				$html .= '<option value="PRODUCT" style="font-style:italic">'.$spacer.$this->l('Choose product ID (link)').'</option>';
				$html .= '<option value="PRODUCTINFO" style="font-style:italic">'.$spacer.$this->l('Choose product ID (info)').'</option>';
			$html .= '</optgroup>';

		$html .= '</select>';

		return $html;
	}

	protected function makeMenuOption($megamenuitem = '')
	{
		$megamenu = new MegaMenu();
		if (!Tools::isEmpty($megamenuitem))
			$menu_item = $megamenuitem;
		else if (Tools::getValue('id_item'))
			$menu_item = $megamenu->getMenuItem((int)Tools::getValue('id_item'));
		else
			$menu_item = array();

		$id_lang = (int)$this->context->language->id;

		if (!Tools::isEmpty($megamenuitem))
			$html = '<select multiple="multiple" name="col-item-items">';
		else
			$html = '<select multiple="multiple" name="simplemenu_items[]" id="simplemenu_items">';
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $values);
			$id = (int)Tools::substr($item, Tools::strlen($values[1]), Tools::strlen($item));

			switch (Tools::substr($item, 0, Tools::strlen($values[1])))
			{
				case 'CAT':
					$category = new Category((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				case 'PRD':
					$product = new Product((int)$id, true, (int)$id_lang);
					if (Validate::isLoadedObject($product))
						$html .= '<option selected="selected" value="PRD'.$id.'">'.$product->name.' (product link)</option>'.PHP_EOL;
					break;

				case 'PRDI':
					$product = new Product((int)$id, true, (int)$id_lang);
					if (Validate::isLoadedObject($product))
						$html .= '<option selected="selected" value="PRDI'.$id.'">'.$product->name.' (product info)</option>'.PHP_EOL;
					break;

				case 'CMS':
					$cms = new CMS((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($cms))
						$html .= '<option selected="selected" value="CMS'.$id.'">'.$cms->meta_title.'</option>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($category))
						$html .= '<option selected="selected" value="CMS_CAT'.$id.'">'.$category->name.'</option>'.PHP_EOL;
					break;

				case 'ALLMAN':
					$html .= '<option selected="selected" value="ALLMAN0">'.$this->l('All manufacturers').'</option>'.PHP_EOL;
					break;

				case 'MAN':
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($manufacturer))
						$html .= '<option selected="selected" value="MAN'.$id.'">'.$manufacturer->name.'</option>'.PHP_EOL;
					break;

				case 'ALLSUP':
					$html .= '<option selected="selected" value="ALLSUP0">'.$this->l('All suppliers').'</option>'.PHP_EOL;
					break;

				case 'SUP':
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (Validate::isLoadedObject($supplier))
						$html .= '<option selected="selected" value="SUP'.$id.'">'.$supplier->name.'</option>'.PHP_EOL;
					break;

				case 'SHOP':
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
						$html .= '<option selected="selected" value="SHOP'.(int)$id.'">'.$shop->name.'</option>'.PHP_EOL;
					break;

				case 'HTML':
					$new_html = new MegaMenuHtml();
					if (count($new_html = $new_html->getHtml((int)$id)) && $new_html)
						$html .= '<option selected="selected" value="HTML'.(int)$new_html['id_item'].'">'.Tools::safeOutput($new_html['title_'.$id_lang.'']).'</option>';
					break;
				case 'LNK':
					$link = new MegaMenuLink();
					if (count($link = $link->getLink((int)$id)) && $link)
						$html .= '<option selected="selected" value="LNK'.(int)$link['id_item'].'">'.Tools::safeOutput($link['title_'.$id_lang.'']).'</option>';
					break;
				case 'BNR':
					$banner = new MegaMenuBanner((int)$id);
					if (Validate::isLoadedObject($banner))
						$html .= '<option selected="selected" value="BNR'.(int)$banner->id.'">'.Tools::safeOutput($banner->title[$id_lang]).'</option>';
					break;
			}
		}

		return $html.'</select>';
	}

	protected function getCMSOptions($parent = 0, $depth = 1, $id_lang = false, $items_to_skip = null, $id_shop = false)
	{
		$megamenu = new MegaMenu();
		$html = '';
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$id_shop = ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;
		$categories = $megamenu->getCMSCategories(false, (int)$parent, (int)$id_lang, (int)$id_shop);
		$pages = $megamenu->getCMSPages((int)$parent, false, (int)$id_lang, (int)$id_shop);

		$spacer = str_repeat('&nbsp;', $this->spacer_size * (int)$depth);

		foreach ($categories as $category)
		{
			if (isset($items_to_skip) && !in_array('CMS_CAT'.$category['id_cms_category'], $items_to_skip))
				$html .= '<option value="CMS_CAT'.$category['id_cms_category'].'" style="font-weight: bold;">'.$spacer.$category['name'].'</option>';
			$html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $items_to_skip);
		}

		foreach ($pages as $page)
			if (isset($items_to_skip) && !in_array('CMS'.$page['id_cms'], $items_to_skip))
				$html .= '<option value="CMS'.$page['id_cms'].'">'.$spacer.$page['meta_title'].'</option>';

		return $html;
	}

	protected function generateCategoriesOption($categories, $items_to_skip = null)
	{
		$html = '';

		foreach ($categories as $category)
		{
			if (isset($items_to_skip))
			{
				$shop = (object)Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="CAT'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);

		}
		return $html;
	}

	protected function makeMenuTop()
	{
		$megamenu = new MegaMenu();
		if($topItems = $megamenu->getTopItems())
		{
			$this->_menu = '<ul class="menu clearfix">';
				foreach ($topItems as $top)
				{
					$simple_class = '';
					$badge = '';
	
					if ($top['is_simple'])
						$simple_class = ' simple';
					if ($top['badge'])
						$badge = '<span class="menu_badge">'.$top['badge'].'</span>';
					if (!$top['is_custom_url'])
						$top_item_url = $this->generateTopItemUrl($top['url']);
					else
						$top_item_url = array('url' =>$top['url'], 'selected' => '');
	
					$this->_menu .= '<li class="'.$top['specific_class'].$simple_class.$top_item_url['selected'].'">';
						if (!Tools::isEmpty($top_item_url['url']))
							$this->_menu .= '<a href="'.$top_item_url['url'].'">'.$top['title'].$badge.'</a>';
						else
							$this->_menu .= $top['title'];
	
					if (!$top['is_mega'])
					{
						$subitems = $megamenu->getMenuItem((int)$top['id_item'], 0, true);
						if ($subitems)
						{
							$this->_menu .= '<ul class="is-simplemenu">';
								$this->_menu .= $this->makeMenu($subitems);
							$this->_menu .= '</ul>';
						}
					}
					else
					{
						if ($rows = $megamenu->getMegamenuRow((int)$top['id_item']))
						{
							$this->_menu .= '<div class="is-megamenu">';
								foreach ($rows as $row)
								{
									$this->_menu .= '<div id="megamenu-row-'.$row.'" class="megamenu-row row megamenu-row-'.$row.'">';
										if ($cols = $megamenu->getMegamenuRowCols((int)$top['id_item'], $row))
										{
											$sp_class = '';
											foreach ($cols as $col)
											{
												if($col['class'])
													$sp_class = ' '.$col['class'];
												$this->_menu .= '<div id="column-'.$row.'-'.$col['col'].'" class="megamenu-col megamenu-col-'.$row.'-'.$col['col'].' col-sm-'.$col['width'].' '.$sp_class.'">';
													$this->_menu .= '<ul class="content">';
														$this->_menu .= $this->makeMenu(explode(',', $col['settings']));
													$this->_menu .= '</ul>';
												$this->_menu .= '</div>';
											}
										}
									$this->_menu .= '</div>';
								}
							$this->_menu .= '</div>';
						}
					}
					$this->_menu .= '</li>';
				}
			$this->_menu .= '</ul>';
		}
	}

	protected function makeMenu($subitems)
	{
		$id_lang = (int)$this->context->language->id;

		foreach ($subitems as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $value);
			$id = (int)Tools::substr($item, Tools::strlen($value[1]), Tools::strlen($item));

			switch (Tools::substr($item, 0, Tools::strlen($value[1])))
			{
				case 'CAT':
					$this->_menu .= $this->generateCategoriesMenu(Category::getNestedCategories($id, $id_lang, true, $this->user_groups));
					break;

				case 'PRD':
					$selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover product"' : ' class="product"';
					$product = new Product((int)$id, true, (int)$id_lang);
					if (!is_null($product->id))
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($product->getLink()).'" title="'.$product->name.'">'.$product->name.'</a></li>'.PHP_EOL;
					break;

				case 'PRDI':
					$selected = ($this->page_name == 'product' && (Tools::getValue('id_product') == $id)) ? ' class="sfHover product-info"' : ' class="product-info"';
					$product = new Product((int)$id, true, (int)$id_lang);
					if (!is_null($product->id))
						$this->_menu .= '<li'.$selected.'>'.$this->generateProductInfo($id).'</li>'.PHP_EOL;
					break;

				case 'CMS':
					$selected = ($this->page_name == 'cms' && (Tools::getValue('id_cms') == $id)) ? ' class="sfHover cms-page"' : ' class="cms-page"';
					$cms = CMS::getLinks((int)$id_lang, array($id));
					if (count($cms))
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($cms[0]['link']).'" title="'.Tools::safeOutput($cms[0]['meta_title']).'">'.Tools::safeOutput($cms[0]['meta_title']).'</a></li>'.PHP_EOL;
					break;

				case 'CMS_CAT':
					$category = new CMSCategory((int)$id, (int)$id_lang);
					$selected = ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms_category') == $category->id)) ? ' class="sfHoverForce cms-category"' : ' class="cms-category"';
					if (count($category))
					{
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($category->getLink()).'" title="'.$category->name.'">'.$category->name.'</a>';
							$this->getCMSMenuItems($category->id);
						$this->_menu .= '</li>'.PHP_EOL;
					}
					break;

				case 'ALLMAN':
					$link = new Link;
					$this->_menu .= '<li class="all-manufacturers"><a href="'.$link->getPageLink('manufacturer').'" title="'.$this->l('All manufacturers').'">'.$this->l('All manufacturers').'</a><ul>'.PHP_EOL;
					$manufacturers = Manufacturer::getManufacturers();
					foreach ($manufacturers as $manufacturer)
					{
						$selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_supplier') == (int)$manufacturer['id_manufacturer'])) ? ' class="sfHoverForce manufacturer"' : ' class="manufacturer"';
						$this->_menu .= '<li'.$selected.'><a href="'.$link->getManufacturerLink((int)$manufacturer['id_manufacturer'], $manufacturer['link_rewrite']).'" title="'.Tools::safeOutput($manufacturer['name']).'">'.Tools::safeOutput($manufacturer['name']).'</a></li>'.PHP_EOL;
					}
					$this->_menu .= '</ul>';
					break;

				case 'MAN':
					$selected = ($this->page_name == 'manufacturer' && (Tools::getValue('id_manufacturer') == $id)) ? ' class="sfHover manufacturer"' : ' class="manufacturer"';
					$manufacturer = new Manufacturer((int)$id, (int)$id_lang);
					if (!is_null($manufacturer->id))
					{
						if ((int)Configuration::get('PS_REWRITING_SETTINGS'))
							$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
						else
							$manufacturer->link_rewrite = 0;
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($link->getManufacturerLink((int)$id, $manufacturer->link_rewrite)).'" title="'.Tools::safeOutput($manufacturer->name).'">'.Tools::safeOutput($manufacturer->name).'</a></li>'.PHP_EOL;
					}
					break;

				case 'ALLSUP':
					$link = new Link;
					$this->_menu .= '<li class="all-suppliers"><a href="'.$link->getPageLink('supplier').'" title="'.$this->l('All suppliers').'">'.$this->l('All suppliers').'</a><ul>'.PHP_EOL;
					$suppliers = Supplier::getSuppliers();
					foreach ($suppliers as $supplier)
					{
						$selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == (int)$supplier['id_supplier'])) ? ' class="sfHoverForce supplier"' : ' class="supplier"';
						$this->_menu .= '<li'.$selected.'><a href="'.$link->getSupplierLink((int)$supplier['id_supplier'], $supplier['link_rewrite']).'" title="'.Tools::safeOutput($supplier['name']).'">'.Tools::safeOutput($supplier['name']).'</a></li>'.PHP_EOL;
					}
					$this->_menu .= '</ul>';
					break;

				case 'SUP':
					$selected = ($this->page_name == 'supplier' && (Tools::getValue('id_supplier') == $id)) ? ' class="sfHover supplier"' : ' class="supplier"';
					$supplier = new Supplier((int)$id, (int)$id_lang);
					if (!is_null($supplier->id))
					{
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($link->getSupplierLink((int)$id, $supplier->link_rewrite)).'" title="'.$supplier->name.'">'.$supplier->name.'</a></li>'.PHP_EOL;
					}
					break;

				case 'SHOP':
					$selected = ($this->page_name == 'index' && ($this->context->shop->id == $id)) ? ' class="sfHover shop"' : ' class="shop"';
					$shop = new Shop((int)$id);
					if (Validate::isLoadedObject($shop))
					{
						$link = new Link;
						$this->_menu .= '<li'.$selected.'><a href="'.Tools::HtmlEntitiesUTF8($shop->getBaseURL()).'" title="'.$shop->name.'">'.$shop->name.'</a></li>'.PHP_EOL;
					}
					break;

				case 'HTML':
					$this->_menu .= $this->generateCustomHtml($id);
					break;
				case 'LNK':
					$this->_menu .= $this->generateCustomLink($id);
					break;
				case 'BNR':
					$this->_menu .= $this->generateBanner($id);
					break;
			}
		}
	}

	/*****
	****** Get all categories items with nesting
	****** $categories = category id
	****** return: nested list with all categories
	*****/
	protected function generateCategoriesMenu($categories, $is_children = 0)
	{
		$html = '';

		foreach ($categories as $category)
		{
			if ($category['level_depth'] > 1)
			{
				$cat = new Category($category['id_category']);
				$link = Tools::HtmlEntitiesUTF8($cat->getLink());
			}
			else
				$link = $this->context->link->getPageLink('index');

			$html .= '<li'.(($this->page_name == 'category'
				&& (int)Tools::getValue('id_category') == (int)$category['id_category']) ? ' class="sfHoverForce category"' : ' class="category"').'>';
			$html .= '<a href="'.$link.'" title="'.$category['name'].'">'.$category['name'].'</a>';

			if (isset($category['children']) && !empty($category['children']))
			{
				$html .= '<ul>';
					$html .= $this->generateCategoriesMenu($category['children'], 1);
				$html .= '</ul>';
			}

			$html .= '</li>';
		}

		return $html;
	}

	/*****
	******	Generate top item URL by element code (`url`)
	******  return item url and active class if selected
	*****/
	public function generateTopItemUrl($url)
	{
		$link = new Link();
		preg_match($this->pattern, $url, $value);
		$id = (int)Tools::substr($url, Tools::strlen($value[1]), Tools::strlen($url));
		$type = Tools::substr($url, 0, Tools::strlen($value[1]));
		$selected = '';

		switch ($type)
		{
			case 'CAT':
				$url = $link->getCategoryLink($id);
				if($this->page_name == 'category' && (int)Tools::getValue('id_category') == $id)
					$selected = ' sfHoverForce';
			break;
			case 'CMS_CAT':
				$url = $link->getCMSCategoryLink($id);
				if($this->page_name == 'cms' && ((int)Tools::getValue('id_cms_category') == $id))
					$selected = ' sfHoverForce';
			break;
			case 'CMS':
				$url = $link->getCMSLink($id);
				if($this->page_name == 'cms' && Tools::getValue('id_cms')== $id)
					$selected = ' sfHoverForce';
			break;
		}

		return array('url' => $url, 'selected' => $selected);
	}

	/*****
	****** Get all cms items with nesting
	****** $parent = paretn category id
	****** $depth = depth level
	****** $id_lang - current lang
	****** return: nested list with all cms items (3 level max)
	*****/
	protected function getCMSMenuItems($parent, $depth = 1, $id_lang = false)
	{
		$megamenu = new MegaMenu();
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;

		if ($depth > 3)
			return;

		$categories = $megamenu->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $megamenu->getCMSPages((int)$parent);

		if (count($categories) || count($pages))
		{
			$this->_menu .= '<ul>';

			foreach ($categories as $category)
			{
				$cat = new CMSCategory((int)$category['id_cms_category'], (int)$id_lang);
				$selected = ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms_category') == $cat->id)) ? ' class="sfHoverForce cms-category"' : ' class="cms-category"';

				$this->_menu .= '<li '.$selected.'>';
				$this->_menu .= '<a href="'.Tools::HtmlEntitiesUTF8($cat->getLink()).'">'.$category['name'].'</a>';
				$this->getCMSMenuItems($category['id_cms_category'], (int)$depth + 1);
				$this->_menu .= '</li>';
			}

			foreach ($pages as $page)
			{
				$cms = new CMS($page['id_cms'], (int)$id_lang);
				$links = $cms->getLinks((int)$id_lang, array((int)$cms->id));

				$selected = ($this->page_name == 'cms' && ((int)Tools::getValue('id_cms') == $cms->id)) ? ' class="sfHoverForce cms-page"' : ' class="cms-page"';
				$this->_menu .= '<li '.$selected.'>';
					$this->_menu .= '<a href="'.$links[0]['link'].'">'.$cms->meta_title.'</a>';
				$this->_menu .= '</li>';
			}

			$this->_menu .= '</ul>';
		}
	}

	/****
	*****	Generating megamenu content in admin part
	****/
	protected function getMegamenuItems()
	{
		$megamenu_items = $this->megamenu_items;
		$megamenu = new MegaMenu();
		$id_item = (int)Tools::getValue('id_item');

		if (!$rows = $megamenu->getMegamenuRow($id_item)) // get rows for this megamenu
			return false;

		foreach ($rows as $row) // parse each row
		{
			$megamenu_items .= '<div id="megamenu-row-'.$row.'" class="megamenu-row row">';
			$megamenu_items .= '<div class="clearfix">';
				$megamenu_items .= '<div class="add-column-button-container col-lg-6">';
					$megamenu_items .= '<a class="btn btn-success add-megamenu-col" onclick="return false;" href="#">'.$this->l('Add column').'</a>';
				$megamenu_items .= '</div>';
				$megamenu_items .= '<div class="remove-row-button col-lg-6 text-right">';
					$megamenu_items .= '<a class="btn btn-danger btn-remove-row" onclick="return false;" href="#">'.$this->l('Remove row').'</a>';
				$megamenu_items .= '</div>';
			$megamenu_items .= '</div>';
			if (!$items = $megamenu->getMegamenuRowCols($id_item, $row)) // get columns for this megamenu row
				return false;
				$row_data = '';
				foreach ($items as $item) // generate each column for current row
				{
					$megamenu_items .= '<div id="column-'.$row.'-'.$item['col'].'" class="megamenu-col megamenu-col-'.$item['col'].' col-lg-'.$item['width'].'">';
						$megamenu_items .= '<div class="megamenu-col-inner">';
							$megamenu_items .= $this->classSelectGenerate((int)$item['width']);
							$megamenu_items .= '<div class="form-group">';
								$megamenu_items .= '<label>'.$this->l('Enter specific class').'</label>';
								$megamenu_items .= '<input class="form-control" type="text" name="col-item-class" value="'.$item['class'].'" />';
								$megamenu_items .= '<p class="help-block">'.$this->l('Can not contain special chars, only _ is allowed.(Will be automatically replaced)').'</p>';
							$megamenu_items .= '</div>';
							$megamenu_items .= '<div class="form-group">';
								$megamenu_items .= '<label>'.$this->l('Select content').'</label>';
								$megamenu_items .= $this->renderChoicesSelect();
							$megamenu_items .= '</div>';
							$megamenu_items .= '<div class="form-group buttons-group">';
								$megamenu_items .= '<a class="add-item-to-selected btn btn-default" onclick="return false;" href="#">'.$this->l('Add').'</a>';
								$megamenu_items .= '<a class="remove-item-from-selected btn btn-default" onclick="return false;" href="#">'.$this->l('Remove').'</a>';
							$megamenu_items .= '</div>';
							$megamenu_items .= '<div class="form-group">';
								$megamenu_items .= '<label>'.$this->l('Selected items').'</label>';
								$megamenu_items .= $this->makeMenuOption(explode(',', $item['settings']));
							$megamenu_items .= '</div>';
							$megamenu_items .= '<div class="remove-block-button">';
								$megamenu_items .= '<a href="#" class="btn btn-default btn-remove-column" onclick="return false;">'.$this->l('Remove block').'</a>';
							$megamenu_items .= '</div>';
						$megamenu_items .= '</div>';// set hidden data for jquery (each colmn)
						$megamenu_items .= '<input type="hidden" value="{col-'.$item['col'].'-'.$item['width'].'-('.$item['class'].')-'.$item['type'].'-['.$item['settings'].']}" name="col_content">';
					$megamenu_items .= '</div>';

					$row_data .= '{col-'.$item['col'].'-'.$item['width'].'-('.$item['class'].')-'.$item['type'].'-['.$item['settings'].']}'; // set hidden data for jquery (each colmns for row)
				}
			$megamenu_items .= '<input type="hidden" name="row_content" value="'.$row_data.'" />';
			$megamenu_items .= '</div>';
		}

		return $megamenu_items;
	}

	/*****
	****** Generate product info block by id
	****** $id_product = product ID
	****** return product info in html block
	*****/
	protected function generateProductInfo($id_product)
	{
		$output = '';
		$id_lang = $this->context->language->id;
		$product = new Product($id_product, true, (int)$id_lang);
		$images = $product->getImages($this->context->language->id);

		foreach ($images as $image)
		{
			if ($image['cover'])
			{
				$img = $product->id.'-'.$image['id_image'];
				break;
			}
		}

		$this->context->smarty->assign(array(
								'product' => $product,
								'image' => $img
								));

		$output .= $this->display($this->local_path, 'views/templates/hook/items/product.tpl');

		return $output;
	}

	/*****
	****** Generate custom HTML block by id_item
	****** $id_item = custom HTML ID
	****** return custom HTML block
	*****/
	protected function generateCustomHtml($id_item)
	{
		$output = '';
		$html = new MegaMenuHtml();
		$html = $html->getHtml($id_item);
		if ($html)
			$output .= '<li '.($html['specific_class']?'class="'.$html['specific_class'].' html"':'class="html"').'><h3>'.$html['title_'.(int)$this->context->language->id.''].'</h3>'.$html['content_'.(int)$this->context->language->id.''].'</li>';
		return $output;
	}

	/*****
	****** Generate custom Link by id_item
	****** $id_item = custom Link ID
	****** return custom Link element
	*****/
	protected function generateCustomLink($id_item)
	{
		$output = '';
		$html = new MegaMenuLink();
		$html = $html->getLink($id_item);
		if ($html)
			$output .= '<li '.($html['specific_class']?'class="'.$html['specific_class'].' custom-link"':'class="custom-link"').'><a '.($html['blank']?'target="_blank"':'').' href="'.$html['url_'.(int)$this->context->language->id.''].'">'.$html['title_'.(int)$this->context->language->id.''].'</a></li>';
		return $output;
	}

	/*****
	****** Generate Banner by id_item
	****** $id_item = Banner ID
	****** return custom Link element
	*****/
	protected function generateBanner($id_item)
	{
		$output = '';
		$id_lang = (int)$this->context->language->id;
		$html = new MegaMenuBanner($id_item);

		if ($html)
		{
			$this->context->smarty->assign('image_baseurl', $this->_path.'images/');
			$this->context->smarty->assign('banner', array(
										'id' => $html->id,
										'specific_class' => $html->specific_class,
										'title' => $html->title[$id_lang],
										'url' => $html->url[$id_lang],
										'image' => $html->image[$id_lang],
										'blank' => $html->blank
									));
			$output .= $this->display($this->local_path,'views/templates/hook/items/banner.tpl');
		}

		return $output;
	}

	/*****
	****** Generate select for width checking
	****** $width = current block width
	****** return: select with all width types and current selected
	*****/
	private function classSelectGenerate($width)
	{
		$output = '';
		$output .= '<div class="form-group">';
			$output .= '<label>'.$this->l('Select column width.').'</label>';
			$output .= '<select class="form-control" name="col-item-type">';
				for ($i = 1; $i < 13; $i++)
				{
					$selected = '';
					if ($width == $i)
						$selected = 'selected="selected"';
					$output .= '<option value="'.$i.'" '.$selected.'>col-'.$i.'</option>';
				}
			$output .= '</select>';
		$output .= '</div>';

		return $output;
	}

	/*****
	******	Generate form for Html blocks creating
	*****/
	private function renderAddHtml()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => (Tools::getIsset('editHtml') && (int)Tools::getValue('id_item') > 0)?$this->l('Update Html block'):$this->l('Add Html block'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Enter HTML item name'),
						'name' => 'title',
						'required' => true,
						'lang' => true,
						'col' => 3
					),
					array(
						'type' => 'text',
						'label' => $this->l('Specific class'),
						'name' => 'specific_class',
						'required' => false,
						'lang' => false,
						'col' => 3
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('HTML content'),
						'name' => 'content',
						'autoload_rte' => true,
						'lang' => true,
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'updateHtml',
				),
				'buttons' => array(
					array(
						'class' => 'btn btn-default pull-right',
						'icon' => 'process-icon-save',
						'title' => $this->l('Save & Stay'),
						'type' => 'submit',
						'name' => 'updateHtmlStay',
					),
				)
			),
		);
		if (Tools::getIsset('editHtml') && (int)Tools::getValue('id_item') > 0)
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => (int)Tools::getValue('id_item'));

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->show_cancel_button = true;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = 'id_item';
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getHtmlFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	/*****
	******	Fill Html blocks form fields
	*****/
	private function getHtmlFieldsValues()
	{
		if (Tools::getIsset('editHtml') && (int)Tools::getValue('id_item') > 0)
			$megamenuhtml  = new MegaMenuHtml((int)Tools::getValue('id_item'));
		else
			$megamenuhtml = new MegaMenuHtml();
		$fields_values = array(
			'id_item' => Tools::getValue('id_item'),
			'specific_class' => Tools::getValue('specific_class', $megamenuhtml->specific_class),
		);

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $megamenuhtml->title[$lang['id_lang']]);
			$fields_values['content'][$lang['id_lang']] = Tools::getValue('content_'.(int)$lang['id_lang'], $megamenuhtml->content[$lang['id_lang']]);
		}

		return $fields_values;
	}

	/*****
	******	Generate form for Links creating
	*****/
	private function renderAddLink()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => (Tools::getIsset('editLink') && (int)Tools::getValue('id_item') > 0)?$this->l('Update Link'):$this->l('Add new Link'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Enter Link name'),
						'name' => 'title',
						'required' => true,
						'lang' => true,
						'col' => 3
					),
					array(
						'type' => 'text',
						'label' => $this->l('Enter Link URL'),
						'name' => 'url',
						'required' => true,
						'lang' => true,
						'col' => 3
					),
					array(
						'type' => 'text',
						'label' => $this->l('Specific class'),
						'name' => 'specific_class',
						'required' => false,
						'lang' => false,
						'col' => 3
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Open in new window'),
						'name' => 'blank',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'updateLink',
				),
				'buttons' => array(
					array(
						'class' => 'btn btn-default pull-right',
						'icon' => 'process-icon-save',
						'title' => $this->l('Save & Stay'),
						'type' => 'submit',
						'name' => 'updateLinkStay',
					),
				)
			),
		);
		if (Tools::getIsset('editLink') && (int)Tools::getValue('id_item') > 0)
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => (int)Tools::getValue('id_item'));

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->show_cancel_button = true;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = 'id_item';
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getlinkFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	/*****
	******	Fill Links form fields
	*****/
	private function getLinkFieldsValues()
	{
		if (Tools::getIsset('editLink') && (int)Tools::getValue('id_item') > 0)
			$megamenulink  = new MegaMenuLink((int)Tools::getValue('id_item'));
		else
			$megamenulink = new MegaMenulink();
		$fields_values = array(
			'id_item' => Tools::getValue('id_item'),
			'specific_class' => Tools::getValue('specific_class', $megamenulink->specific_class),
			'blank' => Tools::getValue('blank', $megamenulink->blank),
		);

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $megamenulink->title[$lang['id_lang']]);
			$fields_values['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $megamenulink->url[$lang['id_lang']]);
		}

		return $fields_values;
	}

	/*****
	******	Generate form for Banners creating
	*****/
	private function renderAddBanner()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => (Tools::getIsset('editBanner') && (int)Tools::getValue('id_item') > 0)?$this->l('Update Banner'):$this->l('Add new Banner'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'files_lang',
						'label' => $this->l('Select a file'),
						'name' => 'image',
						'required' => true,
						'lang' => true,
						'desc' => sprintf($this->l('Maximum image size: %s.'), ini_get('upload_max_filesize'))
					),
					array(
						'type' => 'text',
						'label' => $this->l('Enter Banner name'),
						'name' => 'title',
						'required' => true,
						'lang' => true,
						'col' => 3
					),
					array(
						'type' => 'text',
						'label' => $this->l('Enter Banner URL'),
						'name' => 'url',
						'required' => true,
						'lang' => true,
						'col' => 3
					),
					array(
						'type' => 'text',
						'label' => $this->l('Specific class'),
						'name' => 'specific_class',
						'required' => false,
						'lang' => false,
						'col' => 3
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Open in new window'),
						'name' => 'blank',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'name' => 'updateBanner',
				),
				'buttons' => array(
					array(
						'class' => 'btn btn-default pull-right',
						'icon' => 'process-icon-save',
						'title' => $this->l('Save & Stay'),
						'type' => 'submit',
						'name' => 'updateBannerStay',
					),
				),
			),
		);
		if (Tools::getIsset('editBanner') && (int)Tools::getValue('id_item') > 0)
		{
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => (int)Tools::getValue('id_item'));
			$banner = new MegaMenuBanner((int)Tools::getValue('id_item'));
			$fields_form['form']['images'] = $banner->image;

			$has_picture = true;

			foreach (Language::getLanguages(false) as $lang)
				if (!isset($banner->images[$lang['id_lang']]))
					$has_picture &= false;

			if ($has_picture)
				$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
		}

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->show_cancel_button = true;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getBannerFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => $this->_path.'images/'
		);

		$helper->override_folder = '/';

		return $helper->generateForm(array($fields_form));
	}

	/*****
	******	Fill Banner form fields
	*****/
	private function getBannerFieldsValues()
	{
		if (Tools::getIsset('editBanner') && (int)Tools::getValue('id_item') > 0)
			$megamenubanner  = new MegaMenuBanner((int)Tools::getValue('id_item'));
		else
			$megamenubanner = new MegaMenuBanner();
		$fields_values = array(
			'id_item' => Tools::getValue('id_item'),
			'specific_class' => Tools::getValue('specific_class', $megamenubanner->specific_class),
			'blank' => Tools::getValue('blank', $megamenubanner->blank),
		);

		$languages = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['title'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $megamenubanner->title[$lang['id_lang']]);
			$fields_values['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $megamenubanner->url[$lang['id_lang']]);
			$fields_values['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang'], $megamenubanner->image[$lang['id_lang']]);
		}

		return $fields_values;
	}

	public function clearCache()
	{
		$this->_clearCache('menu.tpl');
	}
	public function hookBackOfficeHeader()
	{
		$this->context->controller->addJS($this->_path.'views/js/back.js');
		$this->context->controller->addCSS($this->_path.'views/css/back.css');
	}

	public function hookHeader()
	{
		$this->context->controller->addJS($this->_path.'views/js/hoverIntent.js');
		$this->context->controller->addJS($this->_path.'views/js/superfish.js');
		$this->context->controller->addJS($this->_path.'views/js/front.js');
		$this->context->controller->addCSS($this->_path.'views/css/front.css');
	}

	public function hookDisplayTop($params, $hook = 'top')
	{
		$this->user_groups = ($this->context->customer->isLogged() ?
			$this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
		$this->page_name = Dispatcher::getInstance()->getController();
		if (Tools::isEmpty($this->_menu))
			$this->makeMenuTop();
		$this->smarty->assign('MENU', $this->_menu);
		$this->smarty->assign('hook', $hook);

		return $this->display(__FILE__, 'views/templates/hook/menu.tpl');
	}

	public function hookLeftColumn()
	{
		return $this->hookDisplayTop(false, 'left_column');
	}

	public function hookRightColumn()
	{
		return $this->hookDisplayTop(false, 'right_column');
	}

	public function hookFooter()
	{
		return $this->hookDisplayTop(false, 'footer');
	}
}