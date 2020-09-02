<?php
    
    namespace glx\HTTP\I;
    
    
    interface Request extends Message
    {
        public function method(): string;
        
        public function uri(): URI;
        
        public function target(): string;
    }