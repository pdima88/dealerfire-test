<?php

require_once ('mysql.php');

class Comments {
    protected $_db;
    public function __construct() {
        $this->_db = new MySql();
    }
    
    public function getComments() {
        $sql = "SELECT * FROM comments ORDER BY date";
        $rows = $this->_db->fetchAll($sql);
        $comments = new CommentList($rows);
        return $comments;        
    }
    
    public function insertComment($comment, $replyto = FALSE) {
        $name = $this->_db->escape($comment->author);
        $email = $this->_db->escape($comment->email);
        $comment = $this->_db->escape($comment->comment);
        if (!$replyto) $replyto = 'NULL';
        $sql = "INSERT INTO comments (name,email,comment,replyto) 
            VALUES('{$name}','{$email}','{$comment}',{$replyto})";
        $this->_db->query($sql);
        $id = $this->_db->insertId();
        $comment->id = $id;
        return $id;
    }
}

class CommentList {
    
    /**
     * List of comments
     * @var array 
     */
    public $comments;
    
    /**
     * CommentList constructor
     * @param array $array Rows with comments fetched from DB
     */
    public function __construct($array = array()) {
        $this->comments = array();
        foreach ($array as $item) {
            $comment = new Comment($item);
            if ($item['replyto']) {
                $parent = $this->getComment($item['replyto']);
                $parent->addReply($comment);
            } else {
                $this->add($comment);
            }
        }
    }
    
    /**
     * Gets comment by its ID
     * @param int $id
     * @return Comment or null, if comment not found 
     */
    public function getComment($id) {
        foreach ($this->comments as $comment) {
            if ($comment->id == $id) return $comment;
            $replyComment = $comment->replies->getComment($id);
            if ($replyComment != null) return $replyComment;
        }
        return null;
    }
    
    public function add($comment) {
        $this->comments[] = $comment;
    }
    
    public function count() {
        return count($this->comments);
    }
    
    
}

class Comment {
    public $id;
    public $date;
    public $author;
    public $email;
    public $comment;
    
    public $parent;
    public $replies;
    
    /**
     * Comment constructor
     * @param array $array Associative array, fetched from database
     */
    public function __construct($array = array()) {
        $this->replies = new CommentList();
        
        if (isset($array['id'])) $this->id = $array['id'];
        if (isset($array['date'])) $this->date = $array['date'];
        if (isset($array['name'])) $this->author = $array['name'];
        if (isset($array['email'])) $this->email = $array['email'];    
        if (isset($array['comment'])) $this->comment = $array['comment'];
    }
    
    /**
     * Appends specified comment object to own replies
     * @param Comment $comment 
     */
    public function addReply($comment) {
        $this->replies->add($comment);
        $comment->parent = $this;
    }
    
    public function validate() {
        $errors = array();
        if (empty($this->author)) $errors['author'] = 'empty';
        if (empty($this->email)) $errors['email'] = 'empty';
        else if (!preg_match('/[0-9a-z_]+@[0-9a-z_^.]+\.[a-z]{2,3}/i', $this->email)) $errors['email'] = 'invalid';
        if (empty($this->comment)) $errors['comment'] = 'empty';
        return empty($errors) ? TRUE : $errors;
    }
    
    
}

?>
