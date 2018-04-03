<?php 
function login()
		  {
		    require(ABSOLUTE_PATH.'/views/backend/loginView.php');
		   
		   }

function adminListPost()
              {
              	$post 	   = new PostManager();
		    	 $listPosts = $post->getListPosts();
              	require(ABSOLUTE_PATH.'/views/backend/viewPost.php');
              }
function adminListComment()
		{
			     $comment	   = new CommentManager();
		    	 $listComments = $comment->ListComment();
		 require(ABSOLUTE_PATH.'/views/backend/viewComment.php');	
		}