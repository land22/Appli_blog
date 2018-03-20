<?php
/**
 * 
 * @author camille
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


}