<?php
    
    namespace glx\core\I;
    
    interface Property extends Printable
    {
        public function get();
        
        public function equals($other): bool;
    }