<?php

class TmSearchSearchModuleFrontController extends ModuleFrontController
{
	private $ajax_search = '';
	public function initContent()
	{
		$this->ajax_search = Tools::getValue('ajaxSearch');
		$id_lang = Tools::getValue('id_lang');
		$query = Tools::replaceAccentedChars(urldecode(Tools::getValue('q')));
		$result = array();
		$i = 0;
		if ($this->ajax_search)
		{
			$search_results = Search::find((int)Tools::getValue('id_lang'), $query, 1, 10);
			if (is_array($search_results['result']))
				foreach ($search_results['result'] as &$product)
				{
					$usetax = (Product::getTaxCalculationMethod((int)Context::getContext()->customer->id) != PS_TAX_EXC);

					$pr = new Product($product['id_product']);
					$cat = new Category($pr->id_category_default);
					$images = $pr->getImages($id_lang);
					$manufacturer_name = Manufacturer::getNameById($pr->id_manufacturer);
					foreach ($images as $image)
					{
						if ($image['cover'])
						{
							$img_url = $pr->id.'-'.$image['id_image'];
							break;
						}
					}
					$result[$i]['name'] = $pr->name[$id_lang];
					$result[$i]['description_short'] = $pr->description_short[$id_lang];
					$result[$i]['category'] = $cat->name[$id_lang];
					$result[$i]['description_short'] = $pr->description_short[$id_lang];
					$result[$i]['product_link'] = $this->context->link->getProductLink($pr->id, $pr->link_rewrite, $cat->link_rewrite);
					$result[$i]['img_url'] = $this->context->link->getImageLink($pr->link_rewrite, $img_url, ImageType::getFormatedName('small'));
					$result[$i]['price'] = Tools::displayPrice($pr->getPriceStatic($pr->id, $usetax));
					$result[$i]['price_old'] = (Tools::displayPrice($pr->price) > Tools::displayPrice($pr->getPriceStatic($pr->id, $usetax)) ? Tools::displayPrice($pr->price) : '');
					$result[$i]['reference'] = ($pr->reference?$pr->reference:'');
					$result[$i]['manufacturer'] = ($manufacturer_name?$manufacturer_name:'');
					$i++;
				}

			$this->ajaxDie(Tools::jsonEncode($result));
		}

		parent::initContent();
	}
}