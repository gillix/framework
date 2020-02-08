<?php
 
 namespace glx\Cache;
 
 use glx\Common;

 require_once __DIR__.'/../Common/ObjectAccess.php';
 
 class Temporary extends Common\ObjectAccess
 {
    public function __construct()
    {
      parent::__construct($array = []);
    }
 }