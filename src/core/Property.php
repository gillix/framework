<?php

 namespace glx\core;
 
 use glx\Context;

 abstract class Property extends Unit implements I\Property
 {
    protected static string $_type = 'PROPERTY';
    protected $value;
    
    public function __construct($options)
    {
      if(is_array($options))
        if(isset($options['value']))
           $this->value = $options['value'];
      parent::__construct(is_array($options) ? $options : []);
    }
  
    public function get()
    {
      return $this->value;
    }
  
    public function __toString()
    {
      return (string)$this->get();
    }
 }
 
 