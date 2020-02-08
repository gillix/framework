<?php
 namespace glx\core\I;

 interface Super 
 {
    public function get(string $name, $type = NULL): ? Joint;
 }