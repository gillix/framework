<?php
 namespace glx\core\I;

 interface Rewriter 
 {
    public function extend(?array $options = NULL): ? string;
 }