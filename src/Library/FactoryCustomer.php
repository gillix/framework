<?php
 
 namespace glx\Library;
 
 
 trait FactoryCustomer
 {
    protected I\Factory $factory;
    
    public function factory(I\Factory $factory = NULL): I\Factory
    {
      if($factory)
        $this->factory = $factory;
      return $this->factory;
    }
 }