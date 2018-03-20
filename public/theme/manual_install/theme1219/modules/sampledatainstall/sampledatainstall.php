<?php
set_time_limit(600);
if (!defined('_PS_VERSION_'))
	exit;

class SampleDataInstall extends Module
{

	private $_html = '';
	private $_shop_name;
	private $_shop_email;
	private $_shop_domain;
	private $_shop_domain_ssl;
	private $_shop_db_version;
	private $_shop_install_version;
	private $version_cell = -1;

	
	public function __construct()
	{
		$this->name = 'sampledatainstall';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'TemplateMonster';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Sample Data Install');
		$this->description = $this->l('Imports sample data to make your theme look like at template live demo. Imports sample products, categories, module settings, static pages etc.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		if (!parent::install() || 
			!$this->registerHook('displayBackOfficeHeader') || 
			!Configuration::updateValue('SDI_SHOW_MASSEGE', 1))

			return false;

		return true;
	}

	public function hookdisplayBackOfficeHeader()
	{
		$show_massege = 0;
		if (Configuration::get('SDI_SHOW_MASSEGE') == 1)
			$show_massege = 1;

		// CSS
		$this->context->controller->addCss($this->_path.'css/styles.css');
		// JS
		$this->context->controller->addJS(Media::getJqueryPath(), false);
		$this->context->controller->addJS($this->_path.'js/tab.js');
		$this->context->controller->addJS($this->_path.'js/import.js');

		$this->context->smarty->assign('vars', array(
			'moduleSDIUrl' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'baseDir'        => $this->_path.'sampledatainstall-ajax.php',
			'show_message' => $show_massege
		));
		return $this->display(__FILE__, 'views/templates/admin/admin-header.tpl');
	}

	public function uninstall()
	{
		if (!parent::uninstall() || 
			!Configuration::deleteByName('SDI_SHOW_MASSEGE'))
			return false;
		return true;
	}

	public function removeMessage()
	{
		Configuration::updateValue('SDI_SHOW_MASSEGE', 0);	
	}

	public function _filesUpload($path)
	{
		/*** upload files from local storage ***/
		if (array_key_exists('file', $_FILES))
		{
			$file_name = basename($_FILES['file']['name']);
			$file_arr = explode('.', $file_name);
			$file_ext = $file_arr[(count($file_arr) - 1)]; // file extension
			$file_name = $file_arr['0'].'.'.strrev($file_ext);

			if (strrev($file_ext) == 'sql' || $file_ext == 'rev')
				$upload_file = $path.$file_name;
			else
			{
				
				$path_tmp = explode('@', $file_name);
				$file_insert_path = str_replace('\\', '/', _PS_ROOT_DIR_.'/'.str_replace('#', '/', $path_tmp[0]).'/');
				$file_name = $path_tmp[1];
				// check for path folder
				if (!is_dir($file_insert_path))
					@mkdir($file_insert_path, 0777, true);

				$upload_file = $file_insert_path.$file_name;
			}

			if(!move_uploaded_file($_FILES['file']['tmp_name'], $upload_file))
				die(Tools::jsonEncode(array('error_status' => 'File Upload Fail')));
			die(Tools::jsonEncode(array('success_status' => 'File Upload Success!', 'error' => false)));
		}
	}
	
	public function _installSample()
	{
		if (!$this->checkSampleDataVersion())
			exit('not_suported_version');

		$this->_getDefaultSettings();
		/*** install sample data if isset dump.sql ***/

		$sample_folder = _PS_MODULE_DIR_.$this->name.'/import';
		// read dir
		if (is_dir($sample_folder))
		{
			$files = scandir($sample_folder);
			foreach ($files as $file) 
			{
				// check for bad file name
				if ($file != '.' && $file != '..')
				{
					//check for .sql file
					$file_arr = explode('.', $file);
					// get file extension
					if ($file_arr[(count($file_arr) - 1)] == 'sql')
						{
							/*** install dump.sql ***/
		
							$lines = file($sample_folder.'/'.$file);
							// Temporary variable, used to store current query
							$templine = '';
							// Loop through each line
				
							foreach ($lines as $line)
							{
								// Skip it if it's a comment
								if (Tools::substr($line, 0, 2) == '--' || Tools::substr($line, 0, 2) == '/*' || $line == '')
									continue;
				
								// Add this line to the current segment
								$templine .= $line;
								// If it has a semicolon at the end, it's the end of the query
								if (Tools::substr(trim($line), -1, 1) == ';')
								{
									// Perform the query
									Db::getInstance()->execute($templine);
									// Reset temp variable to empty
									$templine = '';
								}
							}
						}
					}	
			}
		}
		exit('import_end');
	}

	protected function checkSampleDataVersion()
	{
		if (!$result = $this->getVersionFromFile())
			$version = $this->getVersionFromDump();
		else
			$version = $result;

		if ($version != Configuration::get('PS_VERSION_DB'))
			return false;
		return true;
	}

