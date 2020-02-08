<?php

 namespace glx\core;
 
 use glx\Common\_float;
 use glx\Common\_integer;
 use glx\Context;

 class FloatNumber extends Property
 {
    protected static string $_type = 'FLOAT';
    
    public function __construct($options)
    {
      if(is_float($options))
        $this->value = new _float($options);
      parent::__construct($options);
    }
 
    public static function new(...$arguments): I\Property
    {
      return new static(...$arguments);
    }
  
    public static function resolve($value): ? I\Property
    {
      if(is_int($value))
        return self::new($value);
      return NULL;
    }
  
    public function equals($other): bool
    {
      return $this->value === $other;
    }
 }
 
 // register class as value resolver
 Unit::resolver(FloatNumber::class);


 /**
  * global function for simplify usage
  * creates new object of Integer
  * @param mixed ...$arguments
  * @return I\Property
  */
 function float(...$arguments): I\Property
 {
   return FloatNumber::new(...$arguments);
 }
 
