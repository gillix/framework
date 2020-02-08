<?php
 namespace glx\core\I;

 interface File 
 {
    public function source(): string;
    public function uri(): string;
    public function url(): string;
 }