<?php


/**
 * 
 */
function homepage()
{
    require(ABSOLUTE_PATH.'/views/frontend/homePostsView.php');
}

/**
 * liste des post pour la page d'acceuil

 */
function homePosts()
{

	$post = new PostManager();
	$homePosts = $post->getListPosts($limit = 'LIMIT 5');
	require(ABSOLUTE_PATH.'/views/frontend/homePostsView.php');
}

 /*
 liste des posts pour la page des listes des post
 */ 
function listPosts()
{
	$post = new PostManager();
    $listPosts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
}

/*
 Action pour lister les commentaires et son post associé
 */ 
function Post()
{
	$result = new PostManager();
	$post = $result->getPost($_GET['id']);
	if ( empty($post) ) 
	{
		header('Location: index.php');
				exit;
	}
	$comments = $result->getComments($_GET['id']);
	require(ABSOLUTE_PATH.'/views/frontend/PostView.php');
}
/*
*Action pour la page de contact
*/
function contact(){
	require(ABSOLUTE_PATH.'/views/frontend/contactView.php');
}

/*
 action pour ajouter un commentaire sur le blog
 */ 
function addComment()
{
	$result = new PostManager();
	$affectedLines = $result->insertComment($_GET['id'],$_POST['author'],$_POST['comment']);
	if($affectedLines === false)
	{
		die('Impossible d\'ajouter le commentaire !');

	}
	else 
	{
		header('Location: index.php?action=post&id='.$_GET['id']);
	}
}
function signalComment()
{
	$req = new CommentManager();
	$req->ModerComment($_GET['id']);
	header('Location:index.php?action=listPosts');
}

		   



