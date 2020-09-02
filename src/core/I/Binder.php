<?php
    
    namespace glx\core\I;
    
    interface Binder
    {
        public function name(): string;
        
        public function origin(): Entity;
        
        public function visibility(): int;
        
        public function profile(): string;
    }