<?php
    
    namespace glx\Library;
    
    
    trait FactoryCustomer
    {
        protected I\Factory $factory;
        
        public function factory(I\Factory $factory = null): I\Factory
        {
            if ($factory) {
                $this->factory = $factory;
            }
            
            return $this->factory;
        }
    }