<?php
 
 namespace glx\Common;
 
 require_once 'ObjectAccess.php';
 
 class ReadOnly extends ObjectAccess
 {
    public function __set($name, $value)
    {
      throw new \glx\Exception('Can`t modify read-only configuration');
    }

    public function __unset($name)
    {
      throw new \glx\Exception('Can`t modify read-only configuration');
    }
 }