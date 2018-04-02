<?php
/*
*@author landry
*/
class CommentManager extends Manager
{
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
    }

   
    public function deleteComment($idComment){
      $db = $this->_db;
      $db->exec('DELETE FROM comments WHERE id_comment ='.$idComment.'')
    }
    public function updateComment($commentId, $author,$comment){
     $db = $this->_db;
     $db->prepare('UPDATE comments SET author_comment =: author_comment, comment_date=: NOW(), comment=: comment WHERE id_comment=:id_comment');
     $db->bindValue('autho_comment',$author,PDO::PARAM_STR);
     $db->bindValue('comment',$comment,PDO::PARAM_STR);
     $db->bindValue('id_comment',$commentId, PDO::PARAM_INT);
     $db->execute();
    }
}