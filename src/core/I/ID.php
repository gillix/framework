<?php
    
    namespace glx\core\I;
    
    interface ID
    {
        public function storage(): string;
        
        public function object(): string;
        
        public function __toString();
    }