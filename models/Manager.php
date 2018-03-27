<?php
/**
 * 
 * @author Landry
 *
 */
class Manager
{
	protected $_db = null;

	public function __construct() {
		
		$this->_db = new PDO('mysql:host=localhost;dbname=blog_Appli;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
	

}