<?php 

/*Initialisation de la classe user manager*/

function login()
{
	require(ABSOLUTE_PATH.'/views/backend/loginView.php');
}

 /*
 ** action pour lister les posts coté admin
 */
function adminListPost()
{
  if (isset($_SESSION['auth']))
  {
    $post = new PostManager();
		$listPosts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/backend/viewPost.php');
  }
  else
  {
  header('Location:index.php');
  }
}

/*
**action pour lister les commentaires coté admin
*/
function adminListComment()
{  
  if (isset($_SESSION['auth']))
  {
	  $comment = new CommentManager();
		if (isset($_GET['id']))
    {
			$listComments = $comment->ListComment($_GET['id']);
		}
    else
    {
      $listComments = $comment->ListComment();
		}    	
	  require(ABSOLUTE_PATH.'/views/backend/viewComment.php');
	}
  else
  {
    header('Location:index.php');
  }	
}
/*
**action pour lister les commentaires moderer
*/
function adminListCommentModer()
{
  if (isset($_SESSION['auth']))
  {
    $comment = new CommentManager();
    $listComments = $comment->ListCommentModer();
    require (ABSOLUTE_PATH.'/views/backend/viewCommentModer.php');
  }
  else
  {
    header('Location:index.php');
  }

}

/*
**action pour ouvrir le formulaire d'insertion d'un post
*/		
function formPost()
{ 
  if (isset($_SESSION['auth'])) 
  {
	  require(ABSOLUTE_PATH.'/views/backend/formView.php');
	}
  else
  {
    header('Location:index.php');
  }
}

/*
**action pour creer un post
*/
function createPost()
{
  if (isset($_SESSION['auth']))
  {
    $post = new PostManager();
    $post->insertPost($_POST['titlePost'],$_POST['subTitle'],$_POST['contentPost']);
	  header('Location:index.php?action=adminListPost');
	}
  else
  {
  header('Location:index.php');
  }
}

/*
**Action pour le formulaire de modification d'un post
*/
function formUpdatePost()
{ 
  if (isset($_SESSION['auth'])) 
  {
    $post = new PostManager();
    $data = $post->getPost($_GET['id']);
    require(ABSOLUTE_PATH.'/views/backend/formViewUpdate.php');
  }
  else 
  {
    header('Location:index.php');
  }
}

/*
**action pour suprimer un post
il faut suprimer les commentaires associés au post et par la suite suprimé le post lui meme
sinom cela ne fonctionne pas
*/
function delPost()
{
  if (isset($_SESSION['auth'])) 
  {
		$post = new PostManager();
	  if (isset($_GET['id'])) 
    {
	  	$post->deleteComment($_GET['id']);
	  }
	  else
    {
	    $post->deleteComment();
	  }
	$post->deletePost($_GET['id']);
	header('Location:index.php?action=adminListPost');
  }
  else
  {
    header('Location:index.php');
  }
}
/*
**Action pour éditer un post
*/
function upPost()
{
  if (isset($_SESSION['auth'])) 
  {
    $post = new PostManager();
    if (isset($_GET['id']))
    {
      $post->getPost($_GET['id']);
      $post->updatePost($_POST['id'], $_POST['titlePost'], $_POST['subTitle'], $_POST['contentPost']);
    }
    header('Location:index.php?action=adminListPost');
  }
  else 
  {
    header('Location:index.php');
  }
  
}
/*
**action pour suprimer un commentaire
*/
function delComment()
{ 
  if (isset($_SESSION['auth']))
  {
    $post = new PostManager();
  	if (isset($_GET['id']))
    {
  	 $post->deleteComment($_GET['id']);
  	}
  	else
    {
      $post->deleteComment();
  	}
  	header('Location:index.php?action=adminListPost');
  }
  else
  {
  header('Location:index.php');
  }
}
  /*
  Parti pour la gestion d'autentification
  */
function connect()
{
  $user = new UserManager();
  $pass = md5($_POST['password']);
  $user->logint($_POST['username'],$pass);
}
/*
Parti pour la deconnecxion de l'utilisateur
*/  
function disconnect()
{
  $user = new UserManager();
  $user->logout();
}