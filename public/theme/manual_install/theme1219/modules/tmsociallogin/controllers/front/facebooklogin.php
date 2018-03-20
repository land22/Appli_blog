<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/../../facebook/facebook.php');

class TMSocialLoginFacebookLoginModuleFrontController extends ModuleFrontController
{ 
	public function initContent()
	{
		parent::initContent();
 
		$facebookid = Configuration::get('TMSOCIALLOGIN_FAPPID');
		$facebookkey = Configuration::get('TMSOCIALLOGIN_FAPPSECRET');

		$this->login_url = $this->context->link->getModuleLink('tmsociallogin', 'facebooklogin', array(), TRUE, $this->context->language->id);

		$facebook = new Facebook(array(
			'appId'  => $facebookid,
			'secret' => $facebookkey,
		));

		$facebookuser = $facebook->getUser();

		if ($facebookuser)
		{
			try {
				$facebookprofile = $facebook->api('/me');
			} catch (FacebookApiException $e) {
				error_log($e);
				$facebookuser = null;
			}
		}
		else
		{
			Tools::redirect($facebook->getLoginUrl(array('scope' => 'email')));
		}

		if ($facebookuser)
		{
			$useremail = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT c.`email`
				FROM `'._DB_PREFIX_.'customer` c
				LEFT JOIN `'._DB_PREFIX_.'customer_tmsociallogin` ct ON ct.id_customer = c.id_customer
				WHERE ct.`social_id` = '.$facebookprofile['id'].'
				AND `social_type` = \'facebook\'
				'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER, 'c'));

			if (empty($useremail))
			{
				Tools::redirect($this->context->link->getModuleLink('tmsociallogin', 'facebookregistration', array('back' => Tools::getValue('back')), TRUE, $this->context->language->id));
			}
			else
			{
				$customer = new Customer();
				$authentication = $customer->getByEmail(trim($useremail));
				if (!$authentication || !$customer->id)
				{
					$this->errors[] = Tools::displayError('Error: Authentication failed.');
				}
				else
				{
					$this->context->cookie->id_compare = isset($this->context->cookie->id_compare) ? $this->context->cookie->id_compare: CompareProduct::getIdCompareByIdCustomer($customer->id);
					$this->context->cookie->id_customer = (int)($customer->id);
					$this->context->cookie->customer_lastname = $customer->lastname;
					$this->context->cookie->customer_firstname = $customer->firstname;
					$this->context->cookie->logged = 1;
					$customer->logged = 1;
					$this->context->cookie->is_guest = $customer->isGuest();
					$this->context->cookie->passwd = $customer->passwd;
					$this->context->cookie->email = $customer->email;

					$this->context->customer = $customer;

					if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id))
						$this->context->cart = new Cart($id_cart);
					else
					{
						$this->context->cart->id_carrier = 0;
						$this->context->cart->setDeliveryOption(null);
						$this->context->cart->id_address_delivery = Address::getFirstCustomerAddressId((int)($customer->id));
						$this->context->cart->id_address_invoice = Address::getFirstCustomerAddressId((int)($customer->id));
					}
					$this->context->cart->id_customer = (int)$customer->id;
					$this->context->cart->secure_key = $customer->secure_key;
					$this->context->cart->save();
					$this->context->cookie->id_cart = (int)$this->context->cart->id;
					$this->context->cookie->update();
					$this->context->cart->autosetProductAddress();

					Hook::exec('actionAuthentication');

					CartRule::autoRemoveFromCart($this->context);
					CartRule::autoAddToCart($this->context);

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

			$this->context->smarty->assign(array(
				'redirect_uri'     => urlencode($this->login_url),
				'facebook_appid' => $facebookid,
				'facebook_error' => $this->errors
			));

			$this->setTemplate('facebooklogin.tpl');
		}
		else
		{
			if(isset($_GET['error']) && isset($_GET['error_code']))
			{
				$msg = 'There was error while trying to get information from Facebook.';
				$msg .= '<br>'. $_GET['error'] .' - '. $_GET['error_code'] .' - '. $_GET['error_description'] .' - '. $_GET['error_reason'];

				$this->errors[] = Tools::displayError($msg);
				$this->setTemplate('facebooklogin.tpl');
			}
			else
			{
				Tools::redirect($facebook->getLoginUrl(array('scope' => 'email')));
			}
		}
	}
}