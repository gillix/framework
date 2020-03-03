<?php
 
 namespace glx\Cache;
 
 use glx\Common;

 require_once __DIR__.'/../Common/Collection.php';
 
 class Temporary extends Common\Collection
 {
    public function __construct()
    {
      parent::__construct($array = []);
    }
 }