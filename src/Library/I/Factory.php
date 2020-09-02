<?php
    
    namespace glx\Library\I;
    
    
    interface Factory
    {
        public function has(string $id): bool;
        
        public function get(string $id, $default = null);
        
        public function new(string $id, $default = null, array $arguments = null);
        
        public function set(string $id, $maker): void;
        
        public function use(Factory $other): void;
    }