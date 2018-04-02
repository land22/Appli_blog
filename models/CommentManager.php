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

    public function getComments($postId){
        $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id_post= ? ORDER BY comment_date DESC');
        $comments->execute(array($postId));
        return $comments;
    }
    public function insertComment($postId, $author, $comment){
      $db = $this->_db;
      $comments = $db->prepare('INSERT INTO comments(id_post, author_comment, comment, comment_date) VALUES(?, ?, ?, NOW())');
      $affectedLines = $comments->execute(array($postId, $author, $comment));
      return $affectedLines ; 
    }
    public function deleteComment($commentId){

    }
    public function updateComment($commentId){

    }
}