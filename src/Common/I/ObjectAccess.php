<?php
 
 namespace glx\Common\I;
 
 interface ObjectAccess extends \ArrayAccess
 {
    public function __get($name);
    public function __set($name, $value);
    public function __isset($name);
    public function __unset($name);
    public function array(): array;
    public function link(ObjectAccess $another): void;
 }
 
