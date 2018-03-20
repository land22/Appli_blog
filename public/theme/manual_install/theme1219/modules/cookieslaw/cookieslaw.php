<?php
/*
	Module Name: Cookies Law
	Description: Seek agreement on the EU cookie law. 
	Version: 0.3
	Author: Art Acherman (info@artacherman.nl)
	Based on: cookieswarning from Paul R Campbell (cookiewarning)
	
	Original cookiewarning module code: Copyright 2012, paul r campbell (pcampbell@ecartservice.net)
	This cookieslaw module code: Copyright 2012, Art Acherman
	Javascript: Scott Herbert (www.scott-herbert.com)
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	This copyright notice  and licence should be retained in all modules based on this framework.
	This does not affect your rights to assert copyright over your own original work.
*/

if (!defined('_PS_VERSION_'))
	exit;
	
class CookiesLaw extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
	function __construct()
	{
		$version_mask = explode('.', _PS_VERSION_, 3);
		$version_test = $version_mask[0] > 0 && $version_mask[1] > 3;

		$this->name = 'cookieslaw';
		$this->tab = $version_test ? 'front_office_features' : 'Tools';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Cookies Law');
		$this->description = $this->l('The 2012 EU Cookie law requires that sites seek positive consent to allow the storing of certain cookies.');
		if ($version_test)
			$this->author = 'Art Acherman';
		$this->version = '0.3';
		$this->bootstrap = true;
	}

	public function install()
	{
		return 
			parent::install() && 
			$this->registerHook('header') && 
			$this->registerHook('displayHeader') &&
			Configuration::updateValue($this->name.'_cookieurl', 'index.php?id_cms=6&controller=cms') &&	
			Configuration::updateValue($this->name.'_cookietop' , 'on') &&
			Configuration::updateValue($this->name.'_redirect', 'http://www.templatemonster.com/prestashop-themes.php');
	}
	
	public function uninstall()
	{
		Configuration::deleteByName($this->name.'_cookieurl');
		Configuration::deleteByName($this->name.'_cookietop');
		Configuration::deleteByName($this->name.'_redirect');
		return parent::uninstall();
	}
		
	public function getContent()
	{
		$this->_html = '';
		
		if (Tools::isSubmit('submit'))
		{			
			Configuration::updateValue($this->name.'_cookieurl', Tools::getValue('cookieurl'));
			Configuration::updateValue($this->name.'_cookietop', Tools::getValue('cookietop'));
			Configuration::updateValue($this->name.'_redirect', Tools::getValue('redirect'));
			$this->_html .= '<div class="alert alert-success">'.$this->l('Updated').'</div>';
		}
		
		return $this->_displayForm();
	}
	
	private function _displayForm()
	{
		$this->_html .='<div class="panel">';
		$this->_html .= '<h4 class="panel-heading">'.$this->displayName.'</h4>';
		$this->_html .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="defaultForm form-horizontal">
			<fieldset>
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<div class="checkbox">
							<label class="control-label">
								'.$this->l('Top of screen').'
								<input type="checkbox" name="cookietop"'.(Tools::getValue('cookietop', Configuration::get($this->name.'_cookietop')) ? ' checked="checked"' : '').' />
							</label>
							<p class="clear"><small>'.$this->l('Displays the cookie law either at top or bottom of the screen.').'</small></p>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">'.$this->l('URL to your cms cookie policy').'</label>
					<div class="col-lg-6">
						<input type="text" name="cookieurl" value="'.Tools::getValue('cookieurl', Configuration::get($this->name.'_cookieurl')).'"/>
						<p class="clear"><small>'.$this->l('Supply the url of the CMS page containing your cookie policy.').'</small></p>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-xs-2 control-label">'.$this->l('Link to redirect to').'</label>
					<div class="col-xs-6">
						<input type="text" name="redirect" value="'.Tools::getValue('redirect', Configuration::get($this->name.'_redirect')).'"/>
						<p class="clear"><small>'.$this->l('If a user does not agree, then they will be directed away from the site to here.').'</small></p>
					</div>
				</div>
				<div class="panel-footer">
					<button type="submit" name="submit" class="button btn btn-default btn-large"><i class="process-icon-save"></i>'.$this->l('Save').'</button>
				</div>
			</fieldset>
		</form>';
		
		$this->_html .='</div>';
		
		return $this->_html;
	}
		
	function hookHeader($params)
	{
		global $smarty, $cookie;
		
		Tools::addCSS(($this->_path).'cookieslaw.css', 'all');
		Tools::addJS(($this->_path).'cookieslaw.js');
		
		$cookie_path = trim(__PS_BASE_URI__, '/\\').'/';
		if ($cookie_path{0} != '/') $cookie_path = '/'.$cookie_path;
		$cookie_path = rawurlencode($cookie_path);
		$cookie_path = str_replace('%2F', '/', $cookie_path);
		$cookie_path = str_replace('%7E', '~', $cookie_path);
		
		$smarty->assign(array(
			'cl_RedirectLink' => Configuration::get($this->name.'_redirect'),
			'cl_CookieTop' => Configuration::get($this->name.'_cookietop'),
			'cl_CookieUrl' => Configuration::get($this->name.'_cookieurl'),
			'cl_CookieName' => 'jsEUCookieslawCheck',
			'cl_CookiePath' => $cookie_path,
			'cl_CookieDomain' => $this->getDomain(),
			'cl_ajaxUrl' => Tools::getHttpHost(true)._MODULE_DIR_.'cookieslaw/cookie_ajax.php'
		));
		return $this->display(__FILE__, 'cookieslaw.tpl');
	}
	
	// Get cookie domain (protected function in Cookie class)
	protected function getDomain()
	{
		$r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';
	    preg_match ($r, Tools::getHttpHost(false, false), $out);
		if (preg_match('/^(((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]{1}[0-9]|[1-9]).)'. 
         '{1}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]).)'. 
         '{2}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]){1}))$/', $out[4]))
			return false;
		if (!strstr(Tools::getHttpHost(false, false), '.'))
			return false;
		$domain = $out[4];
		$subDomains = SubDomain::getSubDomains();
		if ($subDomains === false)
			die(Tools::displayError('Bad SubDomain SQL query.'));
		foreach ($subDomains AS $subDomain)
		{
			$subDomainLength = strlen($subDomain) + 1;
			if (strncmp($subDomain.'.', $domain, $subDomainLength) == 0)
				$domain = substr($domain, $subDomainLength);
		}
		return $domain;
	}
}