<?php
 
 namespace glx\Common;
 
 require_once 'Collection.php';
 
 class ReadOnly extends Collection
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