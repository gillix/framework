<?php
 
 namespace glx\Common;
 
 require_once 'I/ObjectAccess.php';
 
 
 class Collection implements I\Collection, \IteratorAggregate, \Countable
 {
    protected ?array $content = NULL;
    protected ?array $linked = NULL;
    
    public function __construct(array &$array)
    {
      $this->content = &$array;
    }
  
    public function __get($name)
    {
      return (is_array($this->content[$name]) ? new self($this->content[$name]) : $this->content[$name]) ?? $this->checkLinked($name);
    }
  
    protected function checkLinked($name, $isset = false)
    {
      if($this->linked === NULL) return NULL;
      foreach($this->linked as $linked)
        if(($isset && ($result = isset($linked[$name]))) || ($result = $linked[$name]))
          return $result;
      return NULL;
    }
   
    public function __set($name, $value)
    {
      $this->content[$name] = $value;
    }
  
    public function __isset($name): bool
    {
      return isset($this->content[$name]) || $this->checkLinked($name, true);
    }
  
    public function __unset($name)
    {
      unset($this->content[$name]);
    }
 
    public function offsetExists($name): bool
    {
      return $this->__isset($name);
    }
  
    public function offsetGet($name)
    {
      return $this->__get($name);
    }
  
    public function offsetSet($name, $value ): void
    {
      $this->__set($name, $value);
    }
  
    public function offsetUnset($name): void
    {
      $this->__unset($name);
    }
  
    public function array(): array
    {
      return $this->content;
    }
   
    public function getIterator()
    {
      return new \ArrayIterator($this->content);
    }
   
    public function count()
    {
      return count($this->content);
    }
 
    public function link(I\Collection $another): void
    {
      if($this->linked === NULL)
        $this->linked = [];
      $this->linked[] = $another;
    }
 
 }
