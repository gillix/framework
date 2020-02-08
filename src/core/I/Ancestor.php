<?php
 namespace glx\core\I;

 interface Ancestor 
 {
    public function ancestorOf($inheritor): bool;
 }