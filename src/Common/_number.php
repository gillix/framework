<?php
 
 namespace glx\Common;
 
 use glx\Common\I\_string;

 require_once 'I/_number.php';
 
 abstract class _number implements I\_number
 {
    protected $__value;
    
    public function __construct($value)
    {
      $this->__value = $value instanceof self ? $value->__value : $value;
    }
  
    public function abs() { return abs($this->__value); }
    public function format($format): _string { return _string::format($format, $this->__value); }
    public function __toString() { return (string)$this->__value; }
 }