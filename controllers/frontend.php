<?php


/**
 * 
 */
function homepage()
{
    require(ABSOLUTE_PATH.'/views/frontend/homePostsView.php');
}

/**
 * 
 */
function listPosts()
{
	/*
    liste des post pour la page d'acceuil
    */
    $post 	   = new PostManager();
    $homePosts = $post->getListPosts($limit = 'LIMIT 1');
    require(ABSOLUTE_PATH.'/views/frontend/homePostsView.php');
    /*
    liste des post pour la page des listes des post
    */
    if (empty($homePosts)) {
    	 $listPosts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
    }
   
}


