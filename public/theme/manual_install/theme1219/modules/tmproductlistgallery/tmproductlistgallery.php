<?php

if (!defined('_PS_VERSION_'))
	exit;

class Tmproductlistgallery extends Module
{

	public function __construct()
	{
		$this->name = 'tmproductlistgallery';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Template Monster (Alexander Grosul)';

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('TM Product List Gallery');
		$this->description = $this->l('Show all images of product on product listing');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('displayProductListGallery');
	}

	public function uninstall()
	{
		return parent::uninstall();
	}

	public function hookHeader()
	{
		$this->context->controller->addJS($this->_path.'/js/front.js');
		$this->context->controller->addCSS($this->_path.'/css/front.css');
	}

	public function hookDisplayProductListGallery($params)
	{
		$product = new Product($params['product']['id_product']);
		$this->smarty->assign(array(
			'product_images' => $product->getImages($this->context->language->id),
			'product' => $params['product'],
		));
		return $this->display(__FILE__, 'views/templates/hooks/tmproductlistgallery.tpl');
	}
}