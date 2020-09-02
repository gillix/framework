<?php
    
    namespace glx\core\I;
    
    interface Invokable extends Entity
    {
        public function apply(Joint $object, array $arguments = []);
        
        public function call(array $arguments = []);
    }