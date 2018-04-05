<?php 
function login()
		   {
		    require(ABSOLUTE_PATH.'/views/backend/loginView.php');
		    }
	 /*
	  ** action pour lister les posts coté admin
	  */

function adminListPost()
              {
              	 $post 	   = new PostManager();
		    	 $listPosts = $post->getListPosts();
              	require(ABSOLUTE_PATH.'/views/backend/viewPost.php');
              }
       /*
	  **action pour lister les commentaires coté admin
	  */

function adminListComment()
		{
			     $comment	   = new CommentManager();
		    	 $listComments = $comment->ListComment($_GET['id']);
		 require(ABSOLUTE_PATH.'/views/backend/viewComment.php');	
		}
				/*
		**action pour ouvrir le formulaire d'insertion d'un post
		*/
		
function formPost()
       {
	require(ABSOLUTE_PATH.'/views/backend/formView.php');
      }
		/*
		**action pour creer un post
		*/
		
function createPost()
       {
       	$post = new PostManager();
       	$post->insertPost($_POST['titlePost'],$_POST['subTitle'],$_POST['contentPost']);
	header('Location:index.php?action=adminListPost');
      }

      /*
	  **action pour suprimer un post
	    il faut suprimer les commentaires associés au post et par la suite suprimé le post lui meme
	    sinom cela ne fonctionne pas
	  */

function delPost()
    {
	$post = new PostManager();
    $post->deleteComment($_GET['id']);
	$post->deletePost($_GET['id']);
	header('Location:index.php?action=adminListPost');
    }
  function delComment()
  {
  	$post = new PostManager();
  	$post->deleteComment($_GET['id']);
  	header('Location:index.php?action=adminListPost');
  }