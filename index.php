<?php


session_start();

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
       if(!empty($_GET['id'])){
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
	    //Route pour la page de contact
	case 'contact':
	     contact();
	     break;
	    //parti concernant la page de connexion
	 case 'login':
	   login();
	    break;
	    // action Pour Liste les post coté admin 
	  case 'adminListPost':
	  adminListPost();
	   break;
       // action pour Liste les Commentaires coté admin 
	   case 'adminListComment':
	   adminListComment();
	   break;
	   //action pour lister les commentaires moderés
	   case 'adminListCommentModer':
	   adminListCommentModer();
	   break;
	   //action pour ouvrir le formulaire d'insertion d'un post
	   case 'formPost':
	   formPost();
	   break;
	   //action pour creer un post
	   case 'createPost':
	   createPost();
	   break;
      //action pour supprimer un post
	   case 'delPost':
	   delPost();
	   break;
      //action pour supprimer un commentaire
	   case 'formUpdatePost':
	   formUpdatePost();
	    break;
	   case 'upPost':
         upPost();
         break;
	   case 'delComment':
	     delComment();
	     break;
	    case 'connect':
	    connect();
	    break;
	    case 'disconnect':
	    disconnect();
	    break;
  	// Par défaut, on affiche la page d'accueil
	default:
	 homePosts();
		//homePage();
		break;

}