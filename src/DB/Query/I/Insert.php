<?php
    
    namespace glx\DB\Query\I;
    
    interface Insert extends Writable
    {
        public function set($name, $value = null): self;
        
        public function values($values): self;
        
        public function fields($fields): self;
        
        public function orUpdate(...$fields): self;
    }
 
 