<?php
/**
 * 
 * @author landry
 *
 */
class PostManager extends Manager
{
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Retourne la liste des billets
     * @return array
     */
    
    public function getListPosts($limit = '')
    {
        $db       = $this->_db;
        $request  = $db->query('SELECT id_post, title_post, sub_title, DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts '.$limit.'');
        //$result   = $request->fetchAll(PDO::FETCH_ASSOC);
        return $request;
    }

    public function getPost($postId){
                  $db = $this->_db;
                  $req = $db->prepare('SELECT id_post, title_post, content_post, DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id_post = ?');
                  $req->execute(array($postId));
                  $post = $req->fetch();
                  return $post;
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
    public function updatePost($postId){

    }
    public function deletePost($postId){

    }


}