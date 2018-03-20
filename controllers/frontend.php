<?php


/**
 * 
 */
function homepage()
{
    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
}

/**
 * 
 */
function listPosts()
{
    $post 	   = new Post();
    $listposts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
}
