<?php
/**
 * 
 * @author camille
 *
 */
class Manager
{
	protected $_db = null;

	public function __construct() {
		$this->_db = new PDO('mysql:host=localhost;dbname=blog_MVC;charset=utf8', 'root', 'root');
	}

}