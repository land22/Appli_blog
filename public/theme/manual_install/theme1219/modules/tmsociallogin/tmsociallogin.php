<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/facebook/facebook.php');

class Tmsociallogin extends Module
{
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'tmsociallogin';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Template Monster (Alexander Grosul)';
		$this->need_instance = 0;

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Tm Social Login');
		$this->description = $this->l('Tm Social Login module');
		$this->confirmUninstall = $this->l('Are you sure that you want to delete all of your API\'s?');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		include(dirname(__FILE__).'/sql/install.php');
		require_once(dirname(__FILE__).'/facebook/facebook.php');

		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('displayCustomerAccountFormTop') &&
			$this->registerHook('displayCustomerAccount') &&
			$this->registerHook('displayHeaderLoginButtons') &&
			$this->registerHook('displaySocialLoginButtons') &&
			$this->registerHook('displayHeader');
	}

	public function uninstall()
	{
		include(dirname(__FILE__).'/sql/uninstall.php');

		if (!Configuration::deleteByName('TMSOCIALLOGIN_FSTATUS') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_FAPPID') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_FAPPSECRET') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_GSTATUS') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_GAPPID') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_GAPPSECRET') ||
			!Configuration::deleteByName('TMSOCIALLOGIN_GREDIRECT') ||
			!parent::uninstall())
			return false;

		return true;
	}

	public function getContent()
	{
		$output = '';
		if (!function_exists('curl_init'))
			$output .= $this->displayError($this->l('TM Social Login need the CURL PHP extension.'));
		if (!function_exists('json_decode'))
			$output .= $this->displayError($this->l('TM Social Login need the JSON PHP extension.'));
		if (!function_exists('hash_hmac'))
			$output .= $this->displayError($this->l('TM Social Login need the HMAC Hash (hash_hmac) PHP extension.'));

		$fbstatus = (int)Tools::getValue('TMSOCIALLOGIN_FSTATUS');
		$fbappid = trim(Tools::getValue('TMSOCIALLOGIN_FAPPID'));
		$fbappsecret = trim(Tools::getValue('TMSOCIALLOGIN_FAPPSECRET'));
		$gstatus = (int)Tools::getValue('TMSOCIALLOGIN_GSTATUS');
		$gappid = trim(Tools::getValue('TMSOCIALLOGIN_GAPPID'));
		$gappsecret = trim(Tools::getValue('TMSOCIALLOGIN_GAPPSECRET'));
		$gredirect = trim(Tools::getValue('TMSOCIALLOGIN_GREDIRECT'));

		if (Tools::isSubmit('submitTmsocialloginFacebookModule'))
		{
			if(($fbstatus && $fbstatus !=0) && (empty($fbappid) || empty($fbappsecret)))
				$output .= $this->displayError($this->l('Please fill all Facebook fields!'));
			else
			{
				$this->_postProcess();
				$output .= $this->displayConfirmation($this->l('Facebook Setting saved.'));
			}
		}
		if (Tools::isSubmit('submitTmsocialloginGoogleModule'))
			if(($gstatus && $gstatus !=0) && (empty($gappid) || empty($gappsecret) || empty($gredirect)))
				$output .= $this->displayError($this->l('Please fill all Google fields!'));
			else
			{
				$this->_postProcess1();
				$output .= $this->displayConfirmation($this->l('Google Setting saved.'));
			}

		$this->context->smarty->assign('module_dir', $this->_path);

		return $output.$this->renderFacebookForm().$this->renderGoogleForm();
	}

	protected function renderFacebookForm()
	{
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$helper->default_form_language = $this->context->language->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitTmsocialloginFacebookModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getFacebookConfigFormValues(), /* Add values for your inputs */
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);

		return $helper->generateForm(array($this->getFacebookConfigForm()));
	}

	protected function renderGoogleForm()
	{
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$helper->default_form_language = $this->context->language->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitTmsocialloginGoogleModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$helper->tpl_vars = array(
			'fields_value' => $this->getGoogleConfigFormValues(), /* Add values for your inputs */
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		);

		return $helper->generateForm(array($this->getGoogleConfigForm()));
	}

	protected function getFacebookConfigForm()
	{
		return array(
			'form' => array(
				'legend' => array(
				'title' => $this->l('Facebook Settings'),
				'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Use Facebook Login'),
						'name' => 'TMSOCIALLOGIN_FSTATUS',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'enable',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'disable',
								'value' => 0,
								'label' => $this->l('No')),
						),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'required' => true,
						'name' => 'TMSOCIALLOGIN_FAPPID',
						'label' => $this->l('App ID'),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'required' => true,
						'name' => 'TMSOCIALLOGIN_FAPPSECRET',
						'label' => $this->l('App Secret'),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			),
		);
	}
	
	protected function getGoogleConfigForm()
	{
		return array(
			'form' => array(
				'legend' => array(
				'title' => $this->l('Google Settings'),
				'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Use Google Login'),
						'name' => 'TMSOCIALLOGIN_GSTATUS',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'enable',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'disable',
								'value' => 0,
								'label' => $this->l('No')),
						),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'required' => true,
						'name' => 'TMSOCIALLOGIN_GAPPID',
						'label' => $this->l('App ID'),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'required' => true,
						'name' => 'TMSOCIALLOGIN_GAPPSECRET',
						'label' => $this->l('App Secret'),
					),
					array(
						'col' => 3,
						'type' => 'text',
						'required' => true,
						'name' => 'TMSOCIALLOGIN_GREDIRECT',
						'desc' => 'Your shop URL + index.php?fc=module&module=tmsociallogin&controller=googlelogin',
						'label' => $this->l('Redirect URIs'),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			),
		);	
	}

	protected function getFacebookConfigFormValues()
	{
		return array(
			'TMSOCIALLOGIN_FSTATUS' => Tools::getValue('TMSOCIALLOGIN_FSTATUS', Configuration::get('TMSOCIALLOGIN_FSTATUS')),
			'TMSOCIALLOGIN_FAPPID' => Tools::getValue('TMSOCIALLOGIN_FAPPID', Configuration::get('TMSOCIALLOGIN_FAPPID')),
			'TMSOCIALLOGIN_FAPPSECRET' => Tools::getValue('TMSOCIALLOGIN_FAPPSECRET', Configuration::get('TMSOCIALLOGIN_FAPPSECRET')),
		);
	}

	protected function getGoogleConfigFormValues()
	{
		return array(
			'TMSOCIALLOGIN_GSTATUS' => Tools::getValue('TMSOCIALLOGIN_GSTATUS', Configuration::get('TMSOCIALLOGIN_GSTATUS')),
			'TMSOCIALLOGIN_GAPPID' => Tools::getValue('TMSOCIALLOGIN_GAPPID', Configuration::get('TMSOCIALLOGIN_GAPPID')),
			'TMSOCIALLOGIN_GAPPSECRET' => Tools::getValue('TMSOCIALLOGIN_GAPPSECRET', Configuration::get('TMSOCIALLOGIN_GAPPSECRET')),
			'TMSOCIALLOGIN_GREDIRECT' => Tools::getValue('TMSOCIALLOGIN_GREDIRECT', Configuration::get('TMSOCIALLOGIN_GREDIRECT')),
		);
	}

	protected function _postProcess()
	{
		$form_values = $this->getFacebookConfigFormValues();

		foreach (array_keys($form_values) as $key)
			Configuration::updateValue($key, Tools::getValue($key));
	}
	protected function _postProcess1()
	{
		$form_values = $this->getGoogleConfigFormValues();

		foreach (array_keys($form_values) as $key)
			Configuration::updateValue($key, Tools::getValue($key));
	}

	public function hookHeader()
	{
		$this->context->controller->addJS($this->_path.'/views/js/front.js');
		$this->context->controller->addCSS($this->_path.'/views/css/front.css');
	}

	public function checksocial($type) {
		if($this->context->customer->id)
		{
			$customer_id = Db::getInstance()->getValue('
					SELECT `social_id`
					FROM `'._DB_PREFIX_.'customer_tmsociallogin`
					WHERE `social_type` = \''.$type.'\'
					AND `id_customer` = '.$this->context->customer->id.'
					AND `id_shop` = '.$this->context->shop->id
				);
			if($customer_id)
				return false;
			return true;
		}
		return false;
	}
	public function hookDisplayCustomerAccount()
	{
		$this->context->smarty->assign(array(
			'facebook_status' => $this->checksocial('facebook'),
			'google_status' => $this->checksocial('google'),
			'f_status' => (int)Configuration::get('TMSOCIALLOGIN_FSTATUS'),
			'g_status' => (int)Configuration::get('TMSOCIALLOGIN_GSTATUS')
		));
	
		return $this->display(__FILE__, 'customer-account.tpl');
	}

	public function hookDisplayCustomerAccountFormTop()
	{	
		$this->context->smarty->assign(array(
			'facebook_status' => $this->checksocial('facebook'),
			'google_status' => $this->checksocial('google'),
			'f_status' => (int)Configuration::get('TMSOCIALLOGIN_FSTATUS'),
			'g_status' => (int)Configuration::get('TMSOCIALLOGIN_GSTATUS')
		));
		
		return $this->display(__FILE__, 'customer-account-form-top.tpl');
	}
	public function hookDisplayHeaderLoginButtons()
	{
		$this->context->smarty->assign(array(
			'facebook_status' => $this->checksocial('facebook'),
			'google_status' => $this->checksocial('google'),
			'f_status' => (int)Configuration::get('TMSOCIALLOGIN_FSTATUS'),
			'g_status' => (int)Configuration::get('TMSOCIALLOGIN_GSTATUS')
		));

		return $this->display(__FILE__, 'header-account.tpl');
	}

	public function hookDisplaySocialLoginButtons()
	{
		$this->context->smarty->assign(array(
			'facebook_status' => $this->checksocial('facebook'),
			'google_status' => $this->checksocial('google'),
			'f_status' => (int)Configuration::get('TMSOCIALLOGIN_FSTATUS'),
			'g_status' => (int)Configuration::get('TMSOCIALLOGIN_GSTATUS')
		));

		return $this->display(__FILE__, 'social-login-buttons.tpl');
	}
}