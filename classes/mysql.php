<?php

require_once(realpath(dirname(__FILE__).'/../config/config.php'));

class MySql {
    protected $_connection;
    
    public function __construct() {
        $this->_connection = mysql_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD);
        mysql_select_db(Config::DB_NAME);
    }
    
    public function __destruct() {
        mysql_close($this->_connection);
    }
    
    public function query($sql) {
        return mysql_query($sql, $this->_connection);
    }
    
    public function count($result) {
        return mysql_num_rows($result);
    }
    
    public function fetchNext($result) {
        return mysql_fetch_assoc($result);
    }
    
    public function freeResult($result) {
        mysql_free_result($result);
    }
    
    public function fetchAll($sql) {
        $result = $this->query($sql);
        $rows = array();
        while ($row = $this->fetchNext($result)) {
            $rows[] = $row;
        }
        $this->freeResult($result);
        return $rows;
    }
    
    public function fetchRow($sql) {
        $result = $this->query($sql);
        $row = $this->fetchNext($result);
        $this->freeResult($result);
        return $row;
    }
    
    public function fetchOne($sql) {
        $result = $this->query($sql);
        $value = mysql_result($result, 0);
        $this->freeResult($result);
        return $value;
    }
    
    public function insertId() {
        return mysql_insert_id($this->_connection);
    }
    
    public function escape($string) {
        return mysql_escape_string($string);
    }
}
?>
