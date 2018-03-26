<?php
/**
 * 
 * @author landry
 *
 */
class Post extends Manager
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
    
    public function getListPosts()
    {
        $db       = $this->_db;
        $request  = $db->query('SELECT * FROM posts');
        $result   = $request->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPost($postId){
                  $db = $this->_db;
                  $req = $db->prepare('SELECT id_post, title_post, content_post, DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?');
                  $req->execute(array($postId));
                  $post = $req->fetch();
                  return $post;
    }

    public function getComments($postId){
        $db = $this->_db;
        $comments = $db->prepare('SELECT id_comment, author_comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id_post= ? ORDER BY comment_date DESC');
        $comments->execute(array($postId));
        return $comments;
    }


}