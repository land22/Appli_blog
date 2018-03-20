<?php

if (!defined('_PS_VERSION_'))
	exit;

class Tmnewsletter extends Module
{
	protected $config_form = false;
	const GUEST_NOT_REGISTERED = -1;
	const CUSTOMER_NOT_REGISTERED = 0;
	const GUEST_REGISTERED = 1;
	const CUSTOMER_REGISTERED = 2;

	public function __construct()
	{
		$this->name = 'tmnewsletter';
		$this->tab = 'front_office_features';
		$this->version = '0.1.0';
		$this->author = 'Template Monster (Alexander Grosul)';
		$this->need_instance = 0;

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('TM Newsletter');
		$this->description = $this->l('Display newsletter subscription pop-up in the frontend');

		$this->confirmUninstall = $this->l('Are you sure you want to delete your details?');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		Configuration::updateValue('TMNEWSLETTER_LIVE_MODE', false);
		Configuration::updateValue('TMNW_VERIFICATION_EMAIL', 1);
		Configuration::updateValue('TMNEWSLETTER_GUEST_TIMEOUT', 1);
		Configuration::updateValue('TMNEWSLETTER_USER_TIMEOUT', 1);

		include(dirname(__FILE__).'/sql/install.php');

		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('backOfficeHeader');
	}

