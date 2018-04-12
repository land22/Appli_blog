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
    *avec un $id en parametre qui est optionel
    */
    public function ListComment($id){
        $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id_post = '.$id.'');
        $comments->execute();
        return $comments;
    }
    /*
    *Liste les commentaires du site modérer
    *
    */
    public function ListCommentModer() {
         $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, comment, flag, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE flag = 1 ');
        $comments->execute();
        return $comments;
    }
    /*
    *requette pour moderer un commentaire
    *
    */
     public function ModerComment($idComment){
      $db = $this->_db;
      $req = $db->prepare('UPDATE comments SET flag = 1 WHERE id_comment = :id_comment');
      $req->execute(array('id_comment'=>$idComment));
    }
    /*
    *requette pour restaurer un commentaire moderer
    *
    */
     public function RestoreComment($idComment){
      $db = $this->_db;
      $req = $db->prepare('UPDATE comments SET flag = 0 WHERE id_comment = :id_comment');
      $req->execute(array('id_comment'=>$idComment));
    }
}