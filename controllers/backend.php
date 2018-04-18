<?php 

/*Initialisation de la classe user manager*/

function login()
{
	require(ABSOLUTE_PATH.'/views/backend/loginView.php');
}

function checkLogin()
{
  if (!isset($_SESSION['auth']))
  {
    header('Location:index.php');
  }
}

 /*
 ** action pour lister les posts coté admin
 */
function adminListPost()
{
    checkLogin();
    $post = new PostManager();
		$listPosts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/backend/viewPost.php');
  

}

/*
**action pour lister les commentaires coté admin
*/
function adminListComment()
{  
    checkLogin();
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
/*
**action pour lister les commentaires moderer
*/
function adminListCommentModer()
{
    checkLogin();
    $comment = new CommentManager();
    $listComments = $comment->ListCommentModer();
    require (ABSOLUTE_PATH.'/views/backend/viewCommentModer.php');
  
}
/*
**action pour restaurer un commentaire
*/
function adminRestorComment()
{
    checkLogin();
    if(isset($_GET['id']))
    {
    $comment = new CommentManager();
    $comment->RestoreComment($_GET['id']);
    header('Location:index.php?action=adminListCommentModer');
    }
    else 
    {
     header('Location:index.php?action=adminListPost'); 
    }

}

/*
**action pour ouvrir le formulaire d'insertion d'un post
*/		
function formPost()
{ 
  checkLogin();
	require(ABSOLUTE_PATH.'/views/backend/formView.php');	  
}

/*
**action pour creer un post
*/
function createPost()
{
    checkLogin();
    $post = new PostManager();
    $post->insertPost($_POST['titlePost'],$_POST['subTitle'],$_POST['contentPost']);
	  header('Location:index.php?action=adminListPost');
}

/*
**Action pour le formulaire de modification d'un post
*/
function formUpdatePost()
{ 
    checkLogin();
    $post = new PostManager();
    $data = $post->getPost($_GET['id']);
    require(ABSOLUTE_PATH.'/views/backend/formViewUpdate.php');
  
}

/*
**action pour suprimer un post
il faut suprimer les commentaires associés au post et par la suite suprimé le post lui meme
sinom cela ne fonctionne pas
*/
function delPost()
{
    checkLogin();
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
/*
**Action pour éditer un post
*/
function upPost()
{
    checkLogin();
    $post = new PostManager();
    if (isset($_GET['id']))
    {
      $post->getPost($_GET['id']);
      $post->updatePost($_POST['id'], $_POST['titlePost'], $_POST['subTitle'], $_POST['contentPost']);
    }
    header('Location:index.php?action=adminListPost');
  
  
}
/*
**action pour suprimer un commentaire
*/
function delComment()
{ 
    checkLogin();
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