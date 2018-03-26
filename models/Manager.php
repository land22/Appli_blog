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
		try {
		$this->_db = new PDO('mysql:host=localhost;dbname=blog_Appli;charset=utf8', 'root', '');
		}
		catch(Exception $e){
			die('Erreur :'.$e->getMessage());
		}
		}
	}

}