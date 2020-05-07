<?php

 namespace glx\core;
 
 use glx\Common\_string;
 use glx\Context;

 class Str extends Property
 {
    protected static string $_type = 'STRING';
    
    public function __construct($options)
    {
      if(is_string($options))
        $this->value = new _string($options);
      parent::__construct($options);
    }
 
    public static function new(...$arguments): I\Property
    {
      return new static(...$arguments);
    }
  
    public static function resolve($value): ? I\Property
    {
      if(is_string($value))
        return self::new($value);
      return NULL;
    }
  
    public function equals($other): bool
    {
      return $this->value === $other;
    }

    public function __call($name, $arguments)
    {
      $value = $this->get();
      if(method_exists($value, $name))
        return call_user_func_array([$value, $name], $arguments);
      return NULL; // бросать исключение
    }
 }
 
 // register class as value resolver
 Unit::resolver(Str::class);


 /**
  * global function for simplify usage
  * creates new object of Str
  * @param mixed ...$arguments
  * @return I\Property
  */
 function string(...$arguments): I\Property
 {
   return Str::new(...$arguments);
 }
 
