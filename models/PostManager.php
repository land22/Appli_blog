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
                  $req = $db->prepare('SELECT id_post, title_post, sub_title, content_post, DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id_post = ?');
                  $req->execute(array($postId));
                  $post = $req->fetch(PDO::FETCH_ASSOC);
                  return $post;
    }

   public function getComments($postId){
        $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id_post= ? ORDER BY comment_date DESC');
        $comments->execute(array($postId));
        return $comments;
    }

    public function insertPost($title, $subTitle, $content){
      $db = $this->_db;
      $posts = $db->prepare('INSERT INTO posts( title_post, sub_title, content_post, date_post) VALUES(?, ?, ?, NOW())');
      $affectedLines = $posts->execute(array($title, $subTitle, $content));
      return $affectedLines ; 
    }

    public function insertComment($postId, $author, $comment){
      $db = $this->_db;
      $comments = $db->prepare('INSERT INTO comments(id_post, author_comment, comment, comment_date) VALUES(?, ?, ?, NOW())');
      $affectedLines = $comments->execute(array($postId, $author, $comment));
      return $affectedLines ; 
    }
    public function updatePost($postId, $title, $sub_title, $content){
      $db = $this->_db;
      $req = $db->prepare('UPDATE posts SET title_post = :title, sub_title = :sub_title, content_post= :content_post, date_post = NOW() WHERE id_post = :id_post');
    $req->execute(array('title'=>$title,'sub_title' =>$sub_title,'content_post'=>$content,'id_post'=>$postId));
    }
    public function deletePost($postId){
      $db = $this->_db;
      $db->exec('DELETE FROM posts WHERE id_post ='.$postId.'');
    }
    public function deleteComment($id){
      $db = $this->_db;
      $db->exec('DELETE FROM comments WHERE id_post='.$id.' OR id_comment ='.$id.'');
    }


}