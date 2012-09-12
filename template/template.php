<?php

class Template {
    public function __construct($data = array()) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function render($template, $data = NULL) {
        $filename = realpath(dirname(__FILE__) . '/' .$template);
        if ($data) {
            $tpl = new Template($data);
            $tpl->render($template);
        } else {
            if (file_exists($filename)) {
                include($filename);
            }
        }
    }
}

?>
