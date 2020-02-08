<?php
 namespace glx\core\I;

 interface Invokable 
 {
    public function apply(Joint $object, array $arguments = []);
    public function call(array $arguments = []);
 }