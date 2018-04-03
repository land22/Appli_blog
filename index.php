<?php

// Definition du path absolu
define("ABSOLUTE_PATH", dirname(__FILE__));

// Inclusion du controleur 
require('controllers/frontend.php');
require('controllers/backend.php');

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
	//Affiche le contenu d'un post
    case 'post':
       if(isset($_GET['id']) && $_GET['id'] > 0){
		Post();
		}
		else {
			homePosts();
		}
		break;
		//Permet d'ajouter un commentaire à un post
	case 'addComment':
	    addComment();
	    break;
	    //parti concernant l'administration
	 case 'login':
	   login();
	    break;
	    
	  case 'adminListPost':
	  adminListPost();
	   break;

	   case 'adminListComment':
	   adminListComment();
	   break;



  	// Par défaut, on affiche la page d'accueil
	default:
	 homePosts();
		//homePage();
		break;

}