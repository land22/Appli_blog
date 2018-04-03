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
    /*
      fonction permettant de suprimer un commentaire
    */
   
   /* public function deleteComment($idComment){
      $db = $this->_db;
      $db->exec('DELETE FROM comments WHERE id_comment ='.$idComment.'')
    }*/
    /*
    fonction permettant de modifier un commentaire 
    */
    public function updateComment($commentId, $author,$comment){
     $db = $this->_db;
     $db->prepare('UPDATE comments SET author_comment =: author_comment, comment_date=: NOW(), comment=: comment WHERE id_comment=:id_comment');
     $db->bindValue('autho_comment',$author,PDO::PARAM_STR);
     $db->bindValue('comment',$comment,PDO::PARAM_STR);
     $db->bindValue('id_comment',$commentId, PDO::PARAM_INT);
     $db->execute();
    }
    /*
    *Liste les commentaires du site
    */
    public function ListComment(){
        $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments');
        $comments->execute();
        return $comments;
    }
}