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
    $post 	   = new PostManager();
    $listPosts = $post->getListPosts();
    require(ABSOLUTE_PATH.'/views/frontend/listPostsView.php');
}
