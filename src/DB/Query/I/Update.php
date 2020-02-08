<?php
 
 namespace glx\DB\Query\I;
 
 interface Update extends Joinable, Writable
 {
    public function set($name, $value = NULL): self;
 }
 