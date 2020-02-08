<?php
 
 namespace glx\core;
 
 
 class Locator
 {
    private string $location;
    private $place;
  
    public function __construct(string $location,  &$place)
    {
      $this->location = $location;
      $this->place = &$place;
    }
  
    private function resolve(): I\Joint
    {
    
    }
  
    public function __call($name, $arguments)
    {
     // TODO: Implement __call() method.
    }
 }