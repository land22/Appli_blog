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

		    $post 	   = new PostManager();
		    $homePosts = $post->getListPosts($limit = 'LIMIT 5');
		    require(ABSOLUTE_PATH.'/views/frontend/homePostsView.php');
		   }

    /*
    liste des post pour la page des listes des post
    */ 
		 function listPosts(){
		        $post 	   = new PostManager();
		    	 $listPosts = $post->getListPosts();
		    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
		    }
		   



