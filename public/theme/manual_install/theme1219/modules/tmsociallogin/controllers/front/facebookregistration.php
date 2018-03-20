<?php

if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__).'/../../facebook/facebook.php');

class TMSocialLoginFacebookRegistrationModuleFrontController extends ModuleFrontController
{ 
	public function initContent()
	{
		parent::initContent();

		if ($this->context->customer->isLogged())
		{
			Tools::redirect('index.php?controller=my-account');
		}

		$facebookid = Configuration::get('TMSOCIALLOGIN_FAPPID');
		$facebookkey = Configuration::get('TMSOCIALLOGIN_FAPPSECRET');

		$this->redirect_uri = $this->context->link->getModuleLink('tmsociallogin', 'facebookregistration', array('done' => 1), TRUE, $this->context->language->id);

		$facebook = new Facebook(array(
			'appId'  => $facebookid,
			'secret' => $facebookkey,
		));

		$facebookuser = $facebook->getUser();

		if ($facebookuser)
		{
			try
			{
				$facebookprofile = $facebook->api('/me');
			}
			catch (FacebookApiException $e)
			{
				error_log($e);
				$facebookuser = null;
			}
		}
		else
		{
			Tools::redirect($facebook->getLoginUrl(array('scope' => 'email')));
		}

		if (isset($facebookprofile['id']))
		{
			if (Db::getInstance()->getValue('
				SELECT `id_customer`
				FROM `'._DB_PREFIX_.'customer_tmsociallogin`
				WHERE `social_id` = \''.$facebookprofile['id'].'\'
				AND `social_type` = \'facebook\'
			'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER)))
				Tools::redirect($this->context->link->getModuleLink('tmsociallogin', 'facebooklogin', array(), TRUE, $this->context->language->id));
		}

		if (Tools::getValue('done'))
		{
			$signed_request = $facebook->getSignedRequest($_REQUEST['signed_request']);

			$reg_metadata_fields = '[{"name":"name"},{"name":"first_name"},{"name":"last_name"},{"name":"email"},{"name":"birthday"},{"name":"gender"}]';

			$reg_metadata_fields_clean = preg_replace('/\s+/', '', $reg_metadata_fields);
			$signed_request_metadata_fields_clean = preg_replace('/\s+/', '', $signed_request['registration_metadata']['fields']);
			if (strcmp($reg_metadata_fields_clean,$signed_request_metadata_fields_clean) != 0)
				$this->errors[] = Tools::displayError('registration metadata fields not valid');
			$signed_request_email = trim($signed_request['registration']['email']);

			if (empty($signed_request_email))
				$this->errors[] = Tools::displayError('An email address required.');
			else if (!Validate::isEmail($signed_request_email))
				$this->errors[] = Tools::displayError('Invalid email address.');
			else if (Customer::customerExists($signed_request_email))
			{

				$customer = new Customer();
				$authentication = $customer->getByEmail($signed_request['registration']['email']);

				$facebook_id = Db::getInstance()->getValue('
					SELECT `social_id`
					FROM `'._DB_PREFIX_.'customer_tmsociallogin`
					WHERE `id_customer` = \''.(int)$customer->id.'\'
					AND `social_type` = \'facebook\'
				'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER));

				if($facebook_id)
				{
					if($facebook_id == (int)$facebookprofile['id'])
						Tools::redirect($this->context->link->getModuleLink('tmsociallogin', 'facebooklogin', array(), false, $this->context->language->id));
					else
						$this->errors[] = Tools::displayError('An error occurred while linking your Facebook account.');
				}
				else
				{
					if(Db::getInstance()->insert('customer_tmsociallogin',array( 'id_customer' => (int)$customer->id, 'social_id' => $facebookprofile['id'], 'social_type' => 'facebook')))
						$this->errors[] = Tools::displayError('an error occurred while linking your Facebook account.');

					$customer->active = 1;
					$customer->deleted = 0;
					$this->context->cookie->id_customer = intval($customer->id);
					$this->context->cookie->customer_lastname = $customer->lastname;
					$this->context->cookie->customer_firstname = $customer->firstname;
					$this->context->cookie->logged = 1;
					$this->context->cookie->passwd = $customer->passwd;
					$this->context->cookie->email = $customer->email;
					if (Configuration::get('PS_CART_FOLLOWING') AND (empty($this->context->cookie->id_cart) OR Cart::getNbProducts($this->context->cookie->id_cart) == 0))
						$this->context->cookie->id_cart = intval(Cart::lastNoneOrderedCart(intval($customer->id)));

					Module::hookExec('authentication');

					if (($back = Tools::getValue('back')) && $back == Tools::secureReferrer($back))
						Tools::redirect(html_entity_decode($back));
					// redirection: if cart is not empty : redirection to the cart
					if (count($this->context->cart->getProducts(true)) > 0)
						Tools::redirect('index.php?controller=order&multi-shipping='.(int)Tools::getValue('multi-shipping'));
					// else : redirection to the account
					else
						Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));
				}
			}

			if (!sizeof($this->errors))
			{
				$customer = new Customer();
				$customer_birthday = explode('/',$signed_request['registration']['birthday']);
				$customer->birthday = intval($customer_birthday[2]).'-'.intval($customer_birthday[0]).'-'.intval($customer_birthday[1]);
				if ($signed_request['registration']['last_name'] == "male")
					$_POST['id_gender'] = 1;
				else if ($signed_request['registration']['last_name'] == "female")
					$_POST['id_gender'] = 2;
				else
					$_POST['id_gender'] = 0;
				$_POST['lastname'] = $signed_request['registration']['last_name'];
				$_POST['firstname'] = $signed_request['registration']['first_name'];
				$_POST['passwd'] =  Tools::passwdGen();
				$_POST['email'] = $signed_request['registration']['email'];
				$this->errors = $customer->validateControler();

				if (!sizeof($this->errors))
				{
					$customer->active = 1;
					if (!$customer->add())
						$this->errors[] = Tools::displayError('an error occurred while creating your account');
					else
					{
						if(Db::getInstance()->insert('customer_tmsociallogin',array( 'id_customer' => (int)$customer->id, 'social_id' => $facebookprofile['id'], 'id_shop' => (int)$this->context->getContext()->shop->id, 'social_type' => 'facebook')))
							$this->errors[] = Tools::displayError('an error occurred while linking your Facebook account.');

						$this->context->smarty->assign('confirmation', 1);
						$this->context->cookie->id_customer = intval($customer->id);
						$this->context->cookie->customer_lastname = $customer->lastname;
						$this->context->cookie->customer_firstname = $customer->firstname;
						$this->context->cookie->passwd = $customer->passwd;
						$this->context->cookie->logged = 1;
						$this->context->cookie->email = $customer->email;

						Module::hookExec('createAccount', array(
							'_POST' => $_POST,
							'newCustomer' => $customer
						));

						if (($back = Tools::getValue('back')) && $back == Tools::secureReferrer($back))
							Tools::redirect(html_entity_decode($back));
							// redirection: if cart is not empty : redirection to the cart
						if (count($this->context->cart->getProducts(true)) > 0)
							Tools::redirect('index.php?controller=order&multi-shipping='.(int)Tools::getValue('multi-shipping'));
							// else : redirection to the account
						else
							Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));
					}
				}
			}
		}

		$useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;

		$this->context->smarty->assign(array(
			'redirect'     => $this->redirect_uri,
			'protocol' => ($useSSL) ? 'https://' : 'http://',
			'appid' => $facebookid,
		));

		$this->setTemplate('facebookregistration.tpl');
	}
}