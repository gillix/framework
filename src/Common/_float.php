<?php
 
 namespace glx\Common;
 
 require_once 'I/_float.php';
 require_once '_number.php';

 class _float extends _number implements I\_float
 {

    public function int(): I\_integer
    {
      return new _integer((int)$this->__value);
    }
   
    public function round(int $mode = I\_float::ROUND_HALF, int $precision = 0): bool
    {
      switch($mode)
       {
         case I\_float::ROUND_UP:   $rounded = floor($this->__value); break;
         case I\_float::ROUND_DOWN: $rounded = ceil($this->__value); break;
         default: $rounded = round($this->__value, $precision); break;
       }
      return $rounded;
    }
 
    public function get(): float
    {
      return $this->__value;
    }
 }