<?php

 namespace glx\core;
 
 use glx\Common\_integer;
 use glx\Context;

 class Integer extends Property
 {
    protected static string $_type = 'INTEGER';
    
    public function __construct($options)
    {
      if(is_int($options))
        $this->value = new _integer($options);
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
     // TODO: учесть что может прийти объект _integer или Joint
      return $this->value === $other;
    }
 }
 
 // register class as value resolver
 Unit::resolver(Integer::class);


 /**
  * global function for simplify usage
  * creates new object of Integer
  * @param mixed ...$arguments
  * @return I\Property
  */
 function integer(...$arguments): I\Property
 {
   return Integer::new(...$arguments);
 }
 
