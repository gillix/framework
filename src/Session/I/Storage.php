<?php
 
 namespace glx\Session\I;
 
 
 interface Storage
 {
    public function get(string $name);
 }