<?php

if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__).'/../../facebook/facebook.php');

class TMSocialLoginFacebookLinkModuleFrontController extends ModuleFrontController
{ 
	public function initContent()
	{
		parent::initContent();
 
		if (!$this->context->customer->isLogged())
		{
			$back = $this->context->link->getModuleLink('tmsociallogin', 'facebooklink', array(), TRUE, $this->context->language->id);
			Tools::redirect('index.php?controller=authentication&back='.urlencode($back));
		}

		$facebookid = Configuration::get('TMSOCIALLOGIN_FAPPID');
		$facebookkey = Configuration::get('TMSOCIALLOGIN_FAPPSECRET');

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
			Tools::redirect($facebook->getLoginUrl(array('scope' => 'email')));

		if (!$facebookuser || !$facebookprofile['id'])
			Tools::redirect($facebook->getLoginUrl(array('scope' => 'email')));

			$customer_id = Db::getInstance()->getValue('
				SELECT `id_customer`
				FROM `'._DB_PREFIX_.'customer_tmsociallogin`
				WHERE `social_id` = \''.$facebookprofile['id'].'\'
				AND `social_type` = \'facebook\'
				'
			);

			if ($customer_id > 0 && $customer_id != $this->context->customer->id)
			{
				$this->context->smarty->assign(array(
					'facebook_status' => 'error',
					'facebook_massage' => 'The Facebook account is already linked to another account.',
					'facebook_picture' => 'https://graph.facebook.com/'.$facebookprofile['id'].'/picture',
					'facebook_name' => $facebookprofile['name']
				));
			}
			else if ($customer_id == $this->context->customer->id)
			{
				$this->context->smarty->assign(array(
					'facebook_status' => 'linked',
					'facebook_massage' => 'The Facebook account is already linked to your account.',
					'facebook_picture' => 'https://graph.facebook.com/'.$facebookprofile['id'].'/picture',
					'facebook_name' => $facebookprofile['name'],
				));
			}
			else
			{
				$facebook_id = Db::getInstance()->getValue('
					SELECT `social_id`
					FROM `'._DB_PREFIX_.'customer_tmsociallogin`
					WHERE `id_customer` = \''.(int)$this->context->customer->id.'\'
					AND `social_type` = \'facebook\'
					AND `id_shop` = '.(int)$this->context->getContext()->shop->id);

				if(!$facebook_id)
				{
					Db::getInstance()->insert('customer_tmsociallogin',array( 'id_customer' => (int)$this->context->customer->id, 'social_id' => $facebookprofile['id'], 'social_type' => 'facebook'));
	
					$this->context->smarty->assign(array(
						'facebook_status' => 'confirm',
						'facebook_massage' => 'Your Facebook account has been linked to account.',
						'facebook_picture' => 'https://graph.facebook.com/'.$facebookprofile['id'].'/picture',
						'facebook_name' => $facebookprofile['name']
					));
				}
				else
				{
					$this->context->smarty->assign(array(
						'facebook_status' => 'error',
						'facebook_massage' => 'Sorry, unknown error..',
					));
				}
			}

		$this->setTemplate('facebooklink.tpl');
	}
}