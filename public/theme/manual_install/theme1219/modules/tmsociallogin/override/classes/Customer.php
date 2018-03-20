<?php

class Customer extends CustomerCore
{
	public function logout()
	{
		if (file_exists(dirname(__FILE__).'/../../facebook/facebook.php'))
		{
			include(dirname(__FILE__).'/../../facebook/facebook.php');
			$facebook = new Facebook(array(
				'appId'  => Configuration::get('TMSOCIALLOGIN_APPID'),
				'secret' => Configuration::get('TMSOCIALLOGIN_APPSECRET'),
			));
			$facebook->destroySession();
		}

		parent::logout();
	}

	public function mylogout()
	{
		if (file_exists(dirname(__FILE__).'/../../facebook/facebook.php'))
		{
			include(dirname(__FILE__).'/../../facebook/facebook.php');
			$facebook = new Facebook(array(
				'appId'  => Configuration::get('TMSOCIALLOGIN_APPID'),
				'secret' => Configuration::get('TMSOCIALLOGIN_APPSECRET'),
			));
			$facebook->destroySession();
		}

		parent::mylogout();
	}
}