	public function uninstall()
	{
		Configuration::deleteByName('TMNEWSLETTER_LIVE_MODE');
		Configuration::deleteByName('TMNW_VERIFICATION_EMAIL');
		Configuration::deleteByName('TMNEWSLETTER_GUEST_TIMEOUT');
		Configuration::deleteByName('TMNEWSLETTER_USER_TIMEOUT');

		include(dirname(__FILE__).'/sql/uninstall.php');

		return parent::uninstall();
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitTmnewsletterModule'))
		{
			$this->_postProcess();
			$output .= $this->displayConfirmation($this->l('Settings saved.'));
		}

		$this->context->smarty->assign('module_dir', $this->_path);

		if ((int)Validate::isLoadedObject($module = Module::getInstanceByName('blocknewsletter')) && $module->isEnabledForShopContext())
			$output .= $this->renderForm();
		else
			$output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/require.tpl');

		return $output;
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
		$helper->submit_action = 'submitTmnewsletterModule';
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
						'label' => $this->l('Live mode'),
						'name' => 'TMNEWSLETTER_LIVE_MODE',
						'is_bool' => true,
						'desc' => $this->l('Use this module in live mode'),
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
						'label' => $this->l('Would you like to send a verification email after subscription?'),
						'name' => 'TMNW_VERIFICATION_EMAIL',
						'is_bool' => true,
						'desc' => $this->l('Use this module in live mode'),
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
						'desc' => $this->l('Enter a timeout for not registered users.'),
						'name' => 'TMNEWSLETTER_GUEST_TIMEOUT',
						'label' => $this->l('Timeout for guests.'),
						'suffix' => 'hour(s)'
					),
					array(
						'col' => 3,
						'type' => 'text',
						'desc' => $this->l('Enter a timeout for customers.'),
						'name' => 'TMNEWSLETTER_USER_TIMEOUT',
						'label' => $this->l('Timeout for customers.'),
						'suffix' => 'hour(s)'
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
			'TMNEWSLETTER_LIVE_MODE' => Configuration::get('TMNEWSLETTER_LIVE_MODE'),
			'TMNW_VERIFICATION_EMAIL' => Configuration::get('TMNW_VERIFICATION_EMAIL'),
			'TMNEWSLETTER_GUEST_TIMEOUT' => Configuration::get('TMNEWSLETTER_GUEST_TIMEOUT'),
			'TMNEWSLETTER_USER_TIMEOUT' => Configuration::get('TMNEWSLETTER_USER_TIMEOUT'),
		);
	}

	protected function _postProcess()
	{
		$form_values = $this->getConfigFormValues();

		foreach (array_keys($form_values) as $key)
			Configuration::updateValue($key, Tools::getValue($key));
	}

	public function checkUser()
	{
		if ($this->context->customer->isLogged())
		{
			if ($this->context->customer->newsletter)
				return 1;
			else
				return 2;
		}

		return 0;
	}

	public function getUserStatus($email)
	{
		$sql = 'SELECT `email`
				FROM '._DB_PREFIX_.'newsletter
				WHERE `email` = \''.pSQL($email).'\'
				AND id_shop = '.$this->context->shop->id;

		if (Db::getInstance()->getRow($sql))
			return self::GUEST_REGISTERED;

		$sql = 'SELECT `newsletter`
				FROM '._DB_PREFIX_.'customer
				WHERE `email` = \''.pSQL($email).'\'
				AND id_shop = '.$this->context->shop->id;

		if (!$registered = Db::getInstance()->getRow($sql))
			return self::GUEST_NOT_REGISTERED;

		if ($registered['newsletter'] == '1')
			return self::CUSTOMER_REGISTERED;

		return self::CUSTOMER_NOT_REGISTERED;
	}

	public function registerGuest($email, $active = true)
	{
		$sql = 'INSERT INTO '._DB_PREFIX_.'newsletter (id_shop, id_shop_group, email, newsletter_date_add, ip_registration_newsletter, http_referer, active)
				VALUES
				('.$this->context->shop->id.',
				'.$this->context->shop->id_shop_group.',
				\''.pSQL($email).'\',
				NOW(),
				\''.pSQL(Tools::getRemoteAddr()).'\',
				(
					SELECT c.http_referer
					FROM '._DB_PREFIX_.'connections c
					WHERE c.id_guest = '.(int)$this->context->customer->id.'
					ORDER BY c.date_add DESC LIMIT 1
				),
				'.(int)$active.'
				)';

		return Db::getInstance()->execute($sql);
	}

	public function registerUser($email)
	{
		$sql = 'UPDATE '._DB_PREFIX_.'customer
				SET `newsletter` = 1, newsletter_date_add = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\'
				WHERE `email` = \''.pSQL($email).'\'
				AND id_shop = '.$this->context->shop->id;

		return Db::getInstance()->execute($sql);
	}

	public function newsletterRegistration($email)
	{
		$register_status = $this->getUserStatus($email);
		if ($register_status > 0)
			return $this->error = $this->l('This email address is already registered.');

		if (!$this->isRegistered($register_status))
		{
			if (Configuration::get('TMNW_VERIFICATION_EMAIL'))
			{
				if ($register_status == self::GUEST_NOT_REGISTERED)
					$this->registerGuest($email, false);

				if (!$token = $this->getToken($email, $register_status))
					return $this->error = $this->l('An error occurred during the subscription process.');

				$this->sendVerificationEmail($email, $token);

				return $this->valid = $this->l('A verification email has been sent. Please check your inbox.');
			}
			else
			{
				if ($this->register($email, $register_status))
					return $this->valid = $this->l('You have successfully subscribed to this newsletter.');
				else
					return $this->error = $this->l('An error occurred during the subscription process.');
			}
		}
	}

	protected function isRegistered($register_status)
	{
		return in_array(
			$register_status,
			array(self::GUEST_REGISTERED, self::CUSTOMER_REGISTERED)
		);
	}

	protected function register($email, $register_status)
	{
		if ($register_status == self::GUEST_NOT_REGISTERED)
			return $this->registerGuest($email);

		if ($register_status == self::CUSTOMER_NOT_REGISTERED)
			return $this->registerUser($email);

		return false;
	}

	protected function getToken($email, $register_status)
	{
		if (in_array($register_status, array(self::GUEST_NOT_REGISTERED, self::GUEST_REGISTERED)))
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `newsletter_date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) as token
					FROM `'._DB_PREFIX_.'newsletter`
					WHERE `active` = 0
					AND `email` = \''.pSQL($email).'\'';
		}
		else if ($register_status == self::CUSTOMER_NOT_REGISTERED)
		{
			$sql = 'SELECT MD5(CONCAT( `email` , `date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\' )) as token
					FROM `'._DB_PREFIX_.'customer`
					WHERE `newsletter` = 0
					AND `email` = \''.pSQL($email).'\'';
		}

		return Db::getInstance()->getValue($sql);
	}

	protected function sendVerificationEmail($email, $token)
	{
		$verif_url = Context::getContext()->link->getModuleLink(
			'blocknewsletter', 'verification', array(
				'token' => $token,
			)
		);

		return Mail::Send($this->context->language->id, 'newsletter_verif', Mail::l('Email verification', $this->context->language->id), array('{verif_url}' => $verif_url), $email, null, null, null, null, null, _PS_MODULE_DIR_.'blocknewsletter/mails/', false, $this->context->shop->id);
	}

	public function updateDate($status)
	{
		$date_add = date('Y-m-d H:i:s');
		$context = Context::getContext();
		$context->cookie->__set('last_newsletter_showed', $date_add);
		if ($this->checkEntry())
			$this->updateEntry($status);
		else
			$this->addEntry($status);
	}

	private function checkEntry()
	{
		if ($this->context->customer->id)
			$sql = 'SELECT * 
					FROM '._DB_PREFIX_.'tmnewsletter 
					WHERE `id_shop`='.$this->context->shop->id.'
					AND `id_user`='.$this->context->customer->id;
		else
			$sql = 'SELECT * 
					FROM '._DB_PREFIX_.'tmnewsletter
					WHERE `id_shop`='.$this->context->shop->id.'
					AND `id_guest`='.$this->context->customer->id_guest;

		return Db::getInstance()->getRow($sql);
	}

	private function updateEntry($status)
	{
		if ($this->context->customer->id)
			$result = Db::getInstance()->update('tmnewsletter', array(
				'status' => (int)$status
				),
				'`id_user` = '.(int)$this->context->customer->id.'
				AND `id_shop` = '.(int)$this->context->shop->id);
		else
			$result = Db::getInstance()->update('tmnewsletter', array(
				'status' => (int)$status
				),
				'`id_guest` = '.(int)$this->context->customer->id_guest.'
				AND `id_shop` = '.(int)$this->context->shop->id);

		return $result;
	}

	private function addEntry($status)
	{
		if ($this->context->customer->id)
			$result = Db::getInstance()->insert('tmnewsletter', array(
				'id_user' => (int)$this->context->customer->id,
				'id_shop' => (int)$this->context->shop->id,
				'status' => (int)$status
			));
		else
			$result = Db::getInstance()->insert('tmnewsletter', array(
				'id_guest' => (int)$this->context->customer->id_guest,
				'id_shop' => (int)$this->context->shop->id,
				'status' => (int)$status
			));

		return $result;
	}

	private function checkLatestShow()
	{
		if ($this->context->customer->id)
		{
			$sql = 'SELECT `status`
					FROM '._DB_PREFIX_.'tmnewsletter
					WHERE `id_user` ='.(int)$this->context->customer->id;

			$result = Db::getInstance()->getRow($sql);

			if ($result['status'])
				return false;
			else if ($this->compareTime($this->context->cookie->last_newsletter_showed) > 0)
				return true;
			else
				return false;
		}
		else
		{
			$sql = 'SELECT `status`
					FROM '._DB_PREFIX_.'tmnewsletter
					WHERE `id_guest` ='.(int)$this->context->customer->id_guest;

			$result = Db::getInstance()->getRow($sql);

			if ($result['status'])
				return false;
			else if ($this->compareTime($this->context->cookie->last_newsletter_showed) > 0)
				return true;
			else
				return false;
		}
	}

	private function compareTime($time)
	{
		$cur_time = strtotime(date('Y-m-d H:i:s'));
		$last_showed_time = strtotime($time);
		$past_time = $cur_time - $last_showed_time;

		if ($this->context->customer->id)
			$timeout = Configuration::get('TMNEWSLETTER_USER_TIMEOUT');
		else
			$timeout = Configuration::get('TMNEWSLETTER_GUEST_TIMEOUT');

		return $past_time - $timeout * 3600;
	}

	public function hookHeader()
	{
		if ((int)Validate::isLoadedObject($module = Module::getInstanceByName('blocknewsletter')) && $module->isEnabledForShopContext() && Configuration::get('TMNEWSLETTER_LIVE_MODE'))
		{
			$this->context->controller->addJS($this->_path.'/views/js/front.js');
			$this->context->controller->addCSS($this->_path.'/views/css/front.css');

			$this->context->smarty->assign(array(
				'user_newsletter_status' => $this->checkUser(),
				'popup_status' => $this->checkLatestShow(),
				'module_url' => $this->context->link->getModuleLink('tmnewsletter')
			));

			return $this->display(__FILE__, 'tmnewsletter.tpl');
		}
	}
}