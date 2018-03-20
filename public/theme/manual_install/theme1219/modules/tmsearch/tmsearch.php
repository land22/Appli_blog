<?php

if (!defined('_PS_VERSION_'))
	exit;

class Tmsearch extends Module
{
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'tmsearch';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Template Monster (Alexander Grosul)';
		$this->need_instance = 0;

		$this->bootstrap = true;

		parent::__construct();

		$this->controllers = array('search');
		$this->displayName = $this->l('TM Search');
		$this->description = $this->l('Adds a quick search field to your website.');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('displayTop') &&
			Configuration::updateValue('PS_SEARCH_AJAX_IMAGE', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_DESCRIPTION', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_PRICE', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_REFERENCE', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_MANUFACTURER', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_LIMIT', true) &&
			Configuration::updateValue('PS_SEARCH_AJAX_LIMIT_NUM', 3) &&
			Configuration::updateValue('PS_SEARCH_AJAX_SCROLL', false) &&
			Configuration::updateValue('PS_SEARCH_AJAX_SCROLL_HEIGHT', 180);
	}

	public function uninstall()
	{
		return parent::uninstall() &&
				Configuration::deleteByName('PS_SEARCH_AJAX_IMAGE') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_DESCRIPTION') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_PRICE') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_REFERENCE') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_MANUFACTURER') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_LIMIT') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_LIMIT_NUM') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_SCROLL') &&
				Configuration::deleteByName('PS_SEARCH_AJAX_SCROLL_HEIGHT');
	}

	public function getContent()
	{
		if ((bool)Tools::isSubmit('submitTmsearchModule') == true)
			$this->postProcess();

		return $this->renderForm();
	}

	protected function renderForm()
	{
		$helper = new HelperForm();

		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$helper->default_form_language = $this->context->language->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitTmsearchModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFormValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);

		return $helper->generateForm(array($this->getConfigForm()));
	}

	protected function getConfigForm()
	{
		return array(
			'form' => array(
				'legend' => array(
				'title' => $this->l('Settings'),
				'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Enable Ajax Search'),
						'name' => 'PS_SEARCH_AJAX',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Enable Instant Search'),
						'name' => 'PS_INSTANT_SEARCH',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display image in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_IMAGE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display description in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_DESCRIPTION',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display prices in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_PRICE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display reference in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_REFERENCE',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display manufacturer in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_MANUFACTURER',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Maximum result show'),
						'name' => 'PS_SEARCH_AJAX_LIMIT',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'desc' => $this->l('Enter scroll max-height for Ajax search'),
						'name' => 'PS_SEARCH_AJAX_LIMIT_NUM',
						'label' => $this->l('The number of displayed results'),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Use scroll in Ajax search'),
						'name' => 'PS_SEARCH_AJAX_SCROLL',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => true,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => false,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'desc' => $this->l('Enter scroll max-height for Ajax search'),
						'name' => 'PS_SEARCH_AJAX_SCROLL_HEIGHT',
						'label' => $this->l('Scroll max-height'),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			),
		);
	}

	protected function getConfigFormValues()
	{
		return array(
			'PS_SEARCH_AJAX' => Tools::getValue('PS_SEARCH_AJAX', Configuration::get('PS_SEARCH_AJAX')),
			'PS_INSTANT_SEARCH' => Tools::getValue('PS_INSTANT_SEARCH', Configuration::get('PS_INSTANT_SEARCH')),
			'PS_SEARCH_AJAX_IMAGE' => Tools::getValue('PS_SEARCH_AJAX_IMAGE', Configuration::get('PS_SEARCH_AJAX_IMAGE')),
			'PS_SEARCH_AJAX_DESCRIPTION' => Tools::getValue('PS_SEARCH_AJAX_DESCRIPTION', Configuration::get('PS_SEARCH_AJAX_DESCRIPTION')),
			'PS_SEARCH_AJAX_PRICE' => Tools::getValue('PS_SEARCH_AJAX_PRICE', Configuration::get('PS_SEARCH_AJAX_PRICE')),
			'PS_SEARCH_AJAX_REFERENCE' => Tools::getValue('PS_SEARCH_AJAX_REFERENCE', Configuration::get('PS_SEARCH_AJAX_REFERENCE')),
			'PS_SEARCH_AJAX_MANUFACTURER' => Tools::getValue('PS_SEARCH_AJAX_MANUFACTURER', Configuration::get('PS_SEARCH_AJAX_MANUFACTURER')),
			'PS_SEARCH_AJAX_LIMIT' => Tools::getValue('PS_SEARCH_AJAX_LIMIT', Configuration::get('PS_SEARCH_AJAX_LIMIT')),
			'PS_SEARCH_AJAX_LIMIT_NUM' => Tools::getValue('PS_SEARCH_AJAX_LIMIT_NUM', Configuration::get('PS_SEARCH_AJAX_LIMIT_NUM')),
			'PS_SEARCH_AJAX_SCROLL' => Tools::getValue('PS_SEARCH_AJAX_SCROLL', Configuration::get('PS_SEARCH_AJAX_SCROLL')),
			'PS_SEARCH_AJAX_SCROLL_HEIGHT' => Tools::getValue('PS_SEARCH_AJAX_SCROLL_HEIGHT', Configuration::get('PS_SEARCH_AJAX_SCROLL_HEIGHT')),
		);
	}

	protected function postProcess()
	{
		$form_values = $this->getConfigFormValues();

		foreach (array_keys($form_values) as $key)
			Configuration::updateValue($key, Tools::getValue($key));
	}

	public function hookHeader()
	{
		$this->context->controller->addCSS($this->_path.'/views/css/tmsearch.css');

		if (Configuration::get('PS_SEARCH_AJAX'))
		{
			Media::addJsDef(array('search_url_local' => $this->context->link->getModuleLink('tmsearch', 'search')));
			Media::addJsDef(array('tmsearch_image' => (bool)Configuration::get('PS_SEARCH_AJAX_IMAGE')));
			Media::addJsDef(array('tmsearch_description' => (bool)Configuration::get('PS_SEARCH_AJAX_DESCRIPTION')));
			Media::addJsDef(array('tmsearch_price' => (bool)Configuration::get('PS_SEARCH_AJAX_PRICE')));
			Media::addJsDef(array('tmsearch_reference' => (bool)Configuration::get('PS_SEARCH_AJAX_REFERENCE')));
			Media::addJsDef(array('tmsearch_manufacturer' => (bool)Configuration::get('PS_SEARCH_AJAX_MANUFACTURER')));
			Media::addJsDef(array('tmsearch_limit' => (bool)Configuration::get('PS_SEARCH_AJAX_LIMIT')));
			Media::addJsDef(array('tmsearch_limit_num' => (int)Configuration::get('PS_SEARCH_AJAX_LIMIT_NUM')));
			Media::addJsDef(array('tmsearch_scroll' => (bool)Configuration::get('PS_SEARCH_AJAX_SCROLL')));
			Media::addJsDef(array('tmsearch_height' => (int)Configuration::get('PS_SEARCH_AJAX_SCROLL_HEIGHT')));
			$this->context->controller->addJqueryPlugin('autocomplete');
		}

		if (Configuration::get('PS_INSTANT_SEARCH'))
		{
			Media::addJsDef(array('search_url' => $this->context->link->getPageLink('search', Tools::usingSecureMode())));
			$this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
		}

		if (Configuration::get('PS_SEARCH_AJAX') || Configuration::get('PS_INSTANT_SEARCH'))
			$this->context->controller->addJS(($this->_path).'/views/js/tmsearch.js');
	}

	public function hookDisplayTop()
	{
		$key = $this->getCacheId('tmsearch');
		if (Tools::getValue('search_query') || !$this->isCached('tmsearch.tpl', $key))
		{
			$this->calculHookCommon();
			$this->smarty->assign(array(
				'search_query' => (string)Tools::getValue('search_query')
				)
			);
		}

		return $this->display(__FILE__, 'tmsearch.tpl', Tools::getValue('search_query') ? null : $key);
	}

	public function hookDisplayNav()
	{
		return $this->hookDisplayTop();
	}

	private function calculHookCommon()
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'ajaxsearch' =>		Configuration::get('PS_SEARCH_AJAX'),
			'instantsearch' =>	Configuration::get('PS_INSTANT_SEARCH'),
			'self' =>			dirname(__FILE__),
		));
		return true;
	}
}
