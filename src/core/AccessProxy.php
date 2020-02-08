<?php
 namespace glx\core;

 trait AccessProxy 
 {
    public function __call($name, $arguments)
    {
      if($method = $this->get($name, 'method'))
        return $method->apply($this->this(), $arguments);
      return NULL;
    }
  
    public function __set($name, $value)
    {
      $this->add($name, $value);
    }
  
    public function __get($name)
    {
      return $this->get($name);
    }
 
    public function __isset($name)
    {
      return $this->has($name);
    }
   
    public function __unset($name)
    {
      $this->remove($name);
    }
 }