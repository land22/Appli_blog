<?php

// Definition du path absolu
define("ABSOLUTE_PATH", dirname(__FILE__));

// Inclusion du controleur 
require('controllers/frontend.php');

// Autoloader, chargement automatique des classes

function custom_autoloader($className) {
    $path      = ABSOLUTE_PATH .'/models/';
    $filename  = $path .$className . '.php';
    if ( file_exists($filename) ) {
        require_once $filename;
    }
}

spl_autoload_register( 'custom_autoloader' );



$action = "";
if ( !empty($_GET['action'] )) {
	$action = $_GET['action'];
}
switch ( $action  ) {

	// Liste les billets disponibles
	case 'listPosts':
		listPosts();
		break;

  	// Par défaut, on affiche la page d'accueil
	default:
	 listPosts();
		//homePage();
		break;

}