	protected function getVersionFromFile()
	{
		$ver_file = _PS_MODULE_DIR_.$this->name.'/import/version.ver';

		if(file_exists($ver_file))
		{
			$version = trim(file_get_contents($ver_file));
			return $version;
		}

		return false;
	}

	protected function getVersionFromDump()
	{
		$sql_version = '';
		$sample_folder = _PS_MODULE_DIR_.$this->name.'/import';
		// read dir
		if (is_dir($sample_folder))
		{
			$files = scandir($sample_folder);
			foreach ($files as $file) 
			{
				// check for bad file name
				if ($file != '.' && $file != '..')
				{
					//check for .sql file
					$file_arr = explode('.', $file);
					// get file extension
					if ($file_arr[(count($file_arr) - 1)] == 'sql')
						{
							/*** install dump.sql ***/
		
							$lines = file($sample_folder.'/'.$file);
							// Temporary variable, used to store current query
							$templine = '';
							// Loop through each line
				
							foreach ($lines as $line)
							{
								// Skip it if it's a comment
								if (Tools::substr($line, 0, 2) == '--' || Tools::substr($line, 0, 2) == '/*' || $line == '')
									continue;
				
								// Add this line to the current segment
								$templine .= $line;
								// If it has a semicolon at the end, it's the end of the query
								if (Tools::substr(trim($line), -1, 1) == ';')
								{
									$i = 0;
									// searching for configuration table
									if (strpos($templine, 'CREATE TABLE `ps_configuration`') !== false)
									{
										$lines = explode(',', $templine);
										foreach ($lines as $line)
										{
											$i++;
											if (strpos($line, 'value'))
												$this->version_cell = $this->version_cell + $i;
										}
									}
									
									// searching for configuration table version db field
									if (strpos($templine, 'PS_VERSION_DB') !== false)
									{
										$need_line = '';
										$temp_lines = explode('),', $templine);
										foreach ($temp_lines as $line)
										{
											if(strpos($line, 'PS_VERSION_DB'))
											{
												$need_line = explode(',', $line);
											}
										}
										$sql_version = str_replace('\'', '', $need_line[$this->version_cell]);
										
									}
									$templine = '';
								}
							}
						}
					}	
			}
		}

		return $sql_version;
	}

