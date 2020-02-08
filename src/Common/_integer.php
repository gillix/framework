<?php
 
 namespace glx\Common;
 
 require_once 'I/_integer.php';
 require_once '_number.php';

 
 class _integer extends _number implements I\_integer
 {

    public function float(): I\_float
    {
      return new _float((float)$this->__value);
    }
 
    public function get(): int
    {
      return $this->__value;
    }
 }