	public function _getDefaultSettings()
	{
		$this->_shop_name = Configuration::get('PS_SHOP_NAME');
		$this->_shop_email = Configuration::get('PS_SHOP_EMAIL');
		$this->_shop_domain = Configuration::get('PS_SHOP_DOMAIN');
		$this->_shop_domain_ssl = Configuration::get('PS_SHOP_DOMAIN_SSL');
		$this->_shop_db_version = Configuration::get('PS_VERSION_DB');
		$this->_shop_install_version = Configuration::get('PS_INSTALL_VERSION');
	
		$res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'temp_config` (
			  `id_row` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `shop_name` varchar(255) NOT NULL,
			  `shop_email` varchar(255) NOT NULL,
			  `shop_domain` varchar(255) NOT NULL,
			  `shop_domain_ssl` varchar(255) NOT NULL,
			  `shop_db_version` varchar(255) NOT NULL,
			  `shop_install_version` varchar(255) NOT NULL,
			  PRIMARY KEY (`id_row`,`shop_name`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		
		if ($res)
			Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'temp_config` ( 
				`shop_name`,`shop_email`, `shop_domain`, `shop_domain_ssl`, `shop_db_version`, `shop_install_version`
			) 
			VALUES
			(
				\''.$this->_shop_name.'\',
				\''.$this->_shop_email.'\',
				\''.$this->_shop_domain.'\',
				\''.$this->_shop_domain_ssl.'\',
				\''.$this->_shop_db_version.'\',
				\''.$this->_shop_install_version.'\'
				)'
			);
	}

	public function _setDefaultSettings()
	{
		if (Configuration::updateValue('PS_SHOP_NAME', Db::getInstance()->getValue('SELECT shop_name FROM '._DB_PREFIX_.'temp_config')) &&
			Configuration::updateValue('PS_SHOP_EMAIL', Db::getInstance()->getValue('SELECT shop_email FROM '._DB_PREFIX_.'temp_config')) &&
			Configuration::updateValue('PS_SHOP_DOMAIN', Db::getInstance()->getValue('SELECT shop_domain FROM '._DB_PREFIX_.'temp_config')) &&
			Configuration::updateValue('PS_SHOP_DOMAIN_SSL', Db::getInstance()->getValue('SELECT shop_domain_ssl FROM '._DB_PREFIX_.'temp_config')) &&
			Configuration::updateValue('PS_VERSION_DB', Db::getInstance()->getValue('SELECT shop_db_version FROM '._DB_PREFIX_.'temp_config')) &&
			Configuration::updateValue('PS_INSTALL_VERSION', Db::getInstance()->getValue('SELECT shop_install_version FROM '._DB_PREFIX_.'temp_config')))

			Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'temp_config`');

		return false;
	}

	private function _convertBytes($value)
	{
		/**** convert from byte to kb, mb, gb ***/
		
		if (is_numeric( $value ))
			return $value;
		else
		{
			$value_length = Tools::strlen($value);
			$qty = Tools::substr( $value, 0, $value_length - 1 );
			$unit = Tools::strtolower(Tools::substr( $value, $value_length - 1 ));
			switch ($unit)
			{
				case 'k':
					$qty *= 1024;
					break;
				case 'm':
					$qty *= 1048576;
					break;
				case 'b':
					$qty *= 1073741824;
					break;
			}
			return $qty;
		}
	}

	private function _getBrowser()
	{
		/*** get info about curent browser ***/
		
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent))
			$platform = 'linux';
		elseif (preg_match('/macintosh|mac os x/i', $u_agent))
			$platform = 'mac';
		elseif (preg_match('/windows|win32/i', $u_agent))
			$platform = 'windows';

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif (preg_match('/Firefox/i', $u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif (preg_match('/Chrome/i', $u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif (preg_match('/Safari/i', $u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif (preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif (preg_match('/Netscape/i', $u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches))
		{
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1)
		{
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub))
				$version = $matches['version'][0];
			else
				$version = $matches['version'][1];
		}
		else
			$version = $matches['version'][0];

		// check if we have a number
		if ($version == null || $version == "") 
			$version = "?";

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}

	private function _compabilityServer()
	{
		/*** Check server settings ***/
		
		// correct settings for server
		$must_settings = array(
			'safe_mode'           => 'off',
			'file_uploads'        => 'on',
			'memory_limit'        => 128,
			'post_max_size'       => 8,
			'upload_max_filesize' => 8,
			'max_input_time'      => 45,
			'max_execution_time'  => 30
		);

		// curret server settings
		$current_settings = array();

		//result array
		$result = array();

		if (ini_get('safe_mode')) $current_settings['safe_mode'] = 'on';
			else $current_settings['safe_mode'] = 'off';
		if (ini_get('file_uploads')) $current_settings['file_uploads'] = 'on';
			else $current_settings['file_uploads'] = 'off';
		$current_settings['memory_limit'] = (int)ini_get('memory_limit');
		$current_settings['post_max_size'] = (int)ini_get('post_max_size');
		$current_settings['upload_max_filesize'] = (int)ini_get('upload_max_filesize');
		$current_settings['max_input_time'] = (int)ini_get('max_input_time');
		$current_settings['max_execution_time'] = (int)ini_get('max_execution_time');

		$diff = array_diff_assoc($must_settings, $current_settings);

		if (strcmp($must_settings['safe_mode'], $current_settings['safe_mode']))
			$result['safe_mode'] = $must_settings['safe_mode'];
		if (strcmp($must_settings['file_uploads'], $current_settings['file_uploads']))
			$result['file_uploads'] = $must_settings['file_uploads'];

		foreach ($diff as $key => $value)
		{
			if ($current_settings[$key] < $value)
				$result[$key] = $value;
		}
		if (!empty($result))
		{
			$output = '';
			$count = 0;
			foreach ($result as $key => $value)
			{
				$units = '';
				if ($key == 'memory_limit' || $key == 'post_max_size' || $key == 'upload_max_filesize')
					$units = ' (Mb)';
				if ($key == 'max_input_time' || $key == 'max_execution_time')
					$units = ' (s)';
				$output .= '<tr>';
				$output .= '<td>'.$key.$units.'</td>';
				$output .= '<td class="text-center">'.$current_settings[$key].'</td>';
				$output .= '<td class="text-center">'.$must_settings[$key].'</td>';
				$count++;
				if ($count == 3)
					$output .= '</tr>';
			}

			return $output;	
		}
	}

	private function _compabilityBrowser()
	{
		/*** check browser compability ***/
		
		$response = $this->_getBrowser();
		$browser_not_supported = $response['name'] == 'Internet Explorer' && $response['version'] <= 9 || $response['name'] == 'Safari' && $response['version'] <= 6 ? true : false;
		if ($browser_not_supported)
		{
			$this->context->smarty->assign('info', array(   
				'name' => $response['name'],
				'version' => $response['version']
			));

			return $this->display(__FILE__, 'views/templates/admin/old-browser.tpl');

		}
		else
			return true;
	}

	public function getContent()
	{   
		//$this->checkSampleDataVersion();
		if ($this->_compabilityBrowser() === true)
			$this->_html .= $this->renderForm();
		else
			$this->_html .= $this->_compabilityBrowser();

		return $this->_html;
	}

	public function renderForm()
	{
		$this->context->smarty->assign('actions', array(   
			'postAction'     => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'max_file_size'  => $this->_convertBytes(ini_get('upload_max_filesize')),
			'max_file_size_text'  => ini_get('upload_max_filesize'),
			'baseDir'        => $this->_path.'sampledatainstall-ajax.php',
			'regenerateDir' => $this->context->link->getAdminLink('AdminImages').'#image_type_form',
			'output' => $this->_compabilityServer()
		)); 
	
		return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
	}